<?php

namespace App\Exports;

use App\Study;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AllInterviewTokenExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    private $id;

    private $head;

    private $sorting;

    private $columnValues;

    /**
     * Constructor to initialize export parameters.
     *
     * @param  int  $id
     * @param  array  $headings
     * @param  int  $sorting
     */
    public function __construct($id, $headings, $sorting)
    {
        $this->id = $id;
        $this->head = $headings;
        $this->sorting = $sorting;
        $this->columnValues = null;
    }

    /**
     * Define the column headings for the export.
     */
    public function headings(): array
    {
        $columnNames = ['token id', 'token_name', 'interview id', 'interviewee'];

        if ($this->sorting == 1 || $this->sorting == 2) {
            array_push($columnNames, 'circle', 'distance from center (pixels)', 'sorting number', 'position (pixels)', '% percentage position', 'classifiers');
        } elseif ($this->sorting == 3) {
            array_push($columnNames, 'position column/row', 'position base', 'token description');
            $this->initializeColumnValues();
        } else {
            array_push($columnNames, 'position column/row', 'token description');
        }

        return array_merge($columnNames, $this->head);
    }

    /**
     * Map each row of the export.
     *
     * @param  mixed  $study
     */
    public function map($study): array
    {
        $printArray = [];

        // Eager load interviews with tokens (no need to eager load 'valutation' directly as it's in the pivot)
        $study->load(['interviews.tokens']);

        foreach ($study->interviews as $interview) {
            foreach ($interview->tokens as $token) {
                $token->pivot->valutation = json_decode($token->pivot->valutation);

                if ($this->invalidData($token) && $this->sorting != 3) {
                    continue;
                }

                $toPrint = [];

                // Access valutation from the pivot table
                $tokenValutation = $token->pivot->valutation ?? null;

                // Format token values for export, pass the valutation and interviewee name
                $this->formatTokenValuesForExport($token, $toPrint, $interview, $tokenValutation);

                $printArray[] = $toPrint;
            }
        }

        return $printArray;
    }

    /**
     * @return bool
     *              Checks if the token data is valid for export.
     */
    private function invalidData($token): bool
    {
        return empty($token->pivot->valutation->position) || ! is_object($token->pivot->valutation->position);
    }

    /**
     * Initialize column values for sorting.
     */
    private function initializeColumnValues(): void
    {
        $study = Study::find($this->id);
        $columns = explode('|separator|', substr($study->sortings[0]->pivot->details, strpos($study->sortings[0]->pivot->details, 'qsort|') + 6));
        array_pop($columns);

        $this->columnValues = array_map(function ($value) {
            return explode('|', $value)[1];
        }, $columns);
    }

    /**
     * Format token values for export based on sorting type.
     *
     * @param  mixed  $token
     * @param  array  &$toPrint
     * @param  string  $interviewee
     */
    private function formatTokenValuesForExport($token, &$toPrint, $interview): void
    {

        $toPrint['token id'] = $token->id;
        $toPrint['token_name'] = $token->name;
        $toPrint['interview id'] = $interview->id;
        $toPrint['interviewee'] = $interview->interviewed;

        if ($interview->study->sortings[0]->id === 3) {
            $position = is_object($token->pivot->valutation->position) ? $token->pivot->valutation->position->x . ', ' . $token->pivot->valutation->position->y : $token->pivot->valutation->position;
            $toPrint['position column/row'] = $position;
            $toPrint['position base'] = ($this->columnValues[$position[0] - 1] ?? 'N/A') === '--empty--' ? 'N/A' : ($this->columnValues[$position[0] - 1] ?? 'N/A');
            $toPrint['token description'] = json_decode($token->properties)->description ?? '';
            $this->addReasonForPlacement($token, $toPrint, $interview);
        } else {
            $toPrint['circle'] = $token->pivot->valutation->circle;
            $toPrint['distance from center (pixels)'] = $token->pivot->valutation->distance;
            $toPrint['sorting number'] = $token->pivot->valutation->sorting;
            $toPrint['position (pixels)'] = $this->formatPosition($token);
            $toPrint['% percentage position'] = $this->formatPercentagePosition($token);
            $toPrint['classifiers'] = $this->formatClassifiers($token);
        }

        if ($interview->study->sortings[0]->id === 2) {
            $tempValuesArray['section number'] = strval($token->pivot->valutation->section);
            // Check if sectionName exists, otherwise set a default value
            $tempValuesArray['section name'] = isset($token->pivot->valutation->sectionName) ? $token->pivot->valutation->sectionName : 'section not found';
        }

    }

    /**
     * Format position for export.
     *
     * @param  mixed  $token
     */
    private function formatPosition($token): string
    {
        return is_object($token->pivot->valutation->position) ? 'x: ' . $token->pivot->valutation->position->x . ', y:' . $token->pivot->valutation->position->y : $token->pivot->valutation->position;
    }

    /**
     * Format percentage position for export.
     *
     * @param  mixed  $token
     */
    private function formatPercentagePosition($token): string
    {
        if (isset($token->pivot->valutation->percentagePosition)) {
            return 'x: ' . $token->pivot->valutation->percentagePosition->x . ', y:' . $token->pivot->valutation->percentagePosition->y;
        }

        return '';
    }

    /**
     * Format classifiers for export.
     *
     * @param  mixed  $token
     */
    private function formatClassifiers($token): string
    {
        if (is_array($token->pivot->valutation->classifiers) && ! empty($token->pivot->valutation->classifiers)) {
            return implode(' - ', array_column($token->pivot->valutation->classifiers, 'basename'));
        }

        return '';
    }

    /**
     * Add reason for placement in Q-Sort studies.
     *
     * @param  mixed  $token
     * @param  array  &$toPrint
     */
    private function addReasonForPlacement($token, &$toPrint, $interview): void
    {
        $tokenToCheck = $token;
        $hasExtremeQuestion = $interview->study->questions->where('detail', 'extremeQuestion')->first();

        $toPrint['Reason for Placement'] = '';
        if ($hasExtremeQuestion && $interview->answers) {
            foreach ($interview->answers as $answer) {
                $token_answer = json_decode($answer->getOriginal('pivot_result'));
                if ($tokenToCheck->id === $token_answer->token_id) {
                    $toPrint['Reason for Placement'] = $token_answer->text;
                }
            }
        }
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return Study::where('id', $this->id)->get();
    }
}
