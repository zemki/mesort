<?php

namespace App\Exports;

use App\Interview;
use App\InterviewTokens;
use App\Token;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InterviewQsortExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    private $id;

    private $headings;

    private $columnValues;

    /**
     * InterviewQsortExport constructor.
     *
     * @param  int  $id
     * @param  array  $headings
     */
    public function __construct($id, $headings = [])
    {
        $this->id = $id;
        $this->headings = $headings;
        $this->columnValues = $this->getColumnValues();
    }

    /**
     * Generate the headings for the Excel export.
     */
    public function headings(): array
    {
        // Initial columns
        $columnNames = [
            'token_id',
            'token_name',
            'position column/row',
            'position base',
            'token description',
            'Interviewee',
        ];

        // Add dynamic headings
        return array_merge($columnNames, $this->headings);
    }

    /**
     * Map the token data to the respective columns.
     */
    public function map($token): array
    {
        if ($this->invalidData($token)) {
            return [];
        }

        $position = $token->valutation->position;

        $tempValuesArray = [
            'token_id' => $token->token_id,
            'token_name' => Token::find($token->token_id)->name,
            'position column/row' => $position,
            'position base' => ($this->columnValues[$position[0] - 1] ?? 'N/A') === '--empty--' ? 'N/A' : ($this->columnValues[$position[0] - 1] ?? 'N/A'),
            'token description' => json_decode($token->properties)->description ?? '',
            'interviewee name' => $token->interview->interviewed,
            'Reason for Placement' => $this->getReasonForPlacement($token),
        ];

        return $tempValuesArray;
    }

    /**
     * Check if the token data is invalid.
     */
    private function invalidData($token): bool
    {
        return ! is_object($token->valutation->position)
            && $token->valutation->position == 0
            && $token->valutation->distance == 0
            && $token->valutation->circle == 0
            && $token->interview->study->sortings[0]->id === 2;
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

    /**
     * Retrieve the study associated with the interview.
     */
    private function study()
    {
        return Interview::findOrFail($this->id)->study;
    }

    /**
     * Extract and prepare column values for Q-Sort positions.
     */
    private function getColumnValues(): array
    {
        $columns = explode('|separator|', substr($this->study()->sortings[0]->pivot->details, strpos($this->study()->sortings[0]->pivot->details, 'qsort|') + 6));
        array_pop($columns);

        $baseArray = [];
        foreach ($columns as $value) {
            $column = explode('|', $value);
            $baseArray[] = $column[1] ?? 'N/A';  // Fallback to 'N/A' if not found
        }

        return $baseArray;
    }

    /**
     * Retrieve the reason for placement if available.
     */
    private function getReasonForPlacement($token): string
    {
        $interview = Interview::find($this->id);
        $hasExtremeQuestion = $interview->study->questions->firstWhere('detail', 'extremeQuestion');

        if ($hasExtremeQuestion && $interview->answers) {
            foreach ($interview->answers as $answer) {
                $tokenAnswer = json_decode($answer->getOriginal('pivot_result'));
                if ($token->token_id === $tokenAnswer->token_id) {
                    return $tokenAnswer->text;
                }
            }
        }

        return '';  // Default to an empty string if no reason is found
    }
}
