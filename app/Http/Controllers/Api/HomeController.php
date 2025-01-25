<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Modules\Faq\app\Models\Faq;
use Illuminate\Support\Facades\DB;
use Modules\Brand\app\Models\Brand;
use App\Http\Controllers\Controller;
use Modules\Course\app\Models\CourseCategory;
use Modules\Frontend\app\Models\ContactSection;
use Modules\Testimonial\app\Models\Testimonial;
use Modules\Frontend\app\Models\FeaturedInstructor;
use Modules\Frontend\app\Models\SectionTranslation;
use Modules\Frontend\app\Models\FeaturedCourseSection;

class HomeController extends Controller
{
    function index()
    {
        try {
            $section_data = SectionTranslation::select('sections.name', 'section_translations.content')
                ->join('sections', 'section_translations.section_id', '=', 'sections.id')
                ->get();

            $sections = [];
            foreach ($section_data as $key => $section) {
                $sections[str_replace('_section', '', $section->name)] = $section->content;
            }
            $contactSection = ContactSection::first();
            $sections['contact_us'] = json_decode(json_encode([
                'address' => $contactSection->address,
                'phone_one' => $contactSection->phone_one,
                'phone_two' => $contactSection->phone_two,
                'email_one' => $contactSection->email_one,
                'email_two' => $contactSection->email_two,
                'map' => $contactSection->map,
            ]));

            $faqs = Faq::with('translation')->where('status', 1)->get();

            $trendingCategories = CourseCategory::with(['translation:id,name,course_category_id', 'subCategories' => function ($query) {
                $query->withCount(['courses' => function ($query) {
                    $query->where('status', 'active');
                }]);
            }])->withCount(['subCategories as active_sub_categories_count' => function ($query) {
                $query->whereHas('courses', function ($query) {
                    $query->where('status', 'active');
                });
            }])->whereNull('parent_id')
                ->where('status', 1)
                ->where('show_at_trending', 1)
                ->get();

            $brands = Brand::where('status', 1)->get();

            $featuredCourse = FeaturedCourseSection::first();

            $featuredInstructorSection = FeaturedInstructor::first();
            $instructorIds = json_decode($featuredInstructorSection->instructor_ids ?? '[]');

            $selectedInstructors = User::whereIn('id', $instructorIds)
                ->with(['courses' => function ($query) {
                    $query->withCount(['reviews as avg_rating' => function ($query) {
                        $query->select(DB::raw('coalesce(avg(rating),0)'));
                    }]);
                }])
                ->get();

            $testimonials = Testimonial::all();

            $users = User::count();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'users' => $users,
                    'sections' => $sections,
                    'faqs' => $faqs,
                    'trendingCategories' => $trendingCategories,
                    'brands' => $brands,
                    'featuredCourse' => $featuredCourse,
                    'selectedInstructors' => $selectedInstructors,
                    'testimonials' => $testimonials
                ]
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
}
