<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Mail\VerificationEmail;
use App\Question;
use App\Role;
use App\Sorting;
use App\Study;
use App\Token;
use App\User;
use Auth;
use Exception;
use File;
use Helper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Spatie\WebhookServer\WebhookCall;

class StudyController extends Controller
{
    protected const UPDATESTRING = 'update';

    public function show(Study $study)
    {
        if (auth()->user()->notOwnerNorInvited($study)) {
            abort(403, 'You are not authorized to see this content.');
        }

        $data['interviews'] = $study->interviews()->get();
        $data['invites'] = $study->invited;
        $data['breadcrumb'] = ['Home', 'Studies', $study->name];
        $data['publicInterviews'] = collect(DB::select('
        select study_interview_public_url.id,
               art_urls.id as url_id,
               art_urls.url as url,
               first_opened_at,
               submitted_at,
               art_urls.created_at,
               code
        from art_urls
        inner join study_interview_public_url
        on art_urls.url  like CONCAT(\'%\',study_interview_public_url.id,\'%\')
        where study_id = :study', ['study' => $study->id]));

        foreach ($data['publicInterviews'] as $publicInterview) {
            $publicInterview->shorturl = url('', 'short') . '=' . $publicInterview->code;
            $publicInterview->url = (\App\Helpers\Helper::get_string_between($publicInterview->url, '&interviewed=', '&t') == '') ? 'Name not provided' : Helper::get_string_between($publicInterview->url, '&interviewed=', '&t');
        }

        $data['study'] = $study;
        foreach ($data['interviews'] as $interview) {
            $interview['author'] = User::where('id', $interview['author'])->first()->email ?? $interview['author'];
        }

        return view('study.show', $data);
    }

    /**
     * Show the form for creating a new study.
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|\Illuminate\Contracts\View\View|Application|View
     */
    public function create(Request $request)
    {
        if (session('hasReachMaxNumberOfStudies')) {
            auth()->user()->addAction('Trying to create a study', $request->url(), 'Max numbers of studies reached for user ' . auth()->user()->email);
            abort(403, __('You reached the max number of studies'));
        }

        $data['breadcrumb'] = ['Home', 'New Study'];

        // if(!Auth::user ()->cancreatestudies())abort(403,'You cannot create studies');
        $data['isedit'] = 'false';
        $data['presetimages'] = Helper::getPresetImages();
        $data['classifiers'] = Helper::getClassifiers();

        return view('study.create_edit', $data);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|ResponseFactory|Application|Response
     */
    public function duplicate(Study $study)
    {
        $copy = $study->replicate();
        $copy->name = $copy->name . __(' duplicate');
        $copy->save();
        $study->load('questions', 'sortings', 'tokens');
        foreach ($study->getRelations() as $relationName => $values) {
            if ($relationName === 'sortings') {
                $request = new Request();
                $request->replace(['details' => $study->sortings[0]->pivot->details, 'sortingid' => $study->sortings[0]->id]);
                Sorting::store($request, $copy);
            } elseif ($relationName === 'questions') {
                $this->CopyQuestionsRemoveInterviews($study, $copy);
            } elseif ($relationName === 'tokens') {
                $this->RemoveTokensCreatedDuringInterview($values);
                $copy->{$relationName}()->attach($values);
            }
        }
        $copy->save();

        return response('study duplicated', 200);
    }

    private function CopyQuestionsRemoveInterviews(Study $study, Study $copy): void
    {
        foreach ($study->questions as $question) {
            $copiedQuestion = $copy->questions()->create($question->toArray());
            $answers = Answer::where('question_id', $question->id)->get();
            foreach ($answers as $answer) {
                $answer->question_id = $copiedQuestion->id;
                $copiedQuestion->answers()->create($answer->toArray());
            }
            // remove interviews
            foreach ($copiedQuestion->answers as $answer) {
                $answer->interviews()->detach();
                $answer->interviews()->delete();
            }
        }
    }

    private function RemoveTokensCreatedDuringInterview(&$values): void
    {
        // remove tokens created during interview
        foreach ($values as $index => $token) {
            if ($token->author == 0) {
                unset($values[$index]);
            }
        }
    }

    /**
     * @return JsonResponse
     */
    public function store(Request $request, $dummy = false)
    {
        if ($dummy != false) {
            $userForStudy = User::where('id', $dummy)->first();
            $request = new Request(config('utilities.dummyStudy1'));
        } else {
            $userForStudy = Auth::user();
        }
        if (! $request->has('name') || ! $request->has('description')) {
            return response()->json('Data are not valid', 422);
        }

        $newStudy = new Study();
        $newStudy->description = $request->description;
        $newStudy->name = $request->name;
        $newStudy->author = $request->author;
        $newStudy->user_id = $userForStudy->id;
        $newStudy->save();
        Sorting::store($request, $newStudy);

        $this->saveTokens($newStudy, $request, $token);

        $presortQuestions = $request->presort['questions'];
        $postSortQuestions = $request->postsort['questions'];
        $presortQuestions[0]['type'] = 'presort';
        $postSortQuestions[0]['type'] = 'postsort';
        $this->saveQuestionsAnswers($presortQuestions, $newStudy);
        $this->saveQuestionsAnswers($postSortQuestions, $newStudy);

        if ($request->input('sorting.qsortaskextremes')) {
            // create question extreme and 1 answer associated.
            Question::storeExtremeQuestion($request->input('sorting.qsortextremequestion'), $newStudy);
        }
        auth()->user()->addAction('Study Created', $request->url(), 'user created study ' . $newStudy->name);

        return response()->json(['message' => 'Study Saved!', 'studyid' => $newStudy->id], 200);
    }

    /**
     * Save the tokens to the database, if any.
     */
    public function saveTokens(Study $study, Request $request, &$token): void
    {
        if ($request->get('sorting')['tokens']) {
            foreach ($request->get('sorting')['tokens'] as $tokenToSave) {
                Token::store($tokenToSave, $study);
            }
        }
    }

    /**
     * Save the questions to database, if any.
     *
     * @param  bool  $edit
     */
    public function saveQuestionsAnswers($questions, $study, $edit = false)
    {
        if ($edit) {
            foreach ($study->questions as $questionToReset) {
                Answer::where('question_id', '=', $questionToReset['id'])->delete();
                Question::destroy($questionToReset['id']);
            }
        }
        $atLeastOneQuestion = array_key_exists('question', $questions[0]);
        if ($atLeastOneQuestion) {
            foreach ($questions as $questionToSave) {
                $question = new Question();
                $question->question = $questionToSave['question'];
                $question->detail = $questions[0]['type'];
                if (array_key_exists('canShowSorting', $questionToSave) && $questionToSave['canShowSorting']) {
                    $question->detail .= '|showsorting';
                }
                $question->study_id = $study->id;
                $question->save();
                $this->saveAnswers($questionToSave, $question);
            }
        }
    }

    /**
     * Save the answers for a given question.
     */
    private function saveAnswers($questionToSave, Question $question): void
    {
        if ($questionToSave['ismultiple']) {
            foreach ($questionToSave['answers'] as $answerToSave) {
                $answer = new Answer();
                $answer->question_id = $question->id;
                $answerJson = ['type' => 'multi', 'answer' => $answerToSave];
                $answer->answer = $answerJson;
                $answer->save();
            }
        }
        if ($questionToSave['isonechoice']) {
            foreach ($questionToSave['answers'] as $answerToSave) {
                $answer = new Answer();
                $answer->question_id = $question->id;
                $answerJson = ['type' => 'onechoice', 'answer' => $answerToSave];
                $answer->answer = $answerJson;
                $answer->save();
            }
        }
        if ($questionToSave['isopen']) {
            $answer = new Answer();
            $answerJson = ['type' => 'open', 'answer' => ''];
            $answer->question_id = $question->id;
            $answer->answer = $answerJson;
            $answer->save();
        }
        if ($questionToSave['isscale']) {
            $answer = new Answer();
            $answerJson = ['type' => 'scale', 'answer' => ['min' => $questionToSave['scalemin'], 'max' => $questionToSave['scalemax'], 'minlabel' => $questionToSave['minlabel'], 'maxlabel' => $questionToSave['maxlabel']]];
            $answer->question_id = $question->id;
            $answer->answer = $answerJson;
            $answer->save();
        }
    }

    /**
     * @return Factory|View
     *                      Show the edit page
     *
     * @throws AuthorizationException
     */
    public function edit(Study $study)
    {
        $this->authorize($study);
        $data['breadcrumb'] = ['Home', 'Edit Study'];

        $data['study'] = $study;
        $data['study']['tokens'] = $study->available_tokens;
        $data['study']['sorting'] = $study->sortings;
        $data['study']['tokens'] = Token::formatForEdit($data['study']['tokens']);
        $data['study']['q'] = Question::formatForEdit($study);
        $data['isedit'] = 'true';
        $data['presetimages'] = Helper::getPresetImages();
        $data['classifiers'] = Helper::getClassifiers();

        return view('study.create_edit', $data);
    }

    /**
     * @return JsonResponse|RedirectResponse
     *
     * @throws AuthorizationException
     */
    public function update(Study $study, Request $request)
    {
        $this->authorize($study);
        if (! $request->has('name')) {
            return response()->json('Data are not valid', 422);
        }
        $study = Study::where('id', '=', $request->input('id'))->with('questions')->first();
        if (! $study->isEditable()) {
            return back()->withInput();
        }
        // sorting
        Sorting::store($request, $study);
        $study->description = $request->description;
        $study->name = $request->name;
        $study->author = $request->author;
        $study->user_id = Auth::user()->id;
        $study->save();
        // watch out if we want more sortings
        $study->sortings()->sync($request->get('sorting')['id']);
        $this->removeTokensFromStudy($study);
        $this->saveTokens($study, $request, $token);
        $presortQuestions = $request->presort['questions'];
        $postsortQuestions = $request->postsort['questions'];
        $presortQuestions[0]['type'] = 'presort';
        $postsortQuestions[0]['type'] = 'postsort';
        $this->saveQuestionsAnswers($presortQuestions, $study, true);
        $this->saveQuestionsAnswers($postsortQuestions, $study, true);
        if ($request->input('sorting.qsortaskextremes')) {
            // create question extreme and 1 answer associated.
            Question::storeExtremeQuestion($request->input('sorting.qsortextremequestion'), $study);
        }

        return response()->json(['message' => 'Study Updated!', 'studyid' => $study->id], 200);
    }

    public function removeTokensFromStudy(Study $study): void
    {
        // @todo: delete files as well as tokens.
        $available_tokens = $study->available_tokens()->pluck('tokens.id')->toArray();
        foreach ($available_tokens as $tokensToDelete) {
            $to = Token::where('id', '=', $tokensToDelete)->first();
            $study->tokens()->detach($to->id);
            $to->delete();
        }
    }

    /**
     * @return ResponseFactory|Response
     *
     * @throws Exception
     */
    public function destroy(Study $study, Request $request)
    {
        if (auth()->user()->isNot($study->creator())) {
            abort(403, 'You are not allowed to edit this study');
        }
        foreach ($study->interviews as $interview) {
            File::delete($interview->sorting_screenshot);
            $interview->answers()->detach();
            $interview->answers()->delete();
            $interview->tokens()->detach();
            $interview->tokens()->delete();
            $interview->files()->delete();
            $interview->delete();
        }
        $study->interviews()->delete();
        $this->removeTokensFromStudy($study);
        $name = $study->name;
        $study->delete();
        auth()->user()->addAction('delete study', $request->url(), 'user deleted study ' . $name);

        return response('study deleted', 200);
    }

    /**
     * List users for frontend
     * consider to move to api.
     */
    public function getUsers(Study $study)
    {
        return $study->users()->get();
    }

    /**
     * @return JsonResponse
     */
    public function deleteallbyuser(User $user, Request $request)
    {
        $studies = Study::where('user_id', $user->id)->get();
        foreach ($studies as $study) {
            $study->delete();
        }
        auth()->user()->addAction('delete all studies by user', $request->url(), 'user deleted studies for the user  ' . $user->email);

        return response()->json(['message' => 'Studies Deleted!'], 200);
    }

    /**
     * @return JsonResponse
     */
    public function inviteUser(Request $request)
    {
        $study = Study::where('id', $request->input('study'))->first();
        $user = User::where('email', '=', $request->email)->first();
        if (! $user) {
            $user = new User();
            $user->email = $request->email;
            $user->password = Helper::random_str(60);
            $user->password_token = Helper::random_str(30);
            $user->save();
            $profile = $user->addProfile($user);
            Mail::to($user->email)->send(new VerificationEmail($user, $request->emailtext ? $request->emailtext : config('utilities.emailDefaultText')));
            $role = Role::where('name', '=', 'researcher')->first();
            $user->roles()->sync($role);

            if (! App::environment('local')) {
                WebhookCall::create()
                    ->url('https://chat.zemki.uni-bremen.de/hooks/Jj3dDY2KzSFDS2kxZ/SvbmjdswXTASAXxC2GfgfTpFooK5Eo4kFBGPyDRrtsWmgED3')
                    ->payload(['text' => 'User ' . $request->email . ' has registered on Mesort. We have a total of ' . User::all()->count() . ' users!'])
                    ->useSecret('Jj3dDY2KzSFDS2kxZ/SvbmjdswXTASAXxC2GfgfTpFooK5Eo4kFBGPyDRrtsWmgED3')
                    ->dispatch();
            }
        }
        if ($user->is($study->creator())) {
            return response()->json(['message' => __('You can\'t invite who created the study.!')], 200);
        }

        $study->invited()->syncWithoutDetaching($user->id);

        return response()->json(['user' => $user, 'message' => __('User was invited!')], 200);
    }

    public function removeFromStudy(Request $request)
    {
        $user_is_not_invited = ! in_array(Study::find($request->input('study'))->id, auth()->user()->invites()->pluck('study_id')->toArray());
        if ($user_is_not_invited && ! auth()->user()->is(Study::find($request->input('study'))->creator())) {
            return response()->json(['message' => __('Not authorized!')], 403);
        }

        $user = User::where('email', '=', $request->email)->first();
        if ($user) {
            $user->invites()->detach($request->input('study'));

            return response()->json(['message' => __('User was removed from the Study!')], 200);
        } else {
            return response()->json(['message' => __("The user doesn't exist!")], 403);
        }
    }
}
