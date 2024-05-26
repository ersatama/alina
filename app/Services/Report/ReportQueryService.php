<?php

namespace App\Services\Report;

use App\Models\Report;
use App\Services\Service;

class ReportQueryService extends Service
{
    public function __construct()
    {
    }

    public function get()
    {
        return Report::get();
    }

    public function getByReportFileId($reportFileId)
    {
        return Report::where('report_file_id', $reportFileId)->get();
    }

    public function firstById($id)
    {
        return Report::where('id', $id)->first();
    }
}