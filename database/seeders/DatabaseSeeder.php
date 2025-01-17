<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (User::query()->where('email', 'admin@articlapp')->doesntExist()) {
            User::factory()->create([
                'first_name' => 'articlapp',
                'last_name' => '@admin',
                'email' => 'admin@article.app',
            ]);
        }

        foreach(range(0, 9) as $i) {
            Tag::query()->create([
                'name' => fake()->word(),
            ]);
        }
    }
}
