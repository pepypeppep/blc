<?php

namespace Modules\Article\database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Article\app\Models\Article;
use Modules\Article\app\Models\ArticleReview;
use Faker\Factory as Faker;

class ArticleReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $articles = Article::get();
        $statuses = ['published', 'unpublished'];
        
        foreach ($articles as $article) {
            $users = User::where('id', '!=', $article->author_id)->get();
            for ($i = 1; $i <= rand(3, 12); $i++) {
                $status = $statuses[rand(0, 1)];
                $user = $users->random()->id;
                ArticleReview::create([
                    'article_id' => $article->id,
                    'author_id' => $user,
                    'stars' => rand(1, 5),
                    'description' => $faker->paragraph(),
                    'status' => $status,
                    'notes' => $status == 'unpublished' ? $faker->paragraph() : null,
                ]);
            }
        }
    }
}
