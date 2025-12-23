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
        'type',
        'quota',
        'price',
        'validity_days',
        'description',
        'is_active',
        'image_path',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'validity_days' => 'integer',
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

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getProviderNameAttribute()
    {
        $providers = [
            'Telkomsel' => 'Telkomsel',
            'Indosat' => 'Indosat Ooredoo',
            'XL' => 'XL Axiata',
            'Tri' => '3 (Tri)',
            'Smartfren' => 'Smartfren',
            'Axis' => 'Axis',
        ];
        return $providers[$this->provider] ?? ucfirst($this->provider);
    }

    public function isAvailable(){
        return $this -> is_active == true;
    }
}
