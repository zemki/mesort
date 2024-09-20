<?php

namespace App\Http\Controllers;

use App\Exports\AllInterviewTokenExport;
use App\Study;

class StudyInterviewController extends Controller
{
    public function exportall(Study $study)
    {
        if (auth()->user()->notOwnerNorInvited($study)) {
            abort(403, 'You are not authorized to download these data.');
        }

        $headings = $this->getHeadings($study);

        return (new AllInterviewTokenExport($study->id, $headings, $study->sortings[0]->id))->download('Interviews from study' . $study->name . '.xlsx');
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
}
