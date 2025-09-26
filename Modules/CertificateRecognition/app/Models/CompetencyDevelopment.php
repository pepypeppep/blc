<?php

namespace Modules\CertificateRecognition\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CertificateRecognition\Database\factories\CompetencyDevelopmentFactory;

class CompetencyDevelopment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
