<?php

namespace Modules\Article\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Article\app\Models\ArticleComment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ArticleComment::with('post');

        if ($request->keyword) {
            $query->where('description', 'like', '%' . $request->keyword . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $comments = $query->orderByDesc('reported_count')->orderByDesc('id')->paginate(10);

        return view('article::article-comments.index', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('article::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $comment = ArticleComment::with('post', 'reports')->findOrFail($id);

        return view('article::article-comments.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('article::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate(['status' => 'required']);
        $review = ArticleComment::findOrFail($id);
        $review->status = $request->status;
        $review->save();

        return redirect()->route('admin.knowledge-comments.index')->with(['alert-type' => 'success', 'messege' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
