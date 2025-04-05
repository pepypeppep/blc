<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      title="Laravel Swagger OpenAPI Docs",
 *      version="1.0.0",
 *      description="Laravel Swagger OpenAPI documentation",
 *      @OA\Contact(
 *         email="info@laravel-swagger.com",
 *         name="Laravel Swagger API"
 *      )
 * )
 *
 * @OA\Server(
 *     url="/api",
 *     description="API Server"
 * )
 *
 * @OA\Tag(
 *     name="Home",
 *     description="Home API Endpoints"
 * )
 *
 * */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
