<?php

namespace Modules\Frontend\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Frontend\app\Http\Requests\FeaturedInstructorSectionUpdateRequest;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Frontend\app\Models\FeaturedInstructor;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\Frontend\app\Models\FeaturedInstructorTranslation;

class FeaturedInstructorSectionSeeder extends Seeder
{
    use GenerateTranslationTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        FeaturedInstructor::create([
            'id' => 1
        ]);

        $featured = FeaturedInstructor::updateOrCreate(
            ['id' => 1],
            [
                'button_url'     => '/all-instructors',
                'instructor_ids' => json_encode(["1001", "1002", "1003", "1004", "1005", "1006"]),
            ]
        );

        $translation = FeaturedInstructorTranslation::where('featured_instructor_section_id', $featured->id)->exists();

        $request = new FeaturedInstructorSectionUpdateRequest();
        $request->merge([
            "featured_instructor_section_id" => 1,
            "title" => "Instruktur Unggulan",
            "sub_title" => "Potong bebek angsa Masak di kuali Nona minta dansa, dansa empat kali Sorong ke kiri Sorong ke kanan Lalalala Sorong ke kiri Sorong ke kanan Lalalala",
            "button_text" => "Lihat selengkapnya",
            "lang_code" => "id",
        ]);

        if (!$translation) {
            $this->generateTranslations(
                TranslationModels::FeaturedInstructorSection,
                $featured,
                'featured_instructor_section_id',
                $request,
            );
        }

        $this->updateTranslations(
            $featured,
            $request,
            $request->all(),
        );
    }
}
