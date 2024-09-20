<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InterviewTokens extends Model
{
    /**
     * @var string
     */
    protected $table = 'interview_tokens';

    /**
     * @var array
     */
    protected $casts = [
        'valuation' => 'array',
    ];

    public function getValutationAttribute($value)
    {
        return json_decode($value);
    }

    // Relations

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }
}
