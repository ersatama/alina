<?php

namespace App\Services\Report;

use App\Helpers\XMLParserHelper;
use App\Models\Report;
use App\Services\Service;

class ReportCommandService extends Service
{
    public function __construct(
        private readonly Report $report
    )
    {
    }

    public function create($dataDTO)
    {
        return $this->report::create($dataDTO->toArray());
    }
}