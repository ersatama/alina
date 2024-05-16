<?php

namespace App\Http\Controllers\Api;

use App\DTO\ReportCreateDTO;
use App\Helpers\XMLParserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\ImportRequest;
use App\Http\Requests\Report\UpdateRequest;
use App\Http\Resources\Report\ReportCollection;
use App\Http\Resources\Report\ReportResource;
use App\Services\Report\ReportCommandService;
use App\Services\Report\ReportQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ReportController extends Controller
{
    public function __construct(
        private readonly ReportQueryService $reportQueryService,
        private readonly ReportCommandService $reportCommandService,
        protected readonly XMLParserHelper $XMLParserHelper
    )
    {

    }

    /**
     * @throws ValidationException
     */
    public function import(ImportRequest $importRequest): JsonResponse
    {
        $data = $this->XMLParserHelper->parse($importRequest->checked());
        $report = $this->reportCommandService->create(new ReportCreateDTO($data));
        return response()->json([
            'data' => new ReportResource($report)
        ], ResponseAlias::HTTP_CREATED);
    }

    public function get(): JsonResponse
    {
        $reports = $this->reportQueryService->get();
        return response()->json([
            'data' => new ReportCollection($reports)
        ], ResponseAlias::HTTP_OK);
    }

    public function update($id, UpdateRequest $updateRequest)
    {
        $report = $this->reportQueryService->firstById($id);
        if (!$report) {
            return response()->json([
                'message' => 'not found'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
    }
}
