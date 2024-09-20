<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicInterviewUrl extends Model
{
    /**
     * @var string
     */
    protected $table = 'study_interview_public_url';

    public $timestamps = false;

    public static function isValid($uuid)
    {
        return PublicInterviewUrl::where('id', '=', $uuid)->whereNull('submitted_at')->first();
    }
}
