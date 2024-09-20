<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Interview extends Model
{
    protected $guarded = ['id'];

    public static function getSortingImages(Interview $interview, &$data): void
    {
        $i = 0;
        foreach ($interview->files as $file) {
            $imageData = decrypt(file_get_contents($file->path));
            $data['screenshots'][$i] = [];
            $data['screenshots'][$i]['image'] = $imageData;
            $data['screenshots'][$i]['type'] = substr($file->type, strpos($file->type, '_') + strlen('_'));
            $i++;
        }
    }

    // Relations

    public function answers(): BelongsToMany
    {
        return $this->belongsToMany(Answer::class, 'interview_answers')->withPivot('result')->withTimestamps();
    }

    public function tokens(): BelongsToMany
    {
        return $this->belongsToMany(Token::class, 'interview_tokens')->withPivot('valutation')->withTimestamps();
    }

    public function study(): BelongsTo
    {
        return $this->belongsTo(Study::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(Files::class);
    }
}
