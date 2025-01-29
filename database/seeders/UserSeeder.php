<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Activity;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::factory(20)->create()->each(function ($user) {
            for ($i = 0; $i < rand(10, 20); $i++) {
                Activity::create([
                    'user_id' => $user->id,
                    'name' => 'Activity ' . $i,
                    'points' => 20,
                    'performed_at' => now()->subDays(rand(0, 30)) // Add some variety in dates
                ]);
            }
        });
    }
}
