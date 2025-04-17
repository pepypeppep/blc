<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;
use Modules\Faq\app\Models\Faq;
use Modules\Faq\app\Http\Requests\FaqRequest;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class ConfigSeeder extends Seeder
{
    use GenerateTranslationTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            'bantara_nik' => '1313312',
            'bantara_key' => 'bantara_key',
            'bantara_url' => 'bantara_url',
            'bantara_callback_key' => 'bantara_callback_key',
        ];


        foreach ($configs as $key => $value) {
            Config::create([
                'key' => $key,
                'value' => $value,
            ]);
        }
    }
}
