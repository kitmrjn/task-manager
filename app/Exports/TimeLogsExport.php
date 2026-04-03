<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TimeLogsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Date', 'Employee Name', 'Email', 'Role', 'Campaign', 'Team Leader', 
            'Time In', 'Time Out', 'Total Hours', 'Status', 'EOD Notes'
        ];
    }

    public function map($log): array
    {
        return [
            $log->log_date->format('Y-m-d'),
            $log->user->name,
            $log->user->email,
            ucwords(str_replace('_', ' ', $log->user->role)),
            $log->user->campaign->name ?? 'Unassigned',
            $log->user->teamLeader->name ?? 'None',
            $log->time_in ? $log->time_in->format('h:i A') : '--',
            $log->time_out ? $log->time_out->format('h:i A') : '--',
            $log->total_hours,
            $log->status,
            $log->eod_notes ?? 'No notes provided',
        ];
    }
}