<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category', // keuangan, sop_produksi, quality_control, branding, kemasan, marketing, administrasi
        'file_type',
        'file_url',
        'file_size_text',
        'summary',
        'download_count',
    ];
}
