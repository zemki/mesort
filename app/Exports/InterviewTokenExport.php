<?php

namespace App\Exports;

use App\InterviewTokens;
use App\Token;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InterviewTokenExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    private $id;

    private $headings;

    private $sorting;

    /**
     * Constructor to initialize export with interview ID, headings, and sorting type.
     */
    public function __construct($id, $headings, $sorting)
    {
        $this->id = $id;
        $this->headings = $headings;
        $this->sorting = $sorting;
    }

    /**
     * Define the headings for the export.
     */
    public function headings(): array
    {
        $columnNames = [
            'token_id',
            'token_name',
            'interviewee',
        ];

        // Add additional columns based on the sorting type
        if (in_array($this->sorting, [1, 2])) {
            $columnNames = array_merge($columnNames, [
                'circle',
                'distance from center (pixels)',
                'sorting number',
                'position (pixels)',
                '% percentage position',
                'classifiers',
            ]);
        } else {
            $columnNames = array_merge($columnNames, [
                'position column/row',
                'token description',
            ]);
        }

        // Add any additional dynamic headings passed to the constructor
        return array_merge($columnNames, $this->headings);
    }

    /**
     * Map the data for each token to the respective columns.
     */
    public function map($token): array
    {
        // Check if valutation exists, if not, return empty or placeholder values
        if (empty($token->valutation)) {
            return [
                'token_id' => $token->token_id,
                'token_name' => Token::find($token->token_id)->name,
                'interviewee name' => $token->interview->interviewed,
                'circle' => 'N/A',
                'distance from center (pixels)' => 'N/A',
                'sorting number' => 'N/A',
                'position (pixels)' => 'N/A',
                '% percentage position' => 'N/A',
                'classifiers' => 'N/A',
            ];
        }

        // Skip invalid data
        if ($this->invalidData($token)) {
            return [];
        }

        // Proceed with usual logic if valutation exists
        $percentagePosition = $token->valutation->percentagePosition
            ? 'x: ' . $token->valutation->percentagePosition->x . ' y:' . $token->valutation->percentagePosition->y
            : '';

        $position = is_object($token->valutation->position)
            ? 'x: ' . $token->valutation->position->x . ' y:' . $token->valutation->position->y
            : $token->valutation->position;

        $classifiers = is_array($token->valutation->classifiers) && ! empty($token->valutation->classifiers)
            ? implode(' - ', array_map(fn ($classifier) => $classifier->basename, $token->valutation->classifiers))
            : '';

        $values = [
            'token_id' => $token->token_id,
            'token_name' => Token::find($token->token_id)->name,
            'interviewee name' => $token->interview->interviewed,
        ];

        if ($token->interview->study->sortings[0]->id == 3) {
            $values['position column/row'] = $position;
            $values['token description'] = json_decode($token->properties)->description ?? '';
        } else {
            $values['circle'] = $token->valutation->circle ?? 'N/A';
            $values['distance from center (pixels)'] = $token->valutation->distance ?? 'N/A';
            $values['sorting number'] = $token->valutation->sorting ?? 'N/A';
            $values['position (pixels)'] = $position;
            $values['% percentage position'] = $percentagePosition;
            $values['classifiers'] = $this->formatClassifiers($token);

            if ($token->interview->study->sortings[0]->id === 2) {
                $values['section number'] = (string) ($token->valutation->section ?? 'N/A');
                $values['section name'] = $token->valutation->sectionName ?? 'section not found';
            }
        }

        return $values;
    }

    /**
     * Check if the token data is invalid.
     */
    private function invalidData($token): bool
    {

        // If valutation is empty or exactly matches this structure, return true (invalid)
        $unsortedValuation = $token->valutation->circle === 0 &&
            $token->valutation->distance === 0 &&
            $token->valutation->sorting === 1 &&
            is_array($token->valutation->classifiers) &&
            empty($token->valutation->classifiers) &&
            $token->valutation->position === 0;

        // Check if the valutation is missing or not valid
        if (empty($token->valutation) || $unsortedValuation) {
            return true;
        }

        return ! is_object($token->valutation->position)
            && $token->valutation->position == 0
            && $token->valutation->distance == 0
            && $token->valutation->circle == 0
            && $token->interview->study->sortings[0]->id === 2;
    }

    /**
     * Format classifiers for export.
     *
     * @param  mixed  $token
     */
    private function formatClassifiers($token): string
    {
        if (is_array($token->valutation->classifiers) && ! empty($token->valutation->classifiers)) {
            return implode(' - ', array_column($token->valutation->classifiers, 'basename'));
        }

        return '';
    }

    /**
     * Retrieve the collection of tokens for the given interview.
     */
    public function collection(): Collection
    {
        return InterviewTokens::where('interview_id', $this->id)
            ->join('tokens', 'token_id', 'tokens.id')
            ->get();
    }
}
