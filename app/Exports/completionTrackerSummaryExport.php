<?php

namespace App\Exports;

use App\assessment;
use App\evaluation;
use App\evaluationCompetency;
use App\group;
use App\division;
use App\role;
use App\employee_data;
use App\competencyType;
use App\assessmentType;
use App\employeeRelationship;
use App\relationship;
use App\listOfCompetenciesPerRole;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;


class completionTrackerSummaryExport implements FromArray, ShouldAutoSize, WithTitle, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $tracker;

    public function __construct(array $tracker)
    {
        $this->tracker = $tracker;
    }
    

    public function array(): array
    {
        return $this->tracker;
    }

    public function title(): string
    {
        return 'Completion Tracker Summary';
    }

    public function headings(): array
    {
        return [
            'Role',
            'Assessment Type',
            'Target Employees',
            'Actual Assessed',
            'Completion Rate (%)',
        ];
    }
    
}
