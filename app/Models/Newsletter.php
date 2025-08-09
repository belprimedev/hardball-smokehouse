<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'status',
        'source'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function rules()
    {
        return [
            'email' => 'required|email|unique:newsletters,email',
        ];
    }

    public static function updateRules($id)
    {
        return [
            'email' => 'required|email|unique:newsletters,email,' . $id,
        ];
    }
}
