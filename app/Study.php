<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Study extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'description', 'user_id', 'Author',
    ];

    public static function boot()
    {
        parent::boot();
        static::deleting(static function ($study) {
            $tokens = Token::join('study_tokens', 'tokens.id', '=', 'token_id')->where('study_id', '=', $study->id)->pluck('tokens.id')->toArray();
            $study->available_tokens()->detach($tokens);
            $files = Token::whereIn('id', $tokens)->pluck('image_path');
            foreach ($files as $file) {
                if (strpos($file, 'presets') === false) {
                    Storage::delete($file);
                }
            }
            foreach ($tokens as $token) {
                Token::where('id', $token)->delete();
            }
            $answers = Answer::join('questions', 'questions.id', '=', 'question_id')->where('study_id', '=', $study->id)->pluck('answers.id')->toArray();
            foreach ($study->questions as $question) {
                $question->answers()->detach();
            }
            Answer::whereIn('id', $answers)->delete();
            Question::where('study_id', '=', $study->id)->delete();
            $study->sortings()->detach();
            $study->invited()->detach();
        });
    }

    /**
     * @return User instance of the creator of the study
     */
    public function creator()
    {
        return User::find($this->user_id);
    }

    // Relations

    public function publicUrls()
    {
        return $this->hasMany(PublicInterviewUrl::class);
    }

    public function sortings()
    {
        return $this->belongsToMany(Sorting::class, 'study_sortings')->withPivot('details')->withTimestamps();
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function isEditable()
    {
        return $this->interviews()->count() == 0;
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function available_tokens()
    {
        return $this->tokens()->where('tokens.author', '<>', 0);
    }

    public function tokens()
    {
        return $this->belongsToMany(Token::class, 'study_tokens')->withTimestamps();
    }

    public function invited()
    {
        return $this->belongsToMany(User::class, 'user_studies');
    }
}
