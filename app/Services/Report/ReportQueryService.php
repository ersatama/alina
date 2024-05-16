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
}