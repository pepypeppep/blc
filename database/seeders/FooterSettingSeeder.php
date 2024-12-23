<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\FooterSetting\app\Models\FooterSetting;

class FooterSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FooterSetting::create([
            'logo' => '/uploads/custom-images/wsus-img-2024-06-04-12-31-51-4487.svg',
            'footer_text' => 'Potong bebek angsa Masak di kuali Nona minta dansa, dansa empat kali Sorong ke kiri Sorong ke kanan Lalalala Sorong ke kiri Sorong ke kanan Lalalala',
            'address' => 'Jl. RW Monginsidi 1 Bantul Daerah Istimewa Yogyakarta 55711',
            'phone' => '0274-367509',
            'get_in_touch_text' => 'Jika anda memerlukan bantuan, anda dapat menghubungi kami melalui sosial media kami ataupun mengirim E-mail',
            'google_play_link' => 'https://play.google.com/store/apps/details?id=id.go.bantulkab.bantulpedia&hl=id',
            'apple_store_link' => 'https://apps.apple.com/id/app/bantulpedia/id1579902635?l=id'
        ]);
    }
}
