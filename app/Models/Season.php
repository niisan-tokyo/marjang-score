<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
