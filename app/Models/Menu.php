<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'image_path', 'short_label', 'side_note', 'is_visible', 'is_available', 'is_featured', 'is_chef_special', 'category_id', 'image_path'];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }
}
