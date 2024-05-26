<?php

namespace App\Services\Report;

use App\Helpers\XMLParserHelper;
use App\Models\Report;
use App\Services\Service;

class ReportCommandService extends Service
{

    public function create($data)
    {
        return Report::create($data);
    }

    public function update($report, $data)
    {
        foreach ($data as $key => $value) {
            $report->{$key} = $value;
        }
        $report->update();
        return $report;
    }
}