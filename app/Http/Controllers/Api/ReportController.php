<?php

namespace App\Http\Controllers\Api;

use App\DTO\ReportCreateDTO;
use App\Helpers\XMLParserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\ImportRequest;
use App\Http\Requests\Report\UpdateRequest;
use App\Http\Resources\Report\ReportCollection;
use App\Http\Resources\Report\ReportResource;
use App\Jobs\XmlLoader;
use App\Services\Report\ReportCommandService;
use App\Services\Report\ReportQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Saloon\XmlWrangler\Exceptions\XmlReaderException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class ReportController extends Controller
{
    public function __construct(
        private readonly ReportQueryService $reportQueryService,
        private readonly ReportCommandService $reportCommandService,
        private readonly XMLParserHelper $XMLParserHelper
    )
    {

    }

    /**
     * @throws ValidationException|XmlReaderException
     * @throws Throwable
     */
    public function import(ImportRequest $importRequest): JsonResponse
    {
        if (!$this->XMLParserHelper->parse($importRequest)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect xml file'
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
        //$report = $this->reportCommandService->create(new ReportCreateDTO($data));
        return response()->json([
            'status' => 'success',
            'message' => 'XML file uploaded successfully'
        ], ResponseAlias::HTTP_CREATED);
    }

    public function get(): JsonResponse
    {
        $reports = $this->reportQueryService->get();
        return response()->json([
            'data' => new ReportCollection($reports)
        ], ResponseAlias::HTTP_OK);
    }

    public function getByReportFileId($reportFileId): JsonResponse
    {
        $reports = $this->reportQueryService->getByReportFileId($reportFileId);
        return response()->json([
            'data' => new ReportCollection($reports)
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * @throws ValidationException
     */
    public function update($id, UpdateRequest $updateRequest): JsonResponse
    {
        $report = $this->reportQueryService->firstById($id);
        if (!$report) {
            return response()->json([
                'message' => 'not found'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
        $report = $this->reportCommandService->update($report, $updateRequest->checked());
        return response()->json([
            'data' => new ReportResource($report)
        ], ResponseAlias::HTTP_OK);
    }
}
