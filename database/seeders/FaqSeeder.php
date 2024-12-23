<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Faq\app\Models\Faq;
use Modules\Faq\app\Http\Requests\FaqRequest;
use Modules\Language\app\Enums\TranslationModels;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class FaqSeeder extends Seeder
{
    use GenerateTranslationTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'question' => 'Pertanyaan 1',
                'answer' => 'Potong bebek angsa Masak di kuali Nona minta dansa, dansa empat kali Sorong ke kiri Sorong ke kanan Lalalala Sorong ke kiri Sorong ke kanan Lalalala',
            ],
            [
                'question' => 'Pertanyaan 2',
                'answer' => 'Potong bebek angsa Masak di kuali Nona minta dansa, dansa empat kali Sorong ke kiri Sorong ke kanan Lalalala Sorong ke kiri Sorong ke kanan Lalalala',
            ],
            [
                'question' => 'Pertanyaan 3',
                'answer' => 'Potong bebek angsa Masak di kuali Nona minta dansa, dansa empat kali Sorong ke kiri Sorong ke kanan Lalalala Sorong ke kiri Sorong ke kanan Lalalala',
            ],
        ];

        foreach ($questions as $key => $q) {
            $request = new FaqRequest();
            $request->merge([
                'question' => $q['question'],
                'answer' => $q['answer']
            ]);
            $faq = Faq::create([
                'status' => 1
            ]);

            $this->generateTranslations(
                TranslationModels::Faq,
                $faq,
                'faq_id',
                $request,
            );
        }
    }
}
