<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FootballMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_team_id',
        'away_team_id',
        'home_team_score',
        'away_team_score',
        'total_match_score',
        'match_date',
    ];

    protected $casts = [
        'match_date' => 'datetime',
    ];

    public function homeTeam(): HasOne
    {
        return $this->hasOne(Team::class, 'id', 'home_team_id');
    }

    public function awayTeam(): HasOne
    {
        return $this->hasOne(Team::class, 'id', 'away_team_id');
    }
}