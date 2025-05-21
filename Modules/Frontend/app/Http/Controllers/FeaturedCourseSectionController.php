<?php

namespace Modules\Frontend\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Modules\Course\app\Models\CourseCategory;
use Modules\Frontend\app\Models\FeaturedCourseSection;

class FeaturedCourseSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        checkAdminHasPermissionAndThrowException('section.management');

        $allCourses = Course::active()->whereHas('category.parentCategory', function ($q) {
            $q->where('status', 1);
        })->select(['title', 'id'])->get();
        $categories = CourseCategory::whereNull('parent_id')->where('status', 1)->get();
        $featured = FeaturedCourseSection::first();

        return view('frontend::featured-course-section', compact('allCourses', 'categories', 'featured'));
    }

    function coursesByCategory(Request $request, string $id)
    {

        checkAdminHasPermissionAndThrowException('section.management');

        $courses = Course::select('id', 'title')->whereHas('category', function ($query) use ($id) {
            $query->whereHas('parentCategory', function ($query) use ($id) {
                $query->where('id', $id);
            });
        })
            ->where('status', 'active')->get();

        return $courses;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        checkAdminHasPermissionAndThrowException('section.management');

        $data = $request->except(['_token', '_method']);

        $all_category_ids = Course::active()->whereHas('category.parentCategory', function ($q) {
            $q->where('status', 1);
        })->orderByDesc('id')->limit(20)->get()->pluck('id')->toArray();

        $data['all_category_ids'] = $all_category_ids;

        if ($request->category_one != null && $request->category_one_status == 1) {
            $category_one_ids = Course::active()->whereHas('category.parentCategory', function ($q) use ($request) {
                $q->where('status', 1)->where('id', $request->category_one);
            })->orderByDesc('id')->limit(20)->get()->pluck('id')->toArray();

            $data['category_one_ids'] = $category_one_ids;
        }

        if ($request->category_two != null && $request->category_two_status == 1) {
            $category_two_ids = Course::active()->whereHas('category.parentCategory', function ($q) use ($request) {
                $q->where('status', 1)->where('id', $request->category_two_ids);
            })->orderByDesc('id')->limit(20)->get()->pluck('id')->toArray();

            $data['category_two_ids'] = $category_two_ids;
        }

        if ($request->category_three != null && $request->category_three_status == 1) {
            $category_three_ids = Course::active()->whereHas('category.parentCategory', function ($q) use ($request) {
                $q->where('status', 1)->where('id', $request->category_three_ids);
            })->orderByDesc('id')->limit(20)->get()->pluck('id')->toArray();

            $data['category_three_ids'] = $category_three_ids;
        }

        if ($request->category_four != null && $request->category_four_status == 1) {
            $category_four_ids = Course::active()->whereHas('category.parentCategory', function ($q) use ($request) {
                $q->where('status', 1)->where('id', $request->category_four_ids);
            })->orderByDesc('id')->limit(20)->get()->pluck('id')->toArray();

            $data['category_four_ids'] = $category_four_ids;
        }

        if ($request->category_five != null && $request->category_five_status == 1) {
            $category_five_ids = Course::active()->whereHas('category.parentCategory', function ($q) use ($request) {
                $q->where('status', 1)->where('id', $request->category_five_ids);
            })->orderByDesc('id')->limit(20)->get()->pluck('id')->toArray();

            $data['category_five_ids'] = $category_five_ids;
        }

        // $data['all_category_ids'] = json_encode($request->input('all_category_ids', []));
        // $data['category_one_ids'] = json_encode($request->input('category_one_ids', []));
        // $data['category_two_ids'] = json_encode($request->input('category_two_ids', []));
        // $data['category_three_ids'] = json_encode($request->input('category_three_ids', []));
        // $data['category_four_ids'] = json_encode($request->input('category_four_ids', []));
        // $data['category_five_ids'] = json_encode($request->input('category_five_ids', []));

        // Use updateOrCreate to insert or update the record
        FeaturedCourseSection::updateOrCreate(['id' => 1], $data);

        // Redirect back with a success message
        return redirect()->back()->with(['message' => __('Updated successfully'), 'alert-type' => 'success']);
    }
}
