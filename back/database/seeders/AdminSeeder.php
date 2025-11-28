<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Exécute le seeder pour créer un administrateur par défaut.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['name' => 'Administrateur'],
            ['description' => 'Accès complet au système']
        );


        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'fullname' => 'Administrateur Principal',
                'email' => 'ulrichlaly1@gmail.com',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole->id,
            ]);

            $this->command->info('✅ Administrateur par défaut créé avec succès.');
        } else {
            $this->command->warn('⚠️ L’administrateur par défaut existe déjà.');
        }
    }
}
