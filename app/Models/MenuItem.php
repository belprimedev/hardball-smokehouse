<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image_path',
        'short_label',
        'side_note',
        'is_featured',
        'is_chef_special',
        'is_available',
        'is_visible'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'is_chef_special' => 'boolean',
        'is_available' => 'boolean',
        'is_visible' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * Get the category that owns the menu item.
     */
    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }
} 