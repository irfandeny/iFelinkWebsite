<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes ;

    protected $fillable = [
        'name',
        'slug',
        'provider',
        'quota',
        'price',
        'validity_days',
        'description',
        'is_active',
        'image_path',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($package) {
            if (empty($package->slug) && !empty($package->name)) {
                $package->slug = static::generateSlug($package->name);
            }
        });
    }

    protected static function generateSlug($name)
    {
        $slug = str()->slug($name);
        $original = $slug;
        $count = 2;
        while (static::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }
        return $slug;
    }
}
