<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Professional extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'name', 'slug', 'phone', 'city',
        'description', 'skills', 'languages', 'travel_cities',
        'availability', 'avatar', 'is_verified', 'is_featured',
        'average_rating', 'total_reviews', 'total_views',
        'total_whatsapp_clicks', 'total_calls',
    ];

    protected $casts = [
        'skills'       => 'array',
        'languages'    => 'array',
        'travel_cities'=> 'array',
        'is_verified'  => 'boolean',
        'is_featured'  => 'boolean',
        'average_rating' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function trackings(): HasMany
    {
        return $this->hasMany(Tracking::class);
    }

    public function getWhatsAppUrl(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '212' . substr($phone, 1);
        }
        return "https://wa.me/{$phone}";
    }

    public function updateAverageRating(): void
    {
        $avg   = $this->reviews()->avg('rating') ?? 0;
        $count = $this->reviews()->count();
        $this->update([
            'average_rating' => round($avg, 2),
            'total_reviews'  => $count,
        ]);
    }

    public function getStatusBadge(): array
    {
        return match($this->availability) {
            'available' => ['class' => 'bg-green-100 text-green-700', 'label' => 'Disponible', 'dot' => 'bg-green-500'],
            'busy'      => ['class' => 'bg-yellow-100 text-yellow-700', 'label' => 'Occupé', 'dot' => 'bg-yellow-500'],
            'closed'    => ['class' => 'bg-red-100 text-red-700', 'label' => 'Fermé', 'dot' => 'bg-red-500'],
            default     => ['class' => 'bg-gray-100 text-gray-700', 'label' => 'Inconnu', 'dot' => 'bg-gray-500'],
        };
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name) . '-' . Str::random(6);
            }
        });
    }
}
