<?php

namespace App\Http\Controllers\Api;

use App\Helpers\XMLParserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\ImportRequest;
use App\Http\Requests\Report\UpdateRequest;
use App\Http\Resources\Report\ReportCollection;
use App\Http\Resources\Report\ReportResource;
use App\Http\Resources\ReportFile\ReportFileResource;
use App\Jobs\XmlLoader;
use App\Services\Report\ReportCommandService;
use App\Services\Report\ReportQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Saloon\XmlWrangler\Exceptions\XmlReaderException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;
use OpenApi\Annotations as OA;

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
     * @OA\Post(
     *     path="/api/v1/report/import",
     *     operationId="reportImport",
     *     summary="Report Import",
     *     tags={"Report"},
     *     @OA\RequestBody(
                @OA\MediaType(
                    mediaType="multipart/form-data",
                    @OA\Schema(
                    @OA\Property(
                    description="XML file",
                    property="file",
                    type="file", format="file"
                    )
                    )
                )
            ),
     *     @OA\Response(
     *         response="201",
     *         description="Created",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
    "status": "success",
    "message": "XML file uploaded successfully",
    "data": {
    "id": 18,
    "path": "http://localhost/xml/1716737712_665356b0b73df.xml",
    "created_at": "2024-05-26T15:35:12.000000Z",
    "updated_at": "2024-05-26T15:35:12.000000Z"
    }
    }
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *      response=400,
     *      description="Bad request",
     *      content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                      example={
    "status": "Error",
    "message": "Something goes wrong"
    }
     *                 )
     *             )
     *         }
     *     ),
     * )
     * @throws ValidationException|XmlReaderException
     * @throws Throwable
     */
    public function import(ImportRequest $importRequest): JsonResponse
    {
        try {
            if ($reportFile = $this->XMLParserHelper->parse($importRequest)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'XML file uploaded successfully',
                    'data' => new ReportFileResource($reportFile)
                ], ResponseAlias::HTTP_CREATED);
            }
        } catch (XmlReaderException|Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
        return response()->json([
            'status'  => 'error',
            'message' => 'Something goes wrong',
        ], ResponseAlias::HTTP_BAD_REQUEST);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/report/get",
     *     operationId="report Get",
     *     summary="Report get",
     *     tags={"Report"},
     *          *     @OA\Parameter(
     *       name="page",
     *       description="pagination",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="integer",
     *           example=1
     *       )
     *     ),
     *     @OA\Parameter(
     *        name="take",
     *        description="limit",
     *        in="query",
     *        required=false,
     *        @OA\Schema(
     *            type="integer",
     *            example=20
     *        )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
                            "data": "..."
     *                      }
     *                 )
     *             )
     *         }
     *     ),
     * )
     */
    public function get(): JsonResponse
    {
        $reports = $this->reportQueryService->get();
        return response()->json([
            'data' => new ReportCollection($reports)
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/report/getByReportFileId/{id}",
     *     operationId="report getByReportFileId",
     *     summary="Report get by report file id",
     *     tags={"Report"},
     *     @OA\Parameter(
     *       name="id",
     *       description="report file id",
     *       in="path",
     *       required=true,
     *       @OA\Schema(
     *           type="integer",
     *           example=1
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="page",
     *       description="pagination",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="integer",
     *           example=1
     *       )
     *     ),
     *     @OA\Parameter(
     *        name="take",
     *        description="limit",
     *        in="query",
     *        required=false,
     *        @OA\Schema(
     *            type="integer",
     *            example=20
     *        )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
                            "data": "..."
     *     }
     *                 )
     *             )
     *         }
     *     ),
     * )
     */
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
