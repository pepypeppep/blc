<?php

namespace Modules\CertificateRecognition\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CertificateRecognition\Database\factories\PersonalCertificateRecognitionFactory;

class PersonalCertificateRecognition extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
