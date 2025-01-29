<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function ($user) {
            Activity::factory(rand(10, 20))->create([
                'user_id' => $user->id
            ]);

            $user->total_points = Activity::where('user_id', $user->id)->sum('points');
            $user->save();
        });

        $this->updateRanks();
    }

    private function updateRanks()
    {
        $users = User::orderByDesc('total_points')->get();
        $rank = 1;
        $prevPoints = null;

        foreach ($users as $index => $user) {
            if ($prevPoints !== null && $user->total_points < $prevPoints) {
                $rank = $index + 1;
            }
            $user->rank = $rank;
            $user->save();
            $prevPoints = $user->total_points;
        }
    }
}
