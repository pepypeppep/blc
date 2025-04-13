<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Rules\CustomRecaptcha;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Modules\Article\app\Models\Article;
use Modules\Article\app\Models\ArticleTag;
use Modules\Article\app\Models\ArticleComment;

class ArticleController extends Controller
{
    function index()
    {
        $query = Article::query();
        $query->when(request('search'), function ($query) {
            $query->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%');
        });
        $query->when(request('category'), function ($query) {
            $query->where('category', request('category'));
        });
        $query->when(request('tags'), function ($query) {
            $query->whereHas('tags', function ($query) {
                $query->where('name', request('tags'));
            });
        });
        $articles = $query->where(['status' => 'published'])->orderBy('created_at', 'desc')->paginate(9);

        $tags = Tag::has('articles')->get();
        $categories = ['blog', 'document', 'video'];
        $popularArticles = Article::withCount('comments')->orderBy('comments_count', 'desc')->limit(8)->get();

        return view('frontend.pages.article', compact('articles', 'tags', 'categories', 'popularArticles'));
    }

    function show(string $slug)
    {
        $article = Article::where('slug', $slug)->isPublished()->firstOrFail();
        $latestBlogs = Article::where('slug', '!=', $slug)->isPublished()->orderBy('created_at', 'desc')->limit(8)->get();
        $categories = ['blog', 'document', 'video'];
        $tags = Tag::has('articles')->get();
        $comments = ArticleComment::whereHas('post', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->orderBy('created_at', 'desc')->get();

        return view('frontend.pages.article-details', compact('article', 'latestBlogs', 'categories', 'tags', 'comments'));
    }

    function submitComment(Request $request)
    {
        $request->validate([
            'comment' => ['required', 'max:1000'],
            'g-recaptcha-response' => Cache::get('setting')->recaptcha_status == 'active' ? ['required', new CustomRecaptcha()] : 'nullable',
        ], [
            'comment.required' => __('The comment field is required'),
            'comment.max' => __('The comment must not be greater than 1000 characters'),
            'g-recaptcha-response.required' => __('The reCAPTCHA verification is required'),
            'g-recaptcha-response.recaptcha' => __('The reCAPTCHA verification failed'),
        ]);
        $comment = new ArticleComment();

        $comment->article_id = $request->article_id;
        $comment->user_id = userAuth()->id;
        $comment->comment = $request->comment;
        $comment->save();
        return redirect()->back()->withFragment('comments')->with(['messege' => __('Comment added successfully. waiting for approval'), 'alert-type' => 'success']);
    }
}
