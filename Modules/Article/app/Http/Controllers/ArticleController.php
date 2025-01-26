<?php

namespace Modules\Article\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Modules\Article\app\Http\Requests\PostRequest;
use Modules\Article\app\Models\Article;
use Modules\PendidikanLanjutan\app\Models\Unor;

class ArticleController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $articles = Article::query();

            if ($request->keyword) {
                $articles->where(function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->keyword . '%')
                        ->orWhere('content', 'like', '%' . $request->keyword . '%')
                        ->orWhere('description', 'like', '%' . $request->keyword . '%');
                });
            }

            if ($request->status) {
                $articles->where('status', $request->status);
            }

            if ($request->limit) {
                $articles->limit($request->limit);
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
            $course = Course::with(['instructor'])->findOrFail($request->course_id);
            $instructor = $course->instructor;
            $unor = Unor::findOrFail($instructor->unor_id);

            $validated = array_merge($validated, [
                'author_id' => $instructor->id,
                'verificator_id' =>  $instructor->id,
                'instansi' => $unor->name
            ]);

            $article = Article::create($validated);

            $article->verificator_id = $course->instructor_id;
            $article->slug = generateUniqueSlug(Article::class, $article->title);
            $article->status = 'draft';
            $article->published_at = now();
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
}
