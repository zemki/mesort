<?php

namespace App\Http\Controllers;

use App\Interview;
use App\Sorting;
use App\Study;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use PDF;

class SortingController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return Response
     *
     * @throws AuthorizationException
     */
    public function show(Interview $interview, $sorting)
    {
        $this->authorize([Sorting::class, $interview]);
        $study = Study::where('id', '=', $interview->study_id)->first();
        foreach ($interview->files as $file) {
            if (substr($file->type, strpos($file->type, '_') + strlen('_')) === $sorting) {
                $imageData = decrypt(file_get_contents($file->path));
            }
        }
        $data['interview'] = $interview;
        $data['sorting_screenshot'] = $imageData;
        $data['sorting_number'] = $sorting;
        $data['study'] = $study;
        $data['pdf'] = false;

        return view('sorting.view', $data);
    }

    /**
     * @return mixed
     *
     * @throws AuthorizationException
     */
    public function download(Interview $interview, $sorting)
    {
        $this->authorize('view', [Sorting::class, $interview]);
        $study = Study::where('id', '=', $interview->study_id)->first();
        $data['interview'] = $interview;
        foreach ($interview->files as $file) {
            if (substr($file->type, strpos($file->type, '_') + strlen('_')) === $sorting) {
                $imageData = decrypt(file_get_contents($file->path));
            }
        }
        $data['sorting_screenshot'] = $imageData;
        $data['sorting_number'] = $sorting;
        $data['study'] = $study;
        $data['pdf'] = true;
        $pdf = PDF::setPaper('a4', 'landscape')->loadView('sorting.view', $data);

        return $pdf->stream();
    }
}
