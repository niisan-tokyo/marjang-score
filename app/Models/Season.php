<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * シーズンをアクティブにする
     *
     * @return boolean
     */
    public function activate(): bool
    {
        self::query()->update(['active' => false]);
        $this->active = true;
        return $this->save();
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * 各試合
     *
     * @return HasMany
     */
    public function battles(): HasMany
    {
        return $this->hasMany(Battle::class);
    }
}
