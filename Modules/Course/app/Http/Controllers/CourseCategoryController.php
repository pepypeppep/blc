<?php

namespace Modules\Course\app\Http\Controllers;

use App\Models\Course;
use App\Enums\RedirectType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Modules\Blog\app\Models\BlogCategory;
use Modules\Language\app\Models\Language;
use Modules\Course\app\Models\CourseCategory;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\Course\app\Http\Requests\CourseCategoryStoreRequest;
use Modules\Course\app\Http\Requests\CourseCategoryUpdateRequest;

class CourseCategoryController extends Controller
{
    use GenerateTranslationTrait, RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CourseCategory::query();
        $query->when($request->keyword, function ($q) {
            $q->whereHas('translations', function ($q) {
                $q->where('name', 'like', '%' . request('keyword') . '%');
            });
        });
        $query->where('parent_id', $request->parent_id);
        $query->when(
            $request->status !== null && $request->status !== '',
            fn($q) => $q->where('status', $request->status)
        );
        $orderBy = $request->order_by == 1 ? 'asc' : 'desc';
        $categories = $request->get('par-page') == 'all' ?
            $query->orderBy('id', $orderBy)->get() :
            $query->orderBy('id', $orderBy)->paginate($request->get('par-page') ?? null)->withQueryString();
        $parentCategory = CourseCategory::find($request->parent_id);
        return view('course::course-category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('course::course-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseCategoryStoreRequest $request): RedirectResponse
    {
        $file = $request->file('icon');
        $path = 'course/category/';
        $filename = $request->slug . '_' . time() . '.png';
        Storage::disk('private')->put($path . $filename, file_get_contents($file));

        $category = new CourseCategory();
        $category->slug = $request->slug;
        $category->icon = $path . $filename;
        $category->status = $request->status;
        $category->show_at_trending = $request->show_at_trending;
        $category->save();

        $languages = Language::all();

        $this->generateTranslations(
            TranslationModels::CourseCategory,
            $category,
            'course_category_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.course-category.edit', ['course_category' => $category->id, 'code' => $languages->first()->code]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $category = CourseCategory::findOrFail($id);
        $languages = allLanguages();

        return view('course::course-category.edit', compact('category', 'code', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseCategoryUpdateRequest $request, $id): RedirectResponse
    {

        $category = CourseCategory::findOrFail($id);
        if ($request->hasFile('icon')) {
            if (Storage::disk('private')->exists($category->icon)) {
                Storage::disk('private')->delete($category->icon);
            }
            $file = $request->file('icon');
            $path = 'course/category/';
            $filename = $request->slug . '_' . time() . '.png';
            Storage::disk('private')->put($path . $filename, file_get_contents($file));

            $category->icon = $path . $filename;
        }
        $category->status = $request->status;
        $category->show_at_trending = $request->show_at_trending;
        $category->save();

        $languages = Language::all();

        $this->updateTranslations(
            $category,
            $request,
            $request->validated(),
        );

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.course-category.edit', ['course_category' => $category->id, 'code' => $languages->first()->code]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = CourseCategory::findOrFail($id);
        if (CourseCategory::where('parent_id', $id)->exists()) {
            return redirect()->route('admin.course-category.index')->with(['messege' => 'Category can not be deleted because it has sub categories', 'alert-type' => 'error']);
        }

        if ($category->icon) {
            if (\File::exists(public_path($category->icon))) {
                unlink(public_path($category->icon));
            }
        }
        $category->translations()->delete();
        $category->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.course-category.index');
    }

    public function statusUpdate($id)
    {
        checkAdminHasPermissionAndThrowException('blog.category.update');
        $category = CourseCategory::find($id);
        $status = $category->status == 1 ? 0 : 1;
        $category->update(['status' => $status]);

        $notification = __('Updated Successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }

    public function getFile($id)
    {
        $category = CourseCategory::findOrFail($id);
        return response()->file(Storage::disk('private')->path($category->icon));
    }
}
