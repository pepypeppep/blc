<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CourseParticipant extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'user_id'];
}
