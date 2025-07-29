<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Article\app\Models\Article;
use Modules\Order\app\Models\Enrollment;
use Illuminate\Support\Facades\Validator;
use Modules\Article\app\Models\ArticleReview;
use App\Http\Requests\Frontend\StudentPelatihanStoreRequest;

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
            $articles = Article::with('articleTags')
                ->with('author')
                ->withCount(['reviews as total_review'])
                ->withAvg('reviews as rating', 'stars')
                ->with('enrollment.course')
                ->where('status', Article::STATUS_PUBLISHED);

            if ($request->keyword) {
                $articles = $articles->where(function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->keyword . '%')
                        ->orWhere('content', 'like', '%' . $request->keyword . '%')
                        ->orWhere('description', 'like', '%' . $request->keyword . '%');
                });
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
            $article = Article::with('articleTags')
                ->with('author')
                ->withCount(['reviews as total_review'])
                ->withAvg('reviews as rating', 'stars')
                ->with('enrollment.course')
                ->where('id', $id)
                ->where('status', Article::STATUS_PUBLISHED)
                ->first();

            if ($article) {
                $article->rating = $article->rating ?? 0;
            }

            if (!$article) {
                return $this->errorResponse('Article not found', [], 404);
            }

            return $this->successResponse($article, 'Article fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/articles",
     *     summary="Create a pengetahuan",
     *     description="Create a pengetahuan",
     *     tags={"Articles"},
     *     security={{ "bearer": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"category", "title", "description","visibility", "allow_comments", "thumbnail"},
     *                 @OA\Property(property="enrollment", type="integer", description="Enrollment id"),
     *                 @OA\Property(property="category", type="string", enum={"blog", "video", "document"}, description="Pengetahuan category"),
     *                 @OA\Property(property="title", type="string", description="Pengetahuan title"),
     *                 @OA\Property(property="description", type="string", description="Pengetahuan description"),
     *                 @OA\Property(property="visibility", type="string", enum={"public", "private"}, description="Pengetahuan visibility"),
     *                 @OA\Property(property="allow_comments", type="string", enum={"0", "1"}, description="Allow comments"),
     *                 @OA\Property(property="link", type="string", description="Pengetahuan link"),
     *                 @OA\Property(property="content", type="string", description="Pengetahuan content"),
     *                 @OA\Property(property="tags", type="array", description="Pengetahuan tags", @OA\Items(type="string")),
     *                 @OA\Property(property="thumbnail", type="file", description="Pengetahuan thumbnail"),
     *                 @OA\Property(property="file", type="file", description="Pengetahuan file"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pengetahuan created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Pengetahuan created successfully"),
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|in:blog,document,video',
            'enrollment' => 'nullable|exists:enrollments,id',
            'title' => 'required',
            'description' => 'required',
            'thumbnail' => 'required|mimes:jpg,jpeg,png|max:2048',
            'visibility' => 'required|in:public,internal',
            'link' => 'required_if:category,video',
            'file' => 'nullable|mimes:pdf|max:10240',
            'content' => 'required_if:category,blog',
            'tags' => 'nullable|string',
        ], [
            'category.required' => __('The category field is required'),
            'title.required' => __('The title field is required'),
            'description.required' => __('The description field is required'),
            'thumbnail.required' => __('The thumbnail field is required'),
            'visibility.required' => __('The visibility field is required'),
            'file.required_if' => __('The file field is required when category is document'),
            'content.required_if' => __('The content field is required when category is blog'),
            'category.in' => __('The selected category is invalid'),
            'enrollment.exists' => __('The selected enrollment id is invalid'),
            'thumbnail.mimes' => __('The thumbnail must be a file of type: jpg, jpeg, png'),
            'thumbnail.max' => __('The thumbnail may not be greater than 2048 kilobytes'),
            'file.mimes' => __('The file must be a file of type: pdf'),
            'file.max' => __('The file may not be greater than 10240 kilobytes'),
            'visibility.in' => __('The selected visibility is invalid'),
            'link.required_if' => __('The link field is required when category is video'),
            'tags.string' => __('The tags must be a string'),
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(__('Validation error'), $validator->errors()->toArray(), 422);
        }

        if ($request->enrollment != null) {
            $enrollment = Enrollment::where('user_id', $request->user()->id)->where('id', $request->enrollment)->first();
            if (!$enrollment) {
                return $this->errorResponse('Enrollment not found', [], 404);
            }

            $article = Article::where('enrollment_id', $enrollment->id)->first();
            if ($article) {
                return $this->errorResponse('Pengetahuan already created for this enrollment', [], 409);
            }
        }

        DB::beginTransaction();

        $result = Article::create([
            'slug' => generateUniqueSlug(Article::class, $request->title) . '-' . now()->timestamp,
            'author_id' => $request->user()->id,
            'category' => $request->category,
            'enrollment_id' => $request->enrollment != null ? $enrollment->id : null,
            'title' => $request->title,
            'description' => $request->description,
            'visibility' => $request->visibility,
            'allow_comments' => $request->allow_comments == 'on' ? '1' : '0',
            'link' => $request->link,
            'content' => $request->content,
            'status' => Article::STATUS_DRAFT,
        ]);

        $path = 'pengetahuan/' . now()->year . '/' . now()->month . '/' . $result->id . '/';
        if ($request->category == 'video') {
            $request->validate([
                'link' => 'required|url',
            ]);
        } elseif ($request->category == 'document') {
            $request->validate([
                'file' => 'required|mimes:pdf|max:10240',
            ]);

            $file = $request->file('file');
            $fileName = $path . "document_" . str_replace([' ', '/'], '_', $request->title) . "_" . str_replace(' ', '_', $request->user()->name) . "." . $file->getClientOriginalExtension();
            Storage::disk('private')->put($fileName, file_get_contents($file));
        }
        if ($request->category == 'blog') {
            $request->validate([
                'content' => 'required',
            ]);
        }

        $thumbnail = $request->file('thumbnail');

        $thumbnailName = $path . "thumbnail_" . str_replace([' ', '/'], '_', $request->title) . "_" . str_replace(' ', '_', $request->user()->name) . "." . $thumbnail->getClientOriginalExtension();
        Storage::disk('private')->put($thumbnailName, file_get_contents($thumbnail));

        $result->update([
            'thumbnail' => $thumbnailName,
            'file' => $fileName ?? null,
        ]);

        if (isset($request->tags)) {
            $tags = [];
            foreach (explode(',', $request->tags) as $tag) {
                $res = Tag::firstOrCreate(['name' => $tag]);
                array_push($tags, $res->id);
            }
            $pengetahuan = Article::where('slug', $result->slug)->first();
            $pengetahuan->articleTags()->attach($tags);
            $pengetahuan->save();
        }

        if ($result) {
            DB::commit();
            return $this->successResponse($result, 'Pengetahuan created successfully');
        } else {
            DB::rollBack();
            return $this->errorResponse('Pengetahuan created failed', [], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/articles/{slug}/edit",
     *     summary="Edit an article",
     *     description="Edit an article",
     *     tags={"Articles"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         description="Article slug",
     *         in="path",
     *         name="slug",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article fetched successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Article fetched successfully"),
     *             @OA\Property(property="data", type="object"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Article not found"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="You are not the author of this pengetahuan"),
     *         )
     *     )
     * )
     */
    public function edit($slug)
    {
        try {
            $article = Article::where('slug', $slug)
                ->where(function ($query) {
                    $query->where('status', Article::STATUS_DRAFT)
                        ->orWhere('status', Article::STATUS_REJECTED);
                })->firstOrFail();
            if (!$article) {
                return $this->errorResponse('Pengetahuan not found', [], 404);
            }

            if ($article->author_id != request()->user()->id) {
                return $this->errorResponse('You are not the author of this pengetahuan', [], 403);
            }

            return $this->successResponse($article, 'Pengetahuan fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/articles/{slug}/update",
     *     summary="Update an article",
     *     description="Update an article",
     *     tags={"Articles"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         description="Article slug",
     *         in="path",
     *         name="slug",
     *         required=true,
     *         example="pengetahuan-saya",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"category", "title", "description", "thumbnail", "visibility", "allow_comments"},
     *                 @OA\Property(
     *                     property="category",
     *                     type="string",
     *                     enum={"blog", "document", "video"},
     *                     description="Category of the article",
     *                     example="blog"
     *                 ),
     *                 @OA\Property(
     *                     property="enrollment",
     *                     type="integer",
     *                     description="Enrollment id",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Title of the article",
     *                     example="Pengetahuan Saya"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Description of the article",
     *                     example="<p>Pengetahuan saya tentang teknologi</p>"
     *                 ),
     *                 @OA\Property(
     *                     property="thumbnail",
     *                     type="string",
     *                     format="binary",
     *                     description="Thumbnail image of the article",
     *                 ),
     *                 @OA\Property(
     *                     property="visibility",
     *                     type="string",
     *                     enum={"public", "internal"},
     *                     description="Visibility of the article",
     *                     example="public"
     *                 ),
     *                 @OA\Property(
     *                     property="allow_comments",
     *                     type="boolean",
     *                     description="Allow comments on the article",
     *                     example=true
     *                 ),
     *                 @OA\Property(
     *                     property="link",
     *                     type="string",
     *                     description="Link of the article (only for video category)",
     *                     example="https://www.youtube.com/watch?v=dQw4w9WgXcQ"
     *                 ),
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="File of the article (only for document category)",
     *                 ),
     *                 @OA\Property(
     *                     property="content",
     *                     type="string",
     *                     description="Content of the article (only for blog category)",
     *                     example="<p>Pengetahuan saya tentang teknologi</p>"
     *                 ),
     *                 @OA\Property(
     *                     property="tags",
     *                     type="string",
     *                     description="Tags of the article",
     *                     example="pengetahuan, teknologi"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Pengetahuan updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not the author of this pengetahuan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Pengetahuan not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflict",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Pengetahuan already created for this enrollment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Pengetahuan updated failed")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $slug)
    {
        $article = Article::where('slug', $slug)->first();
        if (!$article) {
            return $this->errorResponse('Pengetahuan not found', [], 404);
        }

        if ($article->author_id != $request->user()->id) {
            return $this->errorResponse('You are not the author of this pengetahuan', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'category' => 'required|in:blog,document,video',
            'enrollment' => 'nullable|exists:enrollments,id',
            'title' => 'required',
            'description' => 'required',
            'thumbnail' => 'required|mimes:jpg,jpeg,png|max:2048',
            'visibility' => 'required|in:public,internal',
            'link' => 'required_if:category,video',
            'file' => 'nullable|mimes:pdf|max:10240',
            'content' => 'required_if:category,blog',
            'tags' => 'nullable|string',
        ], [
            'category.required' => __('The category field is required'),
            'title.required' => __('The title field is required'),
            'description.required' => __('The description field is required'),
            'thumbnail.required' => __('The thumbnail field is required'),
            'visibility.required' => __('The visibility field is required'),
            'file.required_if' => __('The file field is required when category is document'),
            'content.required_if' => __('The content field is required when category is blog'),
            'category.in' => __('The selected category is invalid'),
            'enrollment.exists' => __('The selected enrollment id is invalid'),
            'thumbnail.mimes' => __('The thumbnail must be a file of type: jpg, jpeg, png'),
            'thumbnail.max' => __('The thumbnail may not be greater than 2048 kilobytes'),
            'file.mimes' => __('The file must be a file of type: pdf'),
            'file.max' => __('The file may not be greater than 10240 kilobytes'),
            'visibility.in' => __('The selected visibility is invalid'),
            'link.required_if' => __('The link field is required when category is video'),
            'tags.string' => __('The tags must be a string'),
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(__('Validation error'), $validator->errors()->toArray(), 422);
        }

        if ($request->enrollment != null) {
            $enrollment = Enrollment::where('user_id', $request->user()->id)->where('id', $request->enrollment)->first();
            if (!$enrollment) {
                return $this->errorResponse('Enrollment not found', [], 404);
            }

            $article = Article::where('enrollment_id', $enrollment->id)->first();
            if ($article) {
                return $this->errorResponse('Pengetahuan already created for this enrollment', [], 409);
            }
        }

        DB::beginTransaction();

        $result = $article->update([
            'category' => $request->category,
            'enrollment_id' => $request->enrollment != null ? $enrollment->id : null,
            'title' => $request->title,
            'description' => $request->description,
            'visibility' => $request->visibility,
            'allow_comments' => $request->allow_comments == 'on' ? '1' : '0',
            'link' => $request->link,
            'content' => $request->content,
        ]);

        $path = 'pengetahuan/' . now()->year . '/' . now()->month . '/' . $article->id . '/';
        if ($request->category == 'video') {
            $request->validate([
                'link' => 'required|url',
            ]);
        } elseif ($request->category == 'document') {
            $request->validate([
                'file' => 'required|mimes:pdf|max:10240',
            ]);

            $file = $request->file('file');
            $fileName = $path . "document_" . str_replace([' ', '/'], '_', $request->title) . "_" . str_replace(' ', '_', $request->user()->name) . "." . $file->getClientOriginalExtension();
            Storage::disk('private')->put($fileName, file_get_contents($file));
        }
        if ($request->category == 'blog') {
            $request->validate([
                'content' => 'required',
            ]);
        }

        $thumbnail = $request->file('thumbnail');

        $thumbnailName = $path . "thumbnail_" . str_replace([' ', '/'], '_', $request->title) . "_" . str_replace(' ', '_', $request->user()->name) . "." . $thumbnail->getClientOriginalExtension();
        Storage::disk('private')->put($thumbnailName, file_get_contents($thumbnail));

        $article->update([
            'thumbnail' => $thumbnailName,
            'file' => $fileName ?? null,
        ]);

        if (isset($request->tags)) {
            $tags = [];
            foreach (explode(',', $request->tags) as $tag) {
                $res = Tag::firstOrCreate(['name' => $tag]);
                array_push($tags, $res->id);
            }
            $pengetahuan = Article::where('slug', $article->slug)->first();
            $pengetahuan->articleTags()->sync($tags);
            $pengetahuan->save();
        }

        if ($result) {
            DB::commit();
            return $this->successResponse($article, 'Pengetahuan updated successfully');
        } else {
            DB::rollBack();
            return $this->errorResponse('Pengetahuan updated failed', [], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/articles/{slug}/submit",
     *     summary="Submit an article for verification",
     *     description="Submit an article for verification",
     *     tags={"Articles"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         description="Article slug",
     *         in="path",
     *         name="slug",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article submitted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Article submitted successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="My Article"),
     *                 @OA\Property(property="description", type="string", example="This is my article"),
     *                 @OA\Property(property="category", type="string", example="blog"),
     *                 @OA\Property(property="enrollment_id", type="integer", example=1),
     *                 @OA\Property(property="author_id", type="integer", example=1),
     *                 @OA\Property(property="link", type="string", example="https://www.example.com"),
     *                 @OA\Property(property="content", type="string", example="<p>This is my article</p>"),
     *                 @OA\Property(property="visibility", type="string", example="public"),
     *                 @OA\Property(property="allow_comments", type="integer", example=1),
     *                 @OA\Property(property="thumbnail", type="string", example="path/to/thumbnail.jpg"),
     *                 @OA\Property(property="file", type="string", example="path/to/file.pdf"),
     *                 @OA\Property(property="status", type="string", example="draft"),
     *                 @OA\Property(property="created_at", type="string", example="2022-01-01 00:00:00"),
     *                 @OA\Property(property="updated_at", type="string", example="2022-01-01 00:00:00")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Bad request"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Article not found")
     *         )
     *     )
     * )
     */
    public function submit(Request $request, $slug)
    {
        $pengetahuan = Article::where('slug', $slug)->where('author_id', $request->user()->id)->first();

        if (!$pengetahuan) {
            return $this->errorResponse('Pengetahuan not found', [], 404);
        }

        if ($pengetahuan->status == Article::STATUS_PUBLISHED) {
            return $this->errorResponse('Pengetahuan already published', [], 400);
        }

        if ($pengetahuan->status == Article::STATUS_VERIFICATION) {
            return $this->errorResponse('Pengetahuan sedang diverifikasi', [], 400);
        }

        $pengetahuan->status = Article::STATUS_VERIFICATION;
        $pengetahuan->save();

        return $this->successResponse($pengetahuan, 'Pengetahuan submitted successfully');
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
            $article = Article::with(['reviews' => function ($query) {
                $query->where('status', ArticleReview::STATUS_PUBLISHED)->with('user:id,name,created_at');
            }])->where('id', $id)->where('status', Article::STATUS_PUBLISHED)->first();

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
     *     path="/articles-reviews/{id}",
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
     *         description="User ID",
     *         in="query",
     *         name="user_id",
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
            'user_id' => ['required', 'exists:users,id'],
        ], [
            'comment.required' => __('The comment field is required'),
            'comment.max' => __('The comment must not be greater than 1000 characters'),
            'rating.required' => __('The rating field is required'),
            'rating.numeric' => __('The rating must be a number'),
            'rating.min' => __('The rating must be greater than or equal to 1'),
            'rating.max' => __('The rating must be less than or equal to 5'),
            'user_id.required' => __('The user id field is required'),
            'user_id.exists' => __('The user id does not exist'),
        ]);

        try {
            $articleReview = ArticleReview::where('article_id', $id)->where('author_id', $request->user()->id);

            if ($articleReview->exists()) {
                return $this->errorResponse('You have already reviewed this article', [], 400);
            }

            $articleReview = ArticleReview::create([
                'article_id' => $id,
                'author_id' => $request->user_id,
                'comment' => $request->comment,
                'stars' => $request->rating,
            ]);

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
            $articles = Article::with('author:id,name')
                ->withCount(['reviews as total_review'])
                ->withAvg('reviews as rating', 'stars')
                ->where('status', 'published')
                ->orderByDesc('total_review')
                ->limit(5)->get();
            return $this->successResponse($articles, 'Popular articles fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }
}
