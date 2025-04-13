<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Modules\Article\app\Models\Article;

class ArticleController extends Controller
{
    use ApiResponse;

    /**
         * @OA\Get(
         *     path="/articles",
         *     operationId="getArticles",
         *     tags={"Article"},
         *     summary="Get all articles",
         *     description="Get all articles",
         *     @OA\Parameter(
         *         name="page",
         *         in="query",
         *         required=false,
         *         description="Page number for pagination",
         *         @OA\Schema(
         *             type="integer",
         *             default=1
         *         )
         *     ),
         *     @OA\Parameter(
         *         name="keyword",
         *         in="query",
         *         required=false,
         *         description="Keyword to search for articles",
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *         name="status",
         *         in="query",
         *         required=false,
         *         description="Status of the article",
         *         @OA\Schema(
         *             type="string",
         *             enum={"draft", "published", "rejected"}
         *         )
         *     ),
         *     @OA\Parameter(
         *         name="category",
         *         in="query",
         *         required=false,
         *         description="Category of the article",
         *         @OA\Schema(
         *             type="string",
         *             enum={"blog", "document", "video"}
         *         )
         *     ),
         *     @OA\Parameter(
         *         name="visibility",
         *         in="query",
         *         required=false,
         *         description="Visibility of the article",
         *         @OA\Schema(
         *             type="string",
         *             enum={"public", "internal"}
         *         )
         *     ),
         *     @OA\Parameter(
         *         name="user_id",
         *         in="query",
         *         required=false,
         *         description="User ID of the article author",
         *         @OA\Schema(
         *             type="integer"
         *         )
         *     ),
         *     @OA\Parameter(
         *         name="tags",
         *         in="query",
         *         required=false,
         *         description="Tags of the article",
         *         @OA\Schema(
         *             type="array",
         *             @OA\Items(
         *                 type="integer"
         *             )
         *         )
         *     ),
         *     @OA\Parameter(
         *         name="limit",
         *         in="query",
         *         required=false,
         *         description="Limit the number of articles",
         *         @OA\Schema(
         *             type="integer"
         *         )
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Successful response",
         *         @OA\JsonContent(
         *             @OA\Property(
         *                 property="status",
         *                 type="boolean",
         *                 example=true
         *             ),
         *             @OA\Property(
         *                 property="message",
         *                 type="string",
         *                 example="Articles fetched successfully"
         *             ),
         *             @OA\Property(
         *                 property="data",
         *                 type="array",
         *                 @OA\Items(
         *                     type="object",
         *                     @OA\Property(
         *                         property="id",
         *                         type="integer",
         *                         example=1
         *                     ),
         *                     @OA\Property(
         *                         property="title",
         *                         type="string",
         *                         example="Article Title"
         *                     ),
         *                     @OA\Property(
         *                         property="content",
         *                         type="string",
         *                         example="Article content goes here"
         *                     ),
         *                     @OA\Property(
         *                         property="description",
         *                         type="string",
         *                         example="Article description goes here"
         *                     ),
         *                     @OA\Property(
         *                         property="status",
         *                         type="string",
         *                         example="draft"
         *                     ),
         *                     @OA\Property(
         *                         property="visibility",
         *                         type="string",
         *                         example="public"
         *                     ),
         *                     @OA\Property(
         *                         property="created_at",
         *                         type="string",
         *                         format="date-time",
         *                         example="2023-10-01T12:00:00Z"
         *                     ),
         *                     @OA\Property(
         *                         property="updated_at",
         *                         type="string",
         *                         format="date-time",                         
         *                         example="2023-10-01T12:00:00Z"
         *                     ),
         *                     @OA\Property(
         *                         property="author",
         *                         type="object",
         *                         @OA\Property(
         *                             property="id",                             
         *                             type="integer",                             
         *                             example=1
         *                         ),                         
         *                         @OA\Property(
         *                             property="name",
         *                             type="string",
         *                             example="Author Name"
         *                         ),
         *                         @OA\Property(
         *                             property="email",
         *                             type="string",                             
         *                             example="6sYHt@example.com"
         *                         ),                         
         *                         @OA\Property(
         *                             property="created_at",
         *                             type="string",
         *                             format="date-time",
         *                             example="2023-10-01T12:00:00Z"
         *                         ),
         *                         @OA\Property(
         *                             property="updated_at",
         *                             type="string",
         *                             format="date-time",
         *                             example="2023-10-01T12:00:00Z"
         *                         )
         *                     ),
         *                     @OA\Property(
         *                         property="tags",
         *                         type="array",
         *                         @OA\Items(
         *                             type="object",
         *                             @OA\Property(
         *                                 property="id",
         *                                 type="integer",
         *                                 example=1
         *                             ),                             
         *                             @OA\Property(
         *                                 property="name",
         *                                 type="string",
         *                                 example="Tag Name"
         *                             ),                             
         *                             @OA\Property(
         *                                 property="created_at",
         *                                 type="string",
         *                                 format="date-time",
         *                                 example="2023-10-01T12:00:00Z"
         *                             ),                             
         *                             @OA\Property(
         *                                 property="updated_at",
         *                                 type="string",
         *                                 format="date-time",
         *                                 example="2023-10-01T12:00:00Z"
         *                             )
         *                         )
         *                     )
         *                 )
         *             )
         *         )
         *     ),
         *     @OA\Response(
         *         response=500,
         *         description="Internal server error",
         *         @OA\JsonContent(
         *             @OA\Property(
         *                 property="status",
         *                 type="boolean",
         *                 example=false
         *             ),
         *             @OA\Property(
         *                 property="message",
         *                 type="string",
         *                 example="Internal server error"
         *             ),
         *             @OA\Property(
         *                 property="data",
         *                 type="object",
         *                 @OA\Property(
         *                     property="error",
         *                     type="string",
         *                     example="Error message goes here"
         *                 )
         *             )
         *         )
         *     )
         * )
         */
    public function index(Request $request)
    {
        try {
            $articles = Article::with('articleTags')->with('author')->with('enrollment.course');

            if ($request->keyword) {
                $articles = $articles->where(function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->keyword . '%')
                        ->orWhere('content', 'like', '%' . $request->keyword . '%')
                        ->orWhere('description', 'like', '%' . $request->keyword . '%');
                });
            }

            if ($request->status) {
                $articles = $articles->where('status', $request->status);
            }

            if ($request->category) {
                $articles = $articles->where('category', $request->category);
            }

            if ($request->visibility) {
                $articles = $articles->where('visibility', $request->visibility);
            }

            if ($request->user_id) {
                $articles = $articles->where('author_id', $request->user_id);
            }

            if ($request->tags) {
                $articles = $articles->whereHas('articleTags', function ($query) use ($request) {
                    $query->whereIn('tag_id', $request->tags);
                });
            }

            if ($request->limit) {
                $articles = $articles->limit($request->limit);
            }

            $articles = $articles->paginate(10);
            return $this->successResponse($articles, 'Articles fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }
}
