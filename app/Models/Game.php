<?php

namespace App\Models;

use App\Enums\GameStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'status'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'game_user', 'game_id', 'user_id');
    }

    protected function casts(): array
    {
        return [
            'status' => GameStatus::class,
        ];
    }
}
