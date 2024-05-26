<?php

namespace App\Services\ReportFile;

use App\Models\ReportFile;
use App\Services\Service;

class ReportFileCommandService extends Service
{
    public function create($data)
    {
        return ReportFile::create($data);
    }
}