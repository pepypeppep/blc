<?php

namespace Modules\CertificateRecognition\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CertificateRecognition\Database\factories\CertificateRecognitionMaterialsFactory;

class CertificateRecognitionMaterials extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];
    protected $guarded = ['id'];

    // protected static function newFactory(): CertificateRecognitionMaterialsFactory
    // {
    //     //return CertificateRecognitionMaterialsFactory::new();
    // }
}
