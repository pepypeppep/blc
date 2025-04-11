<?php

namespace Modules\Article\database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Article\app\Models\Article;
use Modules\Order\app\Models\Enrollment;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articleCategory = ['blog','document','video'];
        $visibility = ['public','internal'];
        $statuses = ['draft','published','rejected','verification'];
        $users = User::where('role', 'student')->get();
        $enrollments = Enrollment::query();
        for ($i = 1; $i <= 23; $i++) {
            $cat = $articleCategory[rand(0, 2)];
            $user = $users->random()->id;
            Article::create([
                'slug' => Str::random(10) . '_' . now()->timestamp,
                'author_id' => $user,
                'enrollment_id' => optional($enrollments->where('user_id', $user)->first())->id,
                'category' => $cat,
                'title' => Str::random(10),
                'description' => Str::random(20),
                'visibility' => $visibility[rand(0, 1)],
                'allow_comments' => rand(0, 1),
                'link' => $cat == 'video' ? Str::random(10) : null,
                'thumbnail' => 'https://images.tokopedia.net/img/cache/500-square/VqbcmM/2025/3/27/f0d70eee-f1d1-4751-b7d3-a1fe3ff69a07.jpg.webp?ect=4g',
                'file' => $cat == 'document' ? Str::random(10) : null,
                'content' => Str::random(20),
                'status' => $statuses[rand(0, 3)]
            ]);
        }
    }
}