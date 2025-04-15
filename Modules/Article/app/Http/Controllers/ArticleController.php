<?php

namespace Modules\Article\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Article\app\Models\Article;
use Modules\Article\app\Models\ArticleReview;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        
        $articles = Article::when($status, function ($query, $status) {
                return $query->where('status', $status);
            }, function ($query) {
                return $query->where('status', '!=', 'draft');
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('article::index', compact('articles'));
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $article = Article::find($id);
        $comments = ArticleReview::with('user')->whereHas('post', function ($query) use ($id) {
            $query->where('article_id', $id);
        })->orderBy('created_at', 'desc')->get();

        return view('article::show', compact('article', 'comments'));
    }

    public function updateStatus(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $status = $request->input('status');

        if ($status === 'rejected') {
            $request->validate([
                'rejected_reason' => 'required|string|max:1000',
            ]);
            $article->status = 'rejected';
            $article->note = $request->input('rejected_reason');
        } elseif ($status === 'published') {
            $article->status = 'published';
            $article->published_at = now();
        } else {
            return back()->with('error', 'Status tidak valid.');
        }

        $article->save();

        return redirect()->back()->with('success', 'Status pengetahuan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
