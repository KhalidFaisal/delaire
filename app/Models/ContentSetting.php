<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'website_shutdown',
        'slider_active',
        'feature_content_active',
        'testimonial_active',
        'banner_active',
    ];
}
