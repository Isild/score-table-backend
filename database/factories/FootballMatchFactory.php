<?php

namespace Database\Factories;

use App\Models\MatchModel;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FootballMatch>
 */
class FootballMatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $homeTeamScore = $this->faker->numberBetween(0, 10);
        $awayTeamScore = $this->faker->numberBetween(0, 10);

        return [
            'home_team_id' => Team::factory()->create()->id,
            'away_team_id' => Team::factory()->create()->id,
            'home_team_score' => $homeTeamScore,
            'away_team_score' => $awayTeamScore,
            'total_match_score' => $homeTeamScore + $awayTeamScore,
            'match_date' => $this->faker->dateTime(),
            'total_time' => $this->faker->time(),
        ];
    }
}