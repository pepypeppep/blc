<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      title="BLC API Docs",
 *      version="1.0.0",
 *      description="BLC Swagger OpenAPI documentation",
 *      @OA\Contact(
 *         email="mukelele@mukelele.com",
 *         name="BLC Swagger API"
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
 * @OA\Tag(
 *     name="Courses",
 *     description="Courses API Endpoints"
 * )
 *
 * @OA\Tag(
 *     name="Lessons",
 *     description="Lessons API Endpoints"
 * )
 *
 * @OA\Tag(
 *     name="Pendidikan Lanjutan",
 *     description="Pendidikan Lanjutan API Endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Article",
 *     description="Pengetahuan API Endpoints"
 * )
 *
 * */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
