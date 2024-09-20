<?php

namespace App;

use Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Image;
use Storage;

class Token extends Model
{
    protected $fillable = [
        'name', 'image_path', 'author', 'properties',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($token) {
            if ($token->studies()->exists()) {
                return false;
            }
            File::delete($token->image_path);
            $token->studies()->detach();
        });
    }

    public static function store($tokenToSave, $study, $isInterview = false): void
    {
        $imageIsPreset = self::checkIfImageIsPreset($tokenToSave);

        if ($imageIsPreset) {
            $token = self::createTokenWithPresetImage($tokenToSave, $isInterview);
        } else {
            $image = self::determineImageToSave($tokenToSave, $isInterview);
            $token = self::createTokenWithNewImage($tokenToSave, $study, $image);
        }

        $token->author = $isInterview ? 0 : Auth::user()->id;
        $token->properties = ! empty($tokenToSave['properties']) ? json_encode($tokenToSave['properties']) : '{"description":""}';
        $token->save();
        $token->studies()->sync($study->id);
    }

    private static function checkIfImageIsPreset($tokenToSave)
    {
        return ((isset($tokenToSave['ispreset']) && $tokenToSave['ispreset'])
                || (isset($tokenToSave['file']['ispreset']) && $tokenToSave['file']['ispreset']))
            || (isset($tokenToSave['image_path'])
                && strpos($tokenToSave['image_path'], 'presets') !== false);
    }

    private static function createTokenWithPresetImage($tokenToSave, $isInterview)
    {
        $token = new self();
        $token->name = $tokenToSave['name'];
        $token->image_path = $isInterview ? $tokenToSave['file'] : (isset($tokenToSave['file']) ? $tokenToSave['file'] : $tokenToSave['image_path']);

        return $token;
    }

    private static function determineImageToSave($tokenToSave, $isInterview)
    {
        if (isset($tokenToSave['base64']) || isset($tokenToSave['fileUpload']) || isset($tokenToSave['file']['base64'])) {
            return $isInterview ? $tokenToSave['base64'] : (isset($tokenToSave['fileUpload']) && $tokenToSave['fileUpload'] != [] ? $tokenToSave['fileUpload']['base64'] : $tokenToSave['base64']);
        } else {
            return config('utilities.sortingBasicIcon');
        }
    }

    private static function createTokenWithNewImage($tokenToSave, $study, $image)
    {
        $name = $tokenToSave['name'];
        $arr = explode(',', $image, 2);
        $base64FirstPart = $arr[0];
        $studyPath = storage_path('app/study' . $study->id . '/tokens/');
        \File::isDirectory($studyPath) or \File::makeDirectory($studyPath, 0775, true, true);

        if (file_exists($studyPath . $name . '.mtoken')) {
            $name = now()->timestamp . $name;
        }

        $extension = Helper::extension($image);
        $path = $studyPath . $name . '.' . $extension;
        Image::make($image)->fit(100, 100)->save($path);
        $encryptedContent = encrypt($base64FirstPart . ',' . base64_encode(file_get_contents($path)));
        $encryptedName = 'study' . $study->id . '/tokens/' . $name . '.mtoken';
        Storage::put($encryptedName, $encryptedContent);
        \File::delete($path);

        $token = new self();
        $token->name = $tokenToSave['name'];
        $token->image_path = $encryptedName;

        return $token;
    }

    public static function formatForEdit($tokens)
    {
        $tokensCount = count($tokens);
        for ($i = 0; $i < $tokensCount; $i++) {
            $tokenIsPreset = strpos($tokens[$i]['image_path'], 'presets') !== false;
            if ($tokenIsPreset) {
                $path = $tokens[$i]['image_path'];
                $tokens[$i]['image_path'] = mb_convert_encoding($path, 'HTML-ENTITIES', 'UTF-8');
            } else {
                $path = storage_path('app/' . $tokens[$i]['image_path']);
                $tokens[$i]['image_path'] = decrypt(file_get_contents($path));
            }
        }

        return $tokens;
    }

    private static function saveTokenFile(string $studyPath, $name, $image, $base64FirstPart, Study $newStudy): string
    {
        if (file_exists($studyPath . $name . '.mtoken')) {
            $name = now()->timestamp . $name;
        }
        $extension = Helper::extension($image);
        // open file a image resource
        $path = $studyPath . $name . '.' . $extension;
        Image::make($image)->fit(100, 100)->save($path);
        $encryptedContent = encrypt($base64FirstPart . ',' . base64_encode(file_get_contents($path)));
        $encryptedName = 'study' . $newStudy->id . '/tokens/' . $name . '.mtoken';
        // Store the encrypted Content
        Storage::put($encryptedName, $encryptedContent);
        \File::delete($path);

        return $encryptedName;
    }

    // Relations
    public function studies(): BelongsToMany
    {
        return $this->belongsToMany(Study::class, 'study_tokens')->withTimestamps();
    }

    public function interview()
    {
        return $this->belongsToMany(Interview::class, 'interview_tokens')->withPivot('valutation')->withTimestamps();
    }

    public function answers()
    {
        return $this->belongsToMany(Answer::class, 'interview_tokens')->withTimestamps();
    }

    public function author()
    {
        return $this->belongsTo(User::class)->withTimestamps();
    }

    public function scopeCreatedTokens($query)
    {
        return $query->where('author', 0);
    }
}
