<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Report\ReportResource;
use App\Http\Resources\ReportFile\ReportFileCollection;
use App\Http\Resources\ReportFile\ReportFileResource;
use App\Services\ReportFile\ReportFileQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ReportFileController extends Controller
{
    public function __construct(
        private readonly ReportFileQueryService $reportFileQueryService,
    )
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reportFile/get",
     *     operationId="reportFile Get",
     *     summary="ReportFile get",
     *     tags={"Report File"},
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
     *                      }
     *                 )
     *             )
     *         }
     *     ),
     * )
     */
    public function get(): ReportFileCollection
    {
        return new ReportFileCollection($this->reportFileQueryService->get());
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reportFile/getById/{id}",
     *     operationId="reportFile getById",
     *     summary="Report get by report id",
     *     tags={"Report File"},
     *     @OA\Parameter(
     *       name="id",
     *       description="report id",
     *       in="path",
     *       required=true,
     *       @OA\Schema(
     *           type="integer",
     *           example=1
     *       )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *         "status": "success",
    "data": "..."
     *     }
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
    "status": "Not found",
    "message": "Report file not found"
    }
     *                 )
     *             )
     *         }
     *     ),
     * )
     */
    public function getById($id): JsonResponse|ReportFileResource
    {
        if ($reportFile = $this->reportFileQueryService->firstById($id)) {
            return response()->json([
                'status' => 'success',
                'data' => new ReportFileResource($reportFile)
            ], ResponseAlias::HTTP_OK);
        }
        return response()->json([
            'status' => 'Not found',
            'message' => 'Report file not found'
        ], ResponseAlias::HTTP_NOT_FOUND);
    }
}
