<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Battle extends Model
{
    use HasFactory;

    protected $fillable = [
        'share_url',
        'comment'
    ];

    /**
     * 試合に参加したユーザー
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(BattleUser::class)
            ->withPivot(['score', 'start_position', 'rank_point'])
            ->orderByPivot('start_position');
    }

    /**
     * どのシーズンの試合だったか
     *
     * @return BelongsTo
     */
    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }
}
