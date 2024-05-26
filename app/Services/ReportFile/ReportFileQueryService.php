<?php

namespace App\Services\ReportFile;

use App\Models\ReportFile;
use App\Services\Service;

class ReportFileQueryService extends Service
{
    public function get()
    {
        return ReportFile::get();
    }

    public function firstById($id)
    {
        return ReportFile::where('id', $id)->first();
    }
}