<?php

namespace Modules\Article\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Tag;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Modules\Article\app\Http\Requests\PostRequest;
use Modules\Article\app\Http\Requests\SubmissionRequest;
use Modules\Article\app\Models\Article;

class ArticleController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $articles = Article::with('articleTags');

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

            if ($request->limit) {
                $articles = $articles->limit($request->limit);
            }

            $articles = $articles->get();
            return $this->successResponse($articles, 'Articles fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function store(PostRequest $request)
    {
        try {
            $validated = $request->validated();

            $course = Course::with(['instructor', 'instructor.unor'])->findOrFail($validated['course_id']);

            $instructor = $course->instructor;
            $unor = $course->instructor->unor;

            $validated = array_merge($validated, [
                'author_id' => $instructor->id,
                'verificator_id' =>  $instructor->id,
                'instansi' => $unor->name
            ]);

            $validated['verificator_id'] = $course->instructor_id;
            $validated['slug'] = generateUniqueSlug(Article::class, $validated['title']);
            $validated['status'] = "draft";
            $validated['published_at'] = now();
            $article = Article::create($validated);

            if (isset($validated['tags'])) {
                $tags = [];
                foreach ($validated['tags'] as $tag) {
                    $res = Tag::firstOrCreate(['name' => $tag]);
                    array_push($tags, $res->id);
                }
                $article->articleTags()->attach($tags);
            }

            $article->save();

            return $this->successResponse($article, 'Article created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function update(PostRequest $request, $slug)
    {
        try {
            $validated = $request->validated();
            $article = Article::where('slug', $slug)->first();

            if (!$article) {
                return $this->errorResponse('Article not found', [], 404);
            }
            if ($article->status != 'draft') {
                return $this->errorResponse('Article is not in draft status', [], 400);
            }

            if (isset($validated['tags'])) {
                $tags = [];
                foreach ($validated['tags'] as $tag) {
                    $res = Tag::firstOrCreate(['name' => $tag]);
                    array_push($tags, $res->id);
                }
                $article->articleTags()->sync($tags);
            }


            $article->update($validated);
            return $this->successResponse($article, 'Article updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function destroy($slug)
    {
        try {
            $article = Article::where('slug', $slug)->first();

            if (!$article) {
                return $this->errorResponse('Article not found', [], 404);
            }

            if ($article->status != 'draft') {
                return $this->errorResponse('Article is not in draft status', [], 400);
            }


            $article->delete();
            return $this->successResponse([], 'Article deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function show($slug)
    {
        try {
            $article = Article::where('slug', $slug)->first();
            if (!$article) {
                return $this->errorResponse('Article not found', [], 404);
            }
            return $this->successResponse($article, 'Article fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function updateSubmission(SubmissionRequest $request, $slug)
    {
        try {
            $validated = $request->validated();
            $article = Article::where('slug', $slug)->first();

            if (!$article) {
                return $this->errorResponse('Article not found', [], 404);
            }

            if ($article->status != 'draft') {
                return $this->errorResponse('Article is not in draft status', [], 400);
            }

            $article->status = $validated['status'];
            $article->save();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }
}
