<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prosubcategory extends Model
{
    use HasFactory;
    public function mainCategory()
    {
        return $this->belongsTo(Procategory::class, 'main_Cat', 'id');
    }
}
