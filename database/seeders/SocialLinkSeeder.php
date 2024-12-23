<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\SocialLink\app\Models\SocialLink;

class SocialLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SocialLink::create([
            'link' => 'https://facebook.com/',
            'icon' => '/uploads/custom-images/wsus-img-2024-06-06-07-11-34-2262.png'
        ]);
        SocialLink::create([
            'link' => 'https://linkedin.com/',
            'icon' => '/uploads/custom-images/wsus-img-2024-06-06-07-12-11-1915.png'
        ]);
        SocialLink::create([
            'link' => 'https://web.whatsapp.com/',
            'icon' => '/uploads/custom-images/wsus-img-2024-06-06-07-32-08-4118.png'
        ]);
        SocialLink::create([
            'link' => 'https://youtube.com/',
            'icon' => '/uploads/custom-images/wsus-img-2024-06-06-07-15-41-1121.png'
        ]);
    }
}
