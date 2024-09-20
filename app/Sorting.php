<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sorting extends Model
{
    /**
     * @param  $study
     *                save the type of the sorting when saving the study
     */
    public static function store($request, $study)
    {
        if (! $request->has('details')) {
            $circles = '';
            $classifiers = '';
            $sections = '';
            $sectionNames = '';
            $sectionCenter = '';
            $qsortsections = '';
            $study->sortings()->detach();
            // insert in details something according to the sorting
            if ($request->input('sorting.id') == 1) {
                $circles = 'circles|' . $request->input('sorting.numberofcircles');
                $description = '||description|' . $request->input('sorting.description');
                $classifiers = $request->input('sorting.classifier') && $request->input('sorting.classifier')['name'] != 'none' ? '||classifier|' . $request->input('sorting.classifier')['name'] : '';
            } elseif ($request->input('sorting.id') == 2) {
                $circles = 'circles|' . $request->input('sorting.numberofcircles');
                $description = '||description|' . $request->input('sorting.description');
                $classifiers = $request->input('sorting.classifier') && $request->input('sorting.classifier')['name'] != 'none' ? '||classifier|' . $request->input('sorting.classifier')['name'] : '';
                $sections = '||divisions|' . $request->input('sorting.sectionNumber');
                $sectionNames = '||names|';
                foreach ($request->input('sorting.sections') as $section) {
                    $sectionNames .= $section['name'] . ';color:' . ($section['color'] === null ? 'casual' : $section['color']) . '|';
                }
                $sectionCenter = '|center|' . $request->input('sorting.centerLabel') . '||';
            } elseif ($request->input('sorting.id') == 3) {
                $description = '||description|' . $request->input('sorting.description');
                $qsortsections = '||qsort|';
                foreach ($request->input('sorting.qsort') as $qsort) {
                    foreach ($qsort as &$val) {
                        if ($val === '' || $val === false || $val === null) {
                            $val = '--empty--';
                        }
                    }
                    $qsortsections .= implode('|', array_reverse($qsort)) . '|separator|';
                }

                if ($request->input('sorting.qsortshownumbers')) {
                    $qsortsections .= 'yesnumbersbelowqsort';
                } else {
                    $qsortsections .= 'nonumbersbelowqsort';
                }
            }
            $study->sortings()->attach($request->get('sorting')['id'], ['details' => $circles . $description . $classifiers . $sections . $sectionNames . $sectionCenter . $qsortsections]);
        } else {
            $study->sortings()->attach($request->get('sortingid'), ['details' => $request->get('details')]);
        }
    }

    public static function getSortingInfo($study, &$data): void
    {
        $pieces = explode('||', $study->sortings[0]->pivot->details);
        $circles = strstr($pieces[0], '|');
        $circles = substr($circles, 1);
        $data['circles'] = $circles;
    }

    // Relations

    public function studies()
    {
        return $this->belongsToMany(Study::class, 'study_sorting')->withTimestamps();
    }
}
