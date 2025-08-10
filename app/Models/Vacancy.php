<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'requirements',
        'responsibilities',
        'location',
        'type',
        'department',
        'salary_min',
        'salary_max',
        'salary_type',
        'is_active',
        'application_deadline',
        'positions_available',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'application_deadline' => 'date',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFormattedSalaryAttribute()
    {
        if (!$this->salary_min && !$this->salary_max) {
            return 'Competitive';
        }

        $min = $this->salary_min ? '£' . number_format($this->salary_min, 2) : '';
        $max = $this->salary_max ? '£' . number_format($this->salary_max, 2) : '';

        if ($min && $max) {
            return $min . ' - ' . $max . ' ' . ucfirst($this->salary_type);
        }

        return ($min ?: $max) . ' ' . ucfirst($this->salary_type);
    }

    public function getIsExpiredAttribute()
    {
        if (!$this->application_deadline) {
            return false;
        }

        return $this->application_deadline->isPast();
    }
}
