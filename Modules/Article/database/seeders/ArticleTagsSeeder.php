<?php

namespace Modules\Article\database\seeders;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Article\app\Models\Article;
use Modules\Order\app\Models\Enrollment;

class ArticleTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = Article::get();
        
        foreach ($articles as $article) {
            $tags = Tag::inRandomOrder()->limit(5)->get();
            $article->articleTags()->sync($tags->pluck('id')->toArray());
        }
    }
}