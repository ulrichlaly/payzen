<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Exécuter l’insertion des rôles de base.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin Général',
                'description' => 'Administrateur général du système avec tous les droits.',
            ],
            [
                'name' => 'RH',
                'description' => 'Responsable des ressources humaines (gestion du personnel, salaires, etc.)',
            ],
            [
                'name' => 'Comptable',
                'description' => 'Responsable de la gestion comptable et fiscale.',
            ],
            [
                'name' => 'Collaborateur',
                'description' => 'Employé standard du système.',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        echo "✅ Rôles de base ajoutés avec succès.\n";
    }
}
