<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;
/**
 * @OA\Info(
 *     title="Alina group OQ project",
 *     version="1.0.0",
 * )
 * @OA\Server(
 *     url=local,
 *     description="Local API Server"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
