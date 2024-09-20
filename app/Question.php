<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Str;

class Question extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'question', 'study_id', 'detail',
    ];

    /**
     * Extract data from the DB and format them for the Edit study page
     */
    public static function formatForEdit($study): mixed
    {
        $study->tokens = $study->available_tokens;
        $questions['presort'] = $questions['postsort'] = $questions['extremeQuestion'] = $questionIds = [];
        foreach ($study->questions as $questionToEdit) {
            if ($questionToEdit->detail === 'presort') {
                $questionToEdit['answers'] = Answer::where('question_id', $questionToEdit->id)->get()->toArray();
                array_push($questions['presort'], $questionToEdit);
            } elseif (Str::contains($questionToEdit->detail, 'postsort')) {
                if (Str::contains($questionToEdit->detail, 'showsorting')) {
                    $questionToEdit['canShowSorting'] = true;
                }
                $questionToEdit['answers'] = Answer::where('question_id', $questionToEdit->id)->get()->toArray();
                array_push($questions['postsort'], $questionToEdit);
            } elseif ($questionToEdit->detail === 'extremeQuestion') {
                $questions['extremeQuestion']['qsortextremequestion'] = $questionToEdit->question;
                $questions['extremeQuestion']['qsortaskextremes'] = true;
            }
            array_push($questionIds, $questionToEdit->id);
        }
        $questions['presort']['count'] = count($questions['presort']);
        $questions['postsort']['count'] = count($questions['postsort']);

        return $questions;
    }

    public function study(): HasOne
    {
        return $this->hasOne(Study::class);
    }

    public function answers(): BelongsToMany
    {
        return $this->belongsToMany(Answer::class, 'interview_answers')->withPivot('result', 'interview_id')->withTimestamps();
    }

    public function availableAnswers(): array
    {
        return Answer::where('question_id', '=', $this->id)->pluck('answer')->toArray();
    }

    /**
     * a specific function to store the extremes of Q-Sort
     */
    public static function storeExtremeQuestion($extremeQuestion, $study): void
    {
        $question = new Question();
        $question->question = $extremeQuestion;
        $question->detail = 'extremeQuestion';
        $question->study_id = $study->id;
        $question->save();
        $answer = new Answer();
        $answer->answer = json_decode('{"type":"extremes","answer":""}');
        $answer->question_id = $question->id;
        $answer->save();
    }
}
