<?php

namespace Database\Seeders;

use App\Models\Users\User;
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
            'name' => 'lucas domingues',
            'email' => 'lucasdomingues@sp.senai.br',
            'password' => bcrypt('12345678'),
            'role_id' => 1,
            'status_id' => 1
        ]);
        User::factory(10)->create();
    }
}
