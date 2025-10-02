<?php

namespace Modules\Article\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Article\app\Models\Article;
use Modules\Article\app\Models\ArticleReview;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'verification');
        $status = $status === 'all' ? null : $status;

        $articles = Article::when($status, function ($query, $status) {
            return $query->where('status', $status);
        }, function ($query) {
            return $query->where('status', '!=', 'draft');
        })
            ->orderByDesc('updated_at')
            ->paginate(10)
            ->appends(['status' => $request->query('status', 'verification')]);

        $statusCounts = Article::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        $totalArticles = Article::where('status', '!=', 'draft')->count();

        return view('article::index', compact('articles', 'statusCounts', 'totalArticles', 'status'));
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

    public function update(Request $request, $id)
    {
        try {
            $article = Article::findOrFail($id);

            $request->validate([
                'description' => 'required|string',
                'content' => 'nullable|string',
            ]);

            $article->update([
                'description' => $request->input('description'),
                'content' => $request->input('content'),
                'editor_id' => Auth::user()->id,
                'edited_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Status pengetahuan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $status = $request->input('status');

        if ($status === 'rejected') {
            $request->validate([
                'rejected_reason' => 'required|string|max:1000',
            ]);
            $article->verificator_id = Auth::user()->id;
            $article->status = 'rejected';
            $article->note = $request->input('rejected_reason');

            if ($article->personal_certificate_recognition_id) {
                $article->certificateRecognition()->update([
                    'status' => 'rejected',
                ]);
            }
        } elseif ($status === 'published') {
            $article->verificator_id = Auth::user()->id;
            $article->status = 'published';
            $article->published_at = now();

            if ($article->personal_certificate_recognition_id) {
                $article->certificateRecognition()->update([
                    'status' => 'published',
                ]);
            }
        } else {
            return back()->with('error', 'Status tidak valid.');
        }

        $article->save();

        return redirect()->route('admin.knowledge.index')->with('success', 'Status pengetahuan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
