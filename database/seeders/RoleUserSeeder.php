<?php

namespace Database\Seeders;

use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
             // 1. Buscar todas as roles existentes
        $roles = Role::all();

        // Se não houver roles, não há o que fazer.
        if ($roles->isEmpty()) {
            $this->command->info('Nenhuma role encontrada. Pulando o RoleUserSeeder.');
            return;
        }

        // 2. Buscar todos os usuários e iterar sobre cada um
        User::all()->each(function ($user) use ($roles) {

            // 3. Pegar uma role aleatória da coleção de roles
            $randomRole = $roles->random();

            // 4. Anexar (attach) a role ao usuário na tabela pivô
            // O método attach() cria o registro na tabela 'role_user'
            $user->roles()->attach($randomRole->id);
        });

        $this->command->info('Roles aleatórias atribuídas aos usuários com sucesso!');

    }
}
