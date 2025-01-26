<?php

namespace Modules\Article\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\Paginator;
use Modules\Article\app\Http\Requests\CategoryRequest;
use Modules\Article\app\Models\ArticleCategory;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class ArticleCategoryController extends Controller
{
    use GenerateTranslationTrait, RedirectHelperTrait;

    public function index()
    {
        checkAdminHasPermissionAndThrowException('article.category.view');

        Paginator::useBootstrap();

        $categories = ArticleCategory::paginate(15);

        return view('article::Category.index', ['categories' => $categories]);
    }

    public function create()
    {
        checkAdminHasPermissionAndThrowException('article.category.create');

        return view('article::Category.create');
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        checkAdminHasPermissionAndThrowException('article.category.store');
        $category = ArticleCategory::create($request->validated());

        $languages = Language::all();

        $this->generateTranslations(
            TranslationModels::ArticleCategory,
            $category,
            'article_category_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.article-category.edit', ['article_category' => $category->id, 'code' => $languages->first()->code]);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        checkAdminHasPermissionAndThrowException('article.category.view');

        return view('article::Category.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        checkAdminHasPermissionAndThrowException('article.category.edit');
        $code = request('code') ?? getSessionLanguage();
        if (! Language::where('code', $code)->exists()) {
            abort(404);
        }
        $category = ArticleCategory::findOrFail($id);
        $languages = allLanguages();

        return view('article::Category.edit', compact('category', 'code', 'languages'));
    }

    public function update(CategoryRequest $request, ArticleCategory $article_category)
    {
        checkAdminHasPermissionAndThrowException('article.category.update');
        $validatedData = $request->validated();

        $article_category->update($validatedData);

        $this->updateTranslations(
            $article_category,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.article-category.edit', ['article_category' => $article_category->id, 'code' => $request->code]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArticleCategory $articleCategory)
    {
        checkAdminHasPermissionAndThrowException('article.category.delete');
        if ($articleCategory->posts()->count() > 0) {
           return redirect()->back()->with(['alert-type' => 'error', 'messege' => __('Can not delete this category because it has posts')]);
        }
        $articleCategory->translations()->each(function ($translation) {
            $translation->category()->dissociate();
            $translation->delete();
        });

        $articleCategory->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.article-category.index');
    }

    public function statusUpdate($id)
    {
        checkAdminHasPermissionAndThrowException('article.category.update');
        $articleCategory = ArticleCategory::find($id);
        $status = $articleCategory->status == 1 ? 0 : 1;
        $articleCategory->update(['status' => $status]);

        $notification = __('Updated Successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
