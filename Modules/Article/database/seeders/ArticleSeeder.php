<?php

namespace Modules\Article\database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Article\app\Models\Article;
use Modules\Order\app\Models\Enrollment;
use Faker\Factory as Faker;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $articleCategory = ['blog','document','video'];
        $visibility = ['public','internal'];
        $statuses = ['draft','published','rejected','verification'];
        $users = User::where('role', 'student')->get();
        $enrollments = Enrollment::query();
        for ($i = 1; $i <= 23; $i++) {
            $cat = $articleCategory[rand(0, 2)];
            $user = $users->random()->id;
            $status = $statuses[rand(0, 3)];
            $title = $faker->sentence();
            Article::create([
                'slug' => $title . '_' . now()->timestamp,
                'author_id' => $user,
                'enrollment_id' => optional($enrollments->where('user_id', $user)->first())->id,
                'category' => $cat,
                'title' => $title,
                'description' => $faker->paragraph(),
                'visibility' => $visibility[rand(0, 1)],
                'allow_comments' => rand(0, 1),
                'link' => $cat == 'video' ? $faker->url : null,
                'thumbnail' => $faker->imageUrl(),
                'file' => $cat == 'document' ? $faker->url : null,
                'content' => $faker->paragraph(),
                'status' => $status,
                'note' => $status == 'rejected' ? $faker->paragraph() : null
            ]);
        }
    }
}