<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Traits\ApiResponse;
use Modules\Article\app\Models\Article;
use Modules\Article\app\Models\ArticleReview;

class ArticleController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *     path="/articles/popular",
     *     path="/articles",
     *     operationId="getArticles",
     *     tags={"Articles"},
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

    /**
     * @OA\Get(
     *     path="/articles/{id}",
     *     summary="Fetch an article by id",
     *     description="Fetch an article by id",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         description="Article id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Article fetched successfully"),
     *             @OA\Property(property="data", type="object"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An error occurred")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $article = Article::with('articleTags')->with('author')->with('enrollment.course')->where('id', $id)->first();

            if (!$article) {
                return $this->errorResponse('Article not found', [], 404);
            }

            return $this->successResponse($article, 'Article fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/articles-reviews/{id}",
     *     summary="Fetch all reviews of an article",
     *     description="Fetch all reviews of an article",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         description="Article id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reviews fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Reviews fetched successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="description", type="string", example="This is a review"),
     *                     @OA\Property(property="stars", type="integer", example=5),
     *                     @OA\Property(property="created_at", type="string", example="2023-10-01T12:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", example="2023-10-01T12:00:00Z"),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An error occurred")
     *         )
     *     )
     * )
     */
    public function articleReviews($id)
    {
        try {
            $article = Article::with('reviews')->where('id', $id)->first();

            if (!$article) {
                return $this->errorResponse('Article not found', [], 404);
            }

            return $this->successResponse($article->reviews, 'Reviews fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/articles/{id}/reviews",
     *     summary="Add a review to an article",
     *     description="Add a review to an article",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         description="Article ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Comment",
     *         in="query",
     *         name="comment",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Rating",
     *         in="query",
     *         name="rating",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             minimum=1,
     *             maximum=5
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Review added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Review added successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="description", type="string", example="This is a review"),
     *                 @OA\Property(property="stars", type="integer", example=5),
     *                 @OA\Property(property="created_at", type="string", example="2023-10-01T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2023-10-01T12:00:00Z"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An error occurred")
     *         )
     *     )
     * )
     */
    public function storeReviews(Request $request, $id)
    {
        $request->validate([
            'comment' => ['required', 'max:1000'],
            'rating' => ['required', 'numeric', 'min:1', 'max:5'],
        ], [
            'comment.required' => __('The comment field is required'),
            'comment.max' => __('The comment must not be greater than 1000 characters'),
            'rating.required' => __('The rating field is required'),
            'rating.numeric' => __('The rating must be a number'),
            'rating.min' => __('The rating must be greater than or equal to 1'),
            'rating.max' => __('The rating must be less than or equal to 5'),
        ]);

        try {
            $articleReview = new ArticleReview();
            $articleReview->article_id = $id;
            $articleReview->author_id = userAuth()->id;
            $articleReview->description = $request->comment;
            $articleReview->stars = $request->rating;
            $articleReview->save();

            return $this->successResponse([], 'Review added successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/articles-tags",
     *     summary="Fetch tags associated with published articles",
     *     tags={"Articles"},
     *     @OA\Response(
     *         response=200,
     *         description="Tags fetched successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An error occurred")
     *         )
     *     )
     * )
     */
    public function articleTags()
    {
        try {
            $tags = Tag::whereHas('articles', function ($query) {
                $query->where('status', 'published');
            })->get();
            return $this->successResponse($tags, 'Tags fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/articles-popular",
     *     summary="Get popular articles",
     *     description="Fetch the top 5 popular articles based on the number of published reviews.",
     *     tags={"Articles"},
     *     @OA\Response(
     *         response=200,
     *         description="Popular articles fetched successfully",
     *         @OA\JsonContent(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An error occurred")
     *         )
     *     )
     * )
     */
    public function popularArticles()
    {
        try {
            $articles = Article::withCount(['reviews' => function ($query) {
                $query->where('status', 'published');
            }])->orderByDesc('reviews_count')->limit(5)->get();
            return $this->successResponse($articles, 'Popular articles fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }
}
