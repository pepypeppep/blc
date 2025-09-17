<?php

namespace Modules\Frontend\database\seeders;

use App\Enums\ThemeList;
use Illuminate\Database\Seeder;
use Modules\Frontend\app\Models\Home;
use Illuminate\Support\Facades\Storage;

class HomePagesSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hero section images
        $bannerImage = public_path('frontend/img/starter/hero_banner_img.webp');
        $bannerFilename = 'custom-images/hero_banner_img' . time() . '.' . pathinfo($bannerImage, PATHINFO_EXTENSION);
        Storage::disk('private')->put($bannerFilename, file_get_contents($bannerImage));
        $bannerBackground = public_path('frontend/img/starter/hero_banner_bg.webp');
        $bannerBackgroundFilename = 'custom-images/hero_banner_bg' . time() . '.' . pathinfo($bannerBackground, PATHINFO_EXTENSION);
        Storage::disk('private')->put($bannerBackgroundFilename, file_get_contents($bannerBackground));
        $heroBackground = public_path('frontend/img/starter/hero_bg.webp');
        $heroBackgroundFilename = 'custom-images/hero_bg' . time() . '.' . pathinfo($heroBackground, PATHINFO_EXTENSION);
        Storage::disk('private')->put($heroBackgroundFilename, file_get_contents($heroBackground));
        $enrollStudentsImage = public_path('frontend/img/starter/enroll_students_img.png');
        $enrollStudentsFilename = 'custom-images/enroll_students_img' . time() . '.' . pathinfo($enrollStudentsImage, PATHINFO_EXTENSION);
        Storage::disk('private')->put($enrollStudentsFilename, file_get_contents($enrollStudentsImage));

        // About image
        $aboutImage = public_path('frontend/img/starter/about_us.webp');
        $aboutFilename = 'custom-images/about_us' . time() . '.' . pathinfo($aboutImage, PATHINFO_EXTENSION);
        Storage::disk('private')->put($aboutFilename, file_get_contents($aboutImage));

        // Newsletter image
        $newsletterImage = public_path('frontend/img/starter/newsletter_section.webp');
        $newsletterFilename = 'custom-images/newsletter_section' . time() . '.' . pathinfo($newsletterImage, PATHINFO_EXTENSION);
        Storage::disk('private')->put($newsletterFilename, file_get_contents($newsletterImage));

        // FAQ image
        $faqImage = public_path('frontend/img/starter/faq_section.webp');
        $faqFilename = 'custom-images/faq_section' . time() . '.' . pathinfo($faqImage, PATHINFO_EXTENSION);
        Storage::disk('private')->put($faqFilename, file_get_contents($faqImage));

        // Our Features image
        $ourFeaturesImage1 = public_path('frontend/img/starter/our_features_section1.png');
        $ourFeaturesFilename1 = 'custom-images/our_features_section1' . time() . '.' . pathinfo($ourFeaturesImage1, PATHINFO_EXTENSION);
        Storage::disk('private')->put($ourFeaturesFilename1, file_get_contents($ourFeaturesImage1));
        $ourFeaturesImage2 = public_path('frontend/img/starter/our_features_section2.png');
        $ourFeaturesFilename2 = 'custom-images/our_features_section2' . time() . '.' . pathinfo($ourFeaturesImage2, PATHINFO_EXTENSION);
        Storage::disk('private')->put($ourFeaturesFilename2, file_get_contents($ourFeaturesImage2));
        $ourFeaturesImage3 = public_path('frontend/img/starter/our_features_section3.png');
        $ourFeaturesFilename3 = 'custom-images/our_features_section3' . time() . '.' . pathinfo($ourFeaturesImage3, PATHINFO_EXTENSION);
        Storage::disk('private')->put($ourFeaturesFilename3, file_get_contents($ourFeaturesImage3));
        $ourFeaturesImage4 = public_path('frontend/img/starter/our_features_section4.png');
        $ourFeaturesFilename4 = 'custom-images/our_features_section4' . time() . '.' . pathinfo($ourFeaturesImage4, PATHINFO_EXTENSION);
        Storage::disk('private')->put($ourFeaturesFilename4, file_get_contents($ourFeaturesImage4));

        // Banner image
        $bannerImage1 = public_path('frontend/img/starter/banner_section1.webp');
        $bannerFilename1 = 'custom-images/banner_section1' . time() . '.' . pathinfo($bannerImage1, PATHINFO_EXTENSION);
        Storage::disk('private')->put($bannerFilename1, file_get_contents($bannerImage1));
        $bannerImage2 = public_path('frontend/img/starter/banner_section2.webp');
        $bannerFilename2 = 'custom-images/banner_section2' . time() . '.' . pathinfo($bannerImage2, PATHINFO_EXTENSION);
        Storage::disk('private')->put($bannerFilename2, file_get_contents($bannerImage2));

        $home_pages = [
            [
                'slug'     => ThemeList::MAIN->value,
                'sections' => [
                    [
                        'name'           => 'hero_section',
                        'global_content' => [
                            'action_button_url'     => '/courses',
                            'video_button_url'      => 'https://www.youtube.com/watch?v=pMzGDBP6Bic',
                            'banner_image'          => $bannerFilename,
                            'banner_background'     => $bannerBackgroundFilename,
                            'hero_background'       => $heroBackgroundFilename,
                            'enroll_students_image' => $enrollStudentsFilename,
                        ],
                    ],
                    [
                        'name'           => 'about_section',
                        'global_content' => [
                            'button_url' => '/about-us',
                            'video_url'  => 'https://www.youtube.com/watch?v=VkBnNxneA_A',
                            'image'      => $aboutFilename,
                        ],
                    ],
                    [
                        'name'           => 'newsletter_section',
                        'global_content' => [
                            'image' => $newsletterFilename,
                        ],
                    ],
                    [
                        'name'           => 'counter_section',
                        'global_content' => [
                            'total_student_count'    => 3000,
                            'total_instructor_count' => 100,
                            'total_courses_count'    => 800,
                            'total_awards_count'     => 50,
                        ],
                    ],
                    [
                        'name'           => 'faq_section',
                        'global_content' => [
                            'image' => $faqFilename,
                        ],
                    ],
                    [
                        'name'           => 'our_features_section',
                        'global_content' => [
                            'image_one'   => $ourFeaturesFilename1,
                            'image_two'   => $ourFeaturesFilename2,
                            'image_three' => $ourFeaturesFilename3,
                            'image_four'  => $ourFeaturesFilename4,
                        ],
                    ],
                    [
                        'name'           => 'banner_section',
                        'global_content' => [
                            'instructor_image' => $bannerFilename1,
                            'bg_image'         => $bannerFilename2,
                        ],
                    ],
                ],
            ],
            [
                'slug'     => ThemeList::ONLINE->value,
                'sections' => [
                    [
                        'name'           => 'hero_section',
                        'global_content' => [
                            'action_button_url'     => '/courses',
                            'video_button_url'      => 'https://www.youtube.com/watch?v=pMzGDBP6Bic',
                            'banner_image'          => 'uploads/custom-images/theme_online_banner_img.png',
                            'banner_background'     => 'uploads/custom-images/theme_online_banner_bg.svg',
                            'hero_background'       => 'uploads/custom-images/theme_online_hero_bg.png',
                            'enroll_students_image' => 'uploads/custom-images/theme_online_enroll_students_image.png',
                        ],
                    ],
                    [
                        'name'           => 'about_section',
                        'global_content' => [
                            'button_url' => '/about-us',
                            'video_url'  => 'https://www.youtube.com/watch?v=VkBnNxneA_A',
                            'image'      => 'uploads/custom-images/theme_online_about_img.png',
                        ],
                    ],
                    [
                        'name'           => 'newsletter_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/theme_online_newsletter.png',
                        ],
                    ],
                    [
                        'name'           => 'counter_section',
                        'global_content' => [
                            'total_student_count'    => 3000,
                            'total_courses_count'    => 800,
                            'total_instructor_count' => 100,
                        ],
                    ],
                    [
                        'name'           => 'faq_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/theme_online_faq.png',
                        ],
                    ],
                    [
                        'name'           => 'our_features_section',
                        'global_content' => [
                            'image_one'   => 'uploads/custom-images/theme_online_features_icon_1.png',
                            'image_two'   => 'uploads/custom-images/theme_online_features_icon_2.png',
                            'image_three' => 'uploads/custom-images/theme_online_features_icon_3.png',
                            'image_four'  => 'uploads/custom-images/theme_online_features_icon_4.png',
                        ],
                    ],
                    [
                        'name'           => 'banner_section',
                        'global_content' => [
                            'instructor_image' => 'uploads/custom-images/theme_online_instructor_image.png',
                            'student_image'    => 'uploads/custom-images/theme_online_student_image.png',
                        ],
                    ],

                ],
            ],
            [
                'slug'     => ThemeList::UNIVERSITY->value,
                'sections' => [
                    [
                        'name'           => 'hero_section',
                        'global_content' => [
                            'action_button_url'     => '/courses',
                            'banner_image'          => 'uploads/custom-images/theme_university_banner_img.png',
                            'banner_background'     => 'uploads/custom-images/theme_university_banner_bg.svg',
                            'hero_background'       => 'uploads/custom-images/theme_university_hero_bg.jpg',
                            'enroll_students_image' => 'uploads/custom-images/theme_university_enroll_students_image.png',
                        ],
                    ],
                    [
                        'name'           => 'about_section',
                        'global_content' => [
                            'button_url'      => '/about-us',
                            'video_url'       => 'https://www.youtube.com/watch?v=VkBnNxneA_A',
                            'year_experience' => '15',
                            'image'           => 'uploads/custom-images/theme_university_about_img.jpg',
                        ],
                    ],
                    [
                        'name'           => 'newsletter_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/theme_university_newsletter.png',
                        ],
                    ],
                    [
                        'name'           => 'counter_section',
                        'global_content' => [
                            'total_student_count'    => 3000,
                            'total_instructor_count' => 100,
                            'total_courses_count'    => 800,
                            'button_url'             => '/courses',
                        ],
                    ],
                    [
                        'name'           => 'faq_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/theme_university_faq.png',
                        ],
                    ],
                    [
                        'name'           => 'our_features_section',
                        'global_content' => [
                            'image_one'   => 'uploads/custom-images/theme_university_features_icon_1.svg',
                            'image_two'   => 'uploads/custom-images/theme_university_features_icon_2.svg',
                            'image_three' => 'uploads/custom-images/theme_university_features_icon_3.svg',
                            'image_four'  => 'uploads/custom-images/theme_university_features_icon_4.svg',
                        ],
                    ],
                    [
                        'name'           => 'banner_section',
                        'global_content' => [
                            'bg_image'         => 'uploads/custom-images/wsus-img-2024-06-04-11-44-52-8799.jpg',
                        ],
                    ],

                ],
            ],
            [
                'slug'     => ThemeList::BUSINESS->value,
                'sections' => [
                    [
                        'name'           => 'slider_section',
                        'global_content' => [
                            'image_one'   => 'uploads/custom-images/theme_business_slider_1.jpg',
                            'image_two'   => 'uploads/custom-images/theme_business_slider_2.jpg',
                            'image_three' => 'uploads/custom-images/theme_business_slider_3.jpg',
                        ],
                    ],
                    [
                        'name'           => 'about_section',
                        'global_content' => [
                            'button_url'  => '/about-us',
                            'video_url'   => 'https://www.youtube.com/watch?v=VkBnNxneA_A',
                            'image'       => 'uploads/custom-images/theme_business_about_img.jpg',
                            'image_two'   => 'uploads/custom-images/wsus-img-2024-06-03-07-17-53-5555.jpg',
                            'image_three' => 'uploads/custom-images/wsus-img-2024-06-03-07-17-53-6666.jpg',
                        ],
                    ],
                    [
                        'name'           => 'newsletter_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/theme_business_newsletter.png',
                        ],
                    ],
                    [
                        'name'           => 'our_features_section',
                        'global_content' => [
                            'image_one'   => 'uploads/custom-images/theme_business_features_icon_1.png',
                            'image_two'   => 'uploads/custom-images/theme_business_features_icon_2.png',
                            'image_three' => 'uploads/custom-images/theme_business_features_icon_3.png',
                            'image_four'  => 'uploads/custom-images/theme_business_features_icon_4.png',
                        ],
                    ],
                    [
                        'name'           => 'banner_section',
                        'global_content' => [
                            'student_image'    => 'uploads/custom-images/theme_business_student_image.png',
                        ],
                    ],
                    [
                        'name'           => 'faq_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/theme_business_faq.png',
                        ],
                    ],
                ],
            ],
            [
                'slug'     => ThemeList::YOGA->value,
                'sections' => [
                    [
                        'name'           => 'hero_section',
                        'global_content' => [
                            'action_button_url'     => '/courses',
                            'booking_number'      => '+1 (123) 909090',
                            'banner_image'          => 'uploads/custom-images/h4_hero_img.png',
                            'banner_background'     => 'uploads/custom-images/h4_hero_img_shape02.svg',
                            'banner_background_two'     => 'uploads/custom-images/h4_hero_img_shape01.svg',
                            'hero_background'       => 'uploads/custom-images/h4_hero_bg.jpg',
                            'enroll_students_image' => 'uploads/custom-images/theme_yoga_enroll_students_image.png',
                        ],
                    ],
                    [
                        'name'           => 'our_features_section',
                        'global_content' => [
                            'image_one'   => 'uploads/custom-images/h4_features_icon01.svg',
                            'image_two'   => 'uploads/custom-images/h4_features_icon02.svg',
                            'image_three' => 'uploads/custom-images/h4_features_icon03.svg',
                            'image_four' => 'uploads/custom-images/h4_features_icon04.png',
                        ],
                    ],
                    [
                        'name'           => 'about_section',
                        'global_content' => [
                            'button_url' => '/about-us',
                            'video_url'  => 'https://www.youtube.com/watch?v=VkBnNxneA_A',
                            'image'      => 'uploads/custom-images/h4_choose_img.jpg',
                            'image_two'   => 'uploads/custom-images/h4_choose_img02.jpg',
                        ],
                    ],
                    [
                        'name'           => 'banner_section',
                        'global_content' => [
                            'student_image'    => 'uploads/custom-images/h4_cta_img.png',
                            'bg_image'    => 'uploads/custom-images/h4_video_bg.jpg',
                            'video_url'      => 'https://www.youtube.com/watch?v=pMzGDBP6Bic',
                        ],
                    ],
                    [
                        'name'           => 'newsletter_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/theme_yoga_newslettter.png',
                        ],
                    ],
                    [
                        'name'           => 'faq_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/theme_yoga_faq.png',
                        ],
                    ],
                ],
            ],
            [
                'slug'     => ThemeList::KITCHEN->value,
                'sections' => [
                    [
                        'name'           => 'hero_section',
                        'global_content' => [
                            'banner_image'          => 'uploads/custom-images/h8_hero_img.png',
                            'banner_background'     => 'uploads/custom-images/h8_hero_img_shape.svg',
                            'banner_background_two'     => 'uploads/custom-images/h8_hero_img_shape02.svg',
                            'hero_background'       => 'uploads/custom-images/h8_hero_bg.jpg',
                            'enroll_students_image' => 'uploads/custom-images/theme_kitchen_enroll_students_image.png',
                        ],
                    ],
                    [
                        'name'           => 'our_features_section',
                        'global_content' => [
                            'image_one'   => 'uploads/custom-images/theme_kitchen_features_icon_1.png',
                            'image_two'   => 'uploads/custom-images/theme_kitchen_features_icon_2.png',
                            'image_three' => 'uploads/custom-images/theme_kitchen_features_icon_3.png',
                            'image_four' => 'uploads/custom-images/theme_kitchen_features_icon_4.png',
                        ],
                    ],
                    [
                        'name'           => 'about_section',
                        'global_content' => [
                            'button_url' => '/about-us',
                            'video_url'  => 'https://www.youtube.com/watch?v=VkBnNxneA_A',
                            'image'      => 'uploads/custom-images/h8_about_img01.jpg',
                            'image_two'   => 'uploads/custom-images/h8_about_img02.jpg',
                            'image_three'   => 'uploads/custom-images/skillgro-diploma.png',
                            'course_success'   => '86',
                        ],
                    ],
                    [
                        'name'           => 'banner_section',
                        'global_content' => [
                            'student_image'    => 'uploads/custom-images/h8_cta_img.png',
                        ],
                    ],
                    [
                        'name'           => 'faq_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/theme_kitchen_faq.png',
                        ],
                    ],
                    [
                        'name'           => 'newsletter_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/theme_kitchen_newslettter.png',
                        ],
                    ],
                ],
            ],
            [
                'slug'     => ThemeList::KINDERGARTEN->value,
                'sections' => [
                    [
                        'name'           => 'hero_section',
                        'global_content' => [
                            'action_button_url' => '/courses',
                            'banner_image'      => 'uploads/custom-images/h5_hero_img.png',
                            'hero_background'   => 'uploads/custom-images/h5_hero_bg.jpg',
                        ],
                    ],
                    [
                        'name'           => 'our_features_section',
                        'global_content' => [
                            'button_url_one'   => '/about-us',
                            'image_one'        => 'uploads/custom-images/theme_kindergarten_features_icon_1.png',
                            'button_url_two'   => '/about-us',
                            'image_two'        => 'uploads/custom-images/theme_kindergarten_features_icon_2.png',
                            'button_url_three' => '/about-us',
                            'image_three'      => 'uploads/custom-images/theme_kindergarten_features_icon_3.png',
                            'button_url_four'  => '/about-us',
                            'image_four'       => 'uploads/custom-images/theme_kindergarten_features_icon_4.png',
                        ],
                    ],
                    [
                        'name'           => 'about_section',
                        'global_content' => [
                            'phone_number' => '+985 0059 500',
                            'button_url'   => '/about-us',
                            'video_url'    => 'https://www.youtube.com/watch?v=VkBnNxneA_A',
                            'image'        => 'uploads/custom-images/h5_about_img01.jpg',
                            'image_two'    => 'uploads/custom-images/h5_about_img02.jpg',
                        ],
                    ],
                    [
                        'name'           => 'faq_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/h5_faq_img.jpg',
                        ],
                    ],
                    [
                        'name'           => 'newsletter_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/theme_kindergarten_newsletter.png',
                        ],
                    ],
                    [
                        'name'           => 'banner_section',
                        'global_content' => [
                            'student_image' => 'uploads/custom-images/theme_kindergarten_student_image.png',
                        ],
                    ],
                ]
            ],
            [
                'slug'     => ThemeList::LANGUAGE->value,
                'sections' => [
                    [
                        'name'           => 'hero_section',
                        'global_content' => [
                            'action_button_url' => '/courses',
                            'video_button_url'      => 'https://www.youtube.com/watch?v=pMzGDBP6Bic',
                            'banner_image'      => 'uploads/custom-images/h6_hero_img.jpg',
                            'hero_background'   => 'uploads/custom-images/h6_hero_bg.jpg',
                            'enroll_students_image' => 'uploads/custom-images/theme_language_enroll_students_image.png',
                        ],
                    ],

                    [
                        'name'           => 'about_section',
                        'global_content' => [
                            'button_url'   => '/about-us',
                            'video_url'    => 'https://www.youtube.com/watch?v=VkBnNxneA_A',
                            'image'        => 'uploads/custom-images/h6_choose_img.jpg',
                        ],
                    ],

                    [
                        'name'           => 'faq_section',
                        'global_content' => [
                            'image'     => 'uploads/custom-images/h6_faq_img01.jpg',
                            'image_two' => 'uploads/custom-images/h6_faq_img02.jpg',
                        ],
                    ],

                    [
                        'name'           => 'counter_section',
                        'global_content' => [
                            'total_student_count'    => 3000,
                            'total_instructor_count' => 100,
                            'image'    => 'uploads/custom-images/theme_language_fact_img.png',
                        ],
                    ],


                    [
                        'name'           => 'our_features_section',
                        'global_content' => [
                            'image_one'   => 'uploads/custom-images/theme_language_features_icon_1.png',
                            'image_two'   => 'uploads/custom-images/theme_language_features_icon_2.png',
                            'image_three' => 'uploads/custom-images/theme_language_features_icon_3.png',
                            'image_four' => 'uploads/custom-images/theme_language_features_icon_4.png',
                        ],
                    ],

                    [
                        'name'           => 'banner_section',
                        'global_content' => [
                            'student_image' => 'uploads/custom-images/theme_language_student_image.png',
                        ],
                    ],
                    [

                        'name'           => 'newsletter_section',
                        'global_content' => [
                            'image' => 'uploads/custom-images/theme_language_newsletter.png',
                        ],
                    ],
                ],
            ],
        ];
        foreach ($home_pages as $home) {
            $page = Home::create(['slug' => $home['slug']]);

            foreach ($home['sections'] as $section) {
                $page->sections()->create(['name' => $section['name'], 'global_content' => $section['global_content']]);
            }
        }
    }
}
