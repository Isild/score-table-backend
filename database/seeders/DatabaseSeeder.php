<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\FootballMatch;
use App\Models\Team;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // teams seed
        $team1 = Team::factory()->create(['name' => 'Mexico']);
        $team2 = Team::factory()->create(['name' => 'Canada']);
        $team3 = Team::factory()->create(['name' => 'Spain']);
        $team4 = Team::factory()->create(['name' => 'Brazil']);
        $team5 = Team::factory()->create(['name' => 'Germany']);
        $team6 = Team::factory()->create(['name' => 'France']);
        $team7 = Team::factory()->create(['name' => 'Uruguay']);
        $team8 = Team::factory()->create(['name' => 'Italy']);
        $team9 = Team::factory()->create(['name' => 'Argentina']);
        $team10 = Team::factory()->create(['name' => 'Australia']);

        // matches seed
        FootballMatch::factory()->create([
            'home_team_id' => $team1->id,
            'away_team_id' => $team2->id,
            'home_team_score' => 0,
            'away_team_score' => 5,
            'total_match_score' => 5,
            'total_time' => '01:30:00',
        ]);
        FootballMatch::factory()->create([
            'home_team_id' => $team3->id,
            'away_team_id' => $team4->id,
            'home_team_score' => 10,
            'away_team_score' => 2,
            'total_match_score' => 12,
            'total_time' => '01:35:00',
        ]);
        FootballMatch::factory()->create([
            'home_team_id' => $team5->id,
            'away_team_id' => $team6->id,
            'home_team_score' => 2,
            'away_team_score' => 2,
            'total_match_score' => 4,
            'total_time' => '01:33:00',
        ]);
        FootballMatch::factory()->create([
            'home_team_id' => $team7->id,
            'away_team_id' => $team8->id,
            'home_team_score' => 6,
            'away_team_score' => 6,
            'total_match_score' => 12,
            'total_time' => '01:40:00',
        ]);
        FootballMatch::factory()->create([
            'home_team_id' => $team9->id,
            'away_team_id' => $team10->id,
            'home_team_score' => 3,
            'away_team_score' => 1,
            'total_match_score' => 4,
            'total_time' => '01:40:00',
        ]);
    }
}