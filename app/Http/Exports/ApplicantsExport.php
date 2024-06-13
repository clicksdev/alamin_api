<?php

namespace App\Http\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Builder;

class ApplicantsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Builder $query;

    /**
     * ApplicantsExport constructor.
     *
     * @param Builder $query
     */
    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->query;
    }

    /**
     * @param mixed $applicant
     * @return array
     */
    public function map($applicant): array
    {
        // Customize this section to map desired model attributes to Excel columns
        return [
            $applicant->email,
    ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Customize this section to define the exact column headings you want
        return [
            'Email', // Updated heading for created_at time
        ];
    }
}
