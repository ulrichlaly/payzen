<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Paie;
use App\Models\Declaration;
use App\Models\Conge;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $user = $request->user();

        $user->load('roles');

        $roleNames = $user->roles->pluck('name')->toArray();

        $primaryRole = !empty($roleNames) ? $roleNames[0] : 'Collaborateur';

        return response()->json([
            'id' => $user->id,
            'fullname' => $user->fullname,
            'email' => $user->email,
            'role' => $primaryRole,
            'roles' => $roleNames,
            'tel' => $user->tel,
        ]);
    }

    public function index()
    {
        $users = User::with('roles')->get();

        return response()->json([
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'tel' => $user->tel,
                    'roles' => $user->roles->pluck('name')->toArray(),
                    'created_at' => $user->created_at,
                ];
            })
        ]);
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();
        $user->load('roles');

        $stats = [
            'total_employees' => User::count(),
            'total_paies' => Paie::count(),
            'total_declarations' => Declaration::count(),
            'total_conges' => Conge::where('statut', 'en_attente')->count(),

            'paies_du_mois' => Paie::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'declarations_en_attente' => Declaration::where('statut', 'en_attente')->count(),
            'declarations_soumises' => Declaration::where('statut', 'soumise')->count(),
            'montant_total_mois' => Paie::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('net_a_payer'),
        ];

        return response()->json([
            'user' => [
                'fullname' => $user->fullname,
                'role' => $user->roles->pluck('name')->first() ?? 'Collaborateur',
                'roles' => $user->roles->pluck('name')->toArray(),
            ],
            'stats' => $stats,
        ]);
    }
}
