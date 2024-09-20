<?php

namespace App;

use File;
use Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;

class Files extends Model
{
    /**
     * @var string
     */
    protected $table = 'files_interview';

    /**
     * @var array
     */
    protected $fillable = [
        'type', 'path', 'size', 'interview_id',
    ];

    public static function occupiedStorage(): mixed
    {
        return Files::all()->sum('size');
    }

    public static function storeSortingScreenshot(Request $request, Study $study, $interviewid, &$name): void
    {
        $arrayOfSorting = $request->input('sorting');
        $i = 0;
        foreach ($arrayOfSorting as $sorting) {
            $name = 'interview_' . $request->input('time_start') . '_sorting' . $i;
            $studyPath = storage_path('app/study' . $study->id . '/screenshots/');
            $extension = Helper::extension($sorting['sortingScreenshot']);
            $notEncryptedContent = $studyPath . $name . '.' . $extension;
            File::isDirectory($studyPath) or File::makeDirectory($studyPath, 0775, true, true);
            // make an image resource
            Image::make($sorting['sortingScreenshot'])->save($notEncryptedContent);
            $arr = explode(',', $sorting['sortingScreenshot'], 2);
            self::SaveEncryptedFile($study, $name, $arr, $notEncryptedContent, $encryptedPath);
            // add to files_interview
            self::SaveFileDbRecord($interviewid, $name, $i, $studyPath, $encryptedPath);
            $i++;
        }
    }

    private static function SaveEncryptedFile(Study $study, &$name, array $arr, string $notEncryptedContent, &$encryptedPath): void
    {
        $base64firstpart = $arr[0];
        $encryptedContent = encrypt($base64firstpart . ',' . base64_encode(file_get_contents($notEncryptedContent)));
        $encryptedPath = 'study' . $study->id . '/screenshots/' . $name . '.mscreenshot';
        // Store the encrypted Content
        Storage::put($encryptedPath, $encryptedContent);
        File::delete($notEncryptedContent);
    }

    private static function SaveFileDbRecord($interviewid, &$name, int $i, string $studyPath, string $encryptedPath): void
    {
        $file_interview = new Files();
        $file_interview->type = 'sorting_' . $i;
        $file_interview->path = $studyPath . $name . '.mscreenshot';
        if (is_file(storage_path('app/' . $encryptedPath))) {
            $file_interview->size = File::size(storage_path('app/' . $encryptedPath));
        } else {
            $file_interview->size = 0;
        }
        $file_interview->interview_id = $interviewid;
        $file_interview->save();
    }

    // Relations

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }
}
