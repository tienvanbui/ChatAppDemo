<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Nguyen Quynh',
            'email' => 'nguyen_quynh@gmail.com',
        ]);

        User::factory()->create([
            'name' => 'Bui Can',
            'email' => 'bui_can@gmail.com',
        ]);

        User::factory()->create([
            'name' => 'Nguyen Huy',
            'email' => 'nguyen_huy@gmail.com',
        ]);

        for ($i = 1; $i <=  20; $i++) {
            User::factory()->create([
                'name' => 'User' . " $i",
                'email' => "user_$i@gmail.com",
            ]);
        }
    }
}
