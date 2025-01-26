<?php

namespace Modules\Article\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Pagination\Paginator;
use Modules\Article\app\Models\ArticleComment;

class ArticleCommentController extends Controller
{
    use RedirectHelperTrait;

    public function index()
    {
        checkAdminHasPermissionAndThrowException('article.comment.view');
        Paginator::useBootstrap();

        $comments = ArticleComment::latest()->paginate(15);

        return view('article::Comment.index', compact('comments'));
    }

    public function show($id)
    {
        checkAdminHasPermissionAndThrowException('article.comment.view');
        $comments = ArticleComment::where('article_id', $id)->paginate(20);

        return view('article::Comment.show', compact('comments'));
    }

    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('article.comment.delete');
        ArticleComment::findOrFail($id)?->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.article-comment.index');
    }

    public function statusUpdate($id)
    {
        checkAdminHasPermissionAndThrowException('article.comment.update');
        $articleCategory = ArticleComment::find($id);
        if ($articleCategory) {
            $status = $articleCategory->status == 1 ? 0 : 1;
            $articleCategory->update(['status' => $status]);

            $notification = __('Updated Successfully');

            return response()->json([
                'success' => true,
                'message' => $notification,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('Failed!'),
        ]);
    }
}
