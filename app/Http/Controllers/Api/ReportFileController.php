<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportFile\ReportFileCollection;
use App\Http\Resources\ReportFile\ReportFileResource;
use App\Models\ReportFile;
use App\Services\ReportFile\ReportFileCommandService;
use App\Services\ReportFile\ReportFileQueryService;
use Illuminate\Http\Request;

class ReportFileController extends Controller
{
    public function __construct(
        private readonly ReportFileCommandService $reportFileCommandService,
        private readonly ReportFileQueryService $reportFileQueryService,
    )
    {
    }

    public function get(): ReportFileCollection
    {
        return new ReportFileCollection($this->reportFileQueryService->get());
    }

    public function getById($id): ReportFileResource
    {
        return new ReportFileResource($this->reportFileQueryService->firstById($id));
    }
}
