<?php

namespace App\Imports;

use App\Models\QuizQuestion;
use App\Models\QuizQuestionAnswer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class QuizQuestionImport implements ToCollection, WithHeadingRow
{
    protected $quizId;

    public function __construct(string $quizId)
    {
        $this->quizId = $quizId;
    }

    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            $questionMap = [];

            foreach ($rows as $row) {
                $judulSoal = trim($row['judul_soal']);
                $key = $this->quizId . '|' . $judulSoal;

                // Cek apakah pertanyaan sudah pernah dicari/ditambahkan sebelumnya
                if (!isset($questionMap[$key])) {
                    // Cek ke database apakah sudah ada pertanyaan ini
                    $existingQuestion = QuizQuestion::where('quiz_id', $this->quizId)
                        ->where('title', $judulSoal)
                        ->first();

                    if ($existingQuestion) {
                        $questionMap[$key] = $existingQuestion->id;
                    } else {
                        $newQuestion = QuizQuestion::create([
                            'quiz_id' => $this->quizId,
                            'title'   => $judulSoal,
                            'type'    => 'multiple',
                            'grade'   => $row['nilai'],
                        ]);

                        $questionMap[$key] = $newQuestion->id;
                    }
                }

                // Cek apakah jawaban sudah ada untuk pertanyaan ini
                $existingAnswer = QuizQuestionAnswer::where('question_id', $questionMap[$key])
                    ->where('title', $row['jawaban'])
                    ->first();

                if (!$existingAnswer) {
                    QuizQuestionAnswer::create([
                        'question_id' => $questionMap[$key],
                        'title'       => $row['jawaban'],
                        'correct'     => $row['benar'],
                    ]);
                }
            }
        });
    }
}
