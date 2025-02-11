<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Article\app\Models\Article;

class Tag extends Model
{
   use HasFactory;

   protected $fillable = ['name'];
   protected $hidden = array('pivot');


   public function articles(): BelongsToMany
   {
      return $this->belongsToMany(Article::class);
   }
}
