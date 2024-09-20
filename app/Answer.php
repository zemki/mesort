<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Answer extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['question_id', 'answer'];

    /**
     * @var array
     */
    protected $casts = [
        'answer' => 'array',
    ];

    public static function saveResultQuestions(Request $request, Interview $interview): void
    {
        foreach ($request->input('results_questions') as $key => $value) {
            if (is_numeric($key)) {
                $answer = Answer::where('id', '=', $key)->first();
                $question = Question::where('id', '=', $answer->question_id)->first();
                $answerType = $answer->answer['type'];
                if ($answerType === 'open' || $answerType === 'scale') {
                    $interview->answers()->attach($key, ['result' => $value, 'question_id' => $question->id]);
                }
            }
            if ($key === 'multi' || $key === 'onechoice') {
                foreach ($value as $answer) {
                    $answer = Answer::where('id', '=', $answer['id'])->first();
                    $question = Question::where('id', '=', $answer->question_id)->first();
                    $interview->answers()->attach($answer['id'], ['question_id' => $question->id]);
                }
            }

            if ($key === 'extremes') {
                // / save the answer as "extremes", save the token id
                // / DB: there's only 1 question marked as extreme as well as 1 answer.
                // /  Here we save in interview_answer multiple entries since the same question has different answers for the same type of answer.
                foreach ($value as $answer) {
                    $result = ['text' => $answer['text'], 'token_id' => $answer['token_id']];
                    $interview->answers()->attach($answer['id'], ['question_id' => $answer['question_id'], 'result' => json_encode($result)]);
                }
            }
        }
    }

    public static function assignAnswersToQuestion(Interview $interview, $data): void
    {
        foreach ($data['questions'] as $question) {
            $question['available_answers'] = $question->availableAnswers();
            $question['answers'] = $question->answers()->having('pivot_interview_id', '=', $interview->id)->get();
        }
    }

    /**
     * @return array
     */
    public function getAnswerAttribute($value)
    {
        return is_array($value) ? $value : (array) json_decode($value);
    }

    // Relations

    public function question()
    {
        return $this->hasOne(Question::class);
    }

    public function interviews()
    {
        return $this->belongsToMany(Interview::class, 'interview_answers')->withPivot('result')->withTimestamps();
    }
}
