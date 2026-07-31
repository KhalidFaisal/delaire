<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'subcategory_id',
        'banner_image',
        'order'
    ];

    public function subcategory()
    {
        return $this->belongsTo(Prosubcategory::class, 'subcategory_id');
    }
}
