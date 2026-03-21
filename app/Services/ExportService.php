<?php

namespace App\Services;

use App\Exports\DynamicExport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ExportService
{
    /**
     * Summary of __construct
     *
     */
    public function __construct(
        //
    ) {
        //
    }

    /**
     * Summary of exportData
     *
     */
    public function exportData($data, $headings, $filename) {
        try {
            $export = Excel::download(new DynamicExport($data, $headings), $filename);
            Log::info('Export successful: ' . $export->getFile()->getRealPath());
            Log::info('Export data details: ' . json_encode($data) . ', Headings: ' . json_encode($headings) . ', Filename: ' . $filename);

            return $export;
        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to export data: ' . $e->getMessage()], 500);
        }
    }
}
