<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Exports\InterviewQsortExport;
use App\Exports\InterviewTokenExport;
use App\Files;
use App\Interview;
use App\Sorting;
use App\Study;
use App\Token;
use App\User;
use Auth;
use DB;
use Exception;
use File;
use Helper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InterviewController extends Controller
{
    /**
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|View
     */
    public function show(Interview $interview)
    {
        $this->authorize($interview);

        $study = $interview->study;
        $answers = $interview->answers;
        $sorting = $study->sortings[0];
        $tokens = $interview->tokens;
        $extreme_question = $study->questions->filter(function ($item) {
            return $item->detail == 'extremeQuestion';
        })->first();

        // Frontend-specific processing
        $columns = explode('|separator|', substr($sorting->pivot->details, strpos($sorting->pivot->details, 'qsort|') + 6));
        array_pop($columns);
        $columnValues = [];
        foreach ($columns as $column) {
            $columnData = explode('|', $column);
            $columnValues[] = $columnData[1];
        }

        $data = [
            'breadcrumb' => [
                'Home',
                'Study',
                $study->name,
                $interview->interviewed == __('Name not provided') ? 'Interview id ' . $interview->id : __('Interview of') . ' ' . $interview->interviewed,
            ],
            'interview' => $interview,
            'screenshots' => [],
            'sortingtoken' => $tokens,
            'tokens' => $study->available_tokens,
            'createdtokens' => Token::formatForEdit($interview->tokens()->where('tokens.author', '=', 0)->get()),
            'study' => $study,
            'questions' => $study->questions,
            'sorting' => $sorting,
            'author' => User::where('id', $interview->author)->first()->email ?? $interview->author,
            'columnValues' => $columnValues,
            'isQSortSorting' => $sorting->id !== 3,
        ];

        if ($sorting->id === 3 && $extreme_question !== null) {
            $data['extremequestion'] = $extreme_question;
            foreach ($answers as $answer) {
                $token_answer = json_decode($answer->getOriginal('pivot_result'));
                $tokenToChange = $tokens->filter(function ($item) use ($token_answer) {
                    return $item->id == $token_answer->token_id;
                })->first();
                $tokenToChange['answer'] = $token_answer->text;
            }
        }

        Answer::assignAnswersToQuestion($interview, $data);
        Sorting::getSortingInfo($study, $data);
        Interview::getSortingImages($interview, $data);

        return view('interview.view', $data);
    }

    /**
     * SHOW THE NEW INTERVIEW PAGE
     * gather the data and format them in a correct way for the frontend.
     *
     * @return Factory|View
     *
     * @throws AuthorizationException
     */
    public function create(Request $request)
    {
        $study = Study::where('id', '=', $request->input('study'))->with('sortings')->first();

        if (auth()->check()) {
            $this->authorize([Interview::class, $study]);
        }

        $returnQuestions = [];
        $this->formatQuestionsAndAnswers($request, $returnQuestions);

        $data = [
            'questions' => $returnQuestions,
            'study' => $study,
            'interviewed' => htmlspecialchars(
                $request->input('interviewed') ?: 'Name not provided'
            ),
            'sorting' => $study->sortings,
            'tokens' => $study->available_tokens->toArray(),
            'presetimages' => Helper::getPresetImages(),
            'classifiers' => Helper::getClassifiers(),
            'gotos' => $request->input('gotos') ? 'true' : 'false',
        ];

        $this->GetTokensForInterview($data);

        $presetsPath = 'presets';
        $appPath = storage_path('app/');

        foreach ($study->available_tokens as &$token) {
            $path = $token['image_path'];
            if (strpos($path, $presetsPath) !== false) {
                $token['image_path'] = mb_convert_encoding($path, 'HTML-ENTITIES', 'UTF-8');
            } else {
                $token['image_path'] = decrypt(file_get_contents($appPath . $path));
            }
        }

        return view('interview.create', $data);
    }

    private function formatQuestionsAndAnswers(Request $request, &$returnQuestions): void
    {
        $studyId = $request->input('study');
        $questions = DB::table('questions')
            ->select('questions.id as qid', 'questions.question as q', 'questions.detail as type')
            ->distinct()
            ->where('study_id', $studyId)
            ->leftJoin('answers', 'questions.id', '=', 'answers.question_id')
            ->groupBy(['qid', 'q', 'type'])
            ->selectRaw('GROUP_CONCAT(answer SEPARATOR ",") as answer')
            ->selectRaw('GROUP_CONCAT(answers.id SEPARATOR ",") as answerids')
            ->get();
        $returnQuestions['presort'] = $returnQuestions['postsort'] = $returnQuestions['extremeQuestion'] = [];
        foreach ($questions as $question) {
            $question->answer = json_decode('[' . $question->answer . ']');
            $question->answer['ids'] = explode(',', $question->answerids);
            if ($question->type === 'presort') {
                array_push($returnQuestions['presort'], $question);
            }
            if (Str::contains($question->type, 'postsort')) {
                array_push($returnQuestions['postsort'], $question);
            }
            if ($question->type === 'extremeQuestion') {
                array_push($returnQuestions['extremeQuestion'], $question);
            }
        }
    }

    private function GetTokensForInterview(&$data): void
    {
        $tokens = &$data['tokens'];
        $presetsPath = 'presets';
        $appPath = storage_path('app/');

        foreach ($tokens as &$token) {
            $path = $token['image_path'];
            if (strpos($path, $presetsPath) !== false) {
                $token['image_path'] = mb_convert_encoding($path, 'HTML-ENTITIES', 'UTF-8');
            } else {
                $token['image_path'] = decrypt(file_get_contents($appPath . $path));
            }
        }
    }

    public function store(Request $request)
    {
        $study = Study::findOrFail($request->input('study'));
        $author = auth()->check() ? Auth::user()->id : 'From public url.';
        $interview = Interview::create([
            'study_id' => $study->id,
            'author' => $author,
            'interviewed' => $request->input('interviewed'),
            'start' => $request->input('time_start'),
            'end' => $request->input('time_end'),
        ]);
        Files::storeSortingScreenshot($request, $study, $interview->id, $name);
        $this->SaveTokenValues($request, $interview);
        Answer::saveResultQuestions($request, $interview);
        if ($author !== 'From public url.') {
            auth()->user()->addAction('Interview Created', $request->url(), 'user created interview for study' . $study->name);
        }

        return response()->json('Interview Saved!', 200);
    }

    /**
     * Save token values for an interview. It loops through the sorting data in the request and processes the token values, such as position, percentage position, and classifiers. The processed data is then attached to the interview with the token id and relevant information.
     */
    private function SaveTokenValues(Request $request, Interview $interview): void
    {
        $sorting = $request->input('sorting');
        $data = [];
        foreach ($sorting as $sort) {
            foreach ($sort['tokens'] as $token) {
                $tokenValutation = $token['valutation'];
                $tokenValutation['position'] = $token['position'];
                if (isset($token['percentagePosition'])) {
                    $tokenValutation['percentagePosition'] = $token['percentagePosition'];
                }
                $tokenValutation['classifiers'] = array_map(function ($classifier) {
                    unset($classifier['base64']);

                    return $classifier;
                }, $tokenValutation['classifiers']);

                $data[] = [
                    'interview_id' => $interview->id,
                    'token_id' => $token['id'],
                    'sorting_id' => 1,
                    'valutation' => json_encode($tokenValutation),
                ];
            }
        }

        $interview->tokens()->attach($data);
    }

    /**
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function destroy(Interview $interview, Request $request)
    {
        if (auth()->user()->notOwnerNorInvited($interview->study)) {
            return response()->json(__('You are not authorized to delete this interview.'), 403);
        }
        $id = $interview->id;
        $interview->files()->delete();
        $interview->answers()->detach();
        $interview->answers()->delete();
        $interview->tokens()->detach();
        $interview->tokens()->delete();
        $interview->delete();
        File::delete($interview->sorting_screenshot);
        $interview->delete();
        auth()->user()->addAction('delete interview', $request->url(), 'user deleted interview with id ' . $id);

        return response()->json('interview deleted', 200);
    }

    /**
     * @return Response|BinaryFileResponse
     */
    public function export(Interview $interview)
    {
        if (auth()->user()->notOwnerNorInvited($interview->study)) {
            abort(403, 'You are not authorized to download these data.');
        }
        $headings = $this->getHeadings($interview->study);
        if ($interview->study->sortings[0]->id === 3) {
            return (new InterviewQsortExport($interview->id, $headings))->download($interview->interviewed . ' tokens.xlsx');
        } else {
            return (new InterviewTokenExport($interview->id, $headings, $interview->study->sortings[0]->id))->download($interview->interviewed . ' tokens.xlsx');
        }
    }

    public function getHeadings($study)
    {
        if ($study->sortings[0]->id === 2) {
            return ['section number', 'section name'];
        } elseif ($study->sortings[0]->id === 3) {
            return ['Reason for Placement'];
        } else {
            return [];
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function done()
    {
        return view('interview.done');
    }
}
