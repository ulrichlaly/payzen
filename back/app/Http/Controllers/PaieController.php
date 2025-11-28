<?php

namespace App\Http\Controllers;

use App\Models\Paie;
use Illuminate\Http\Request;

class PaieController extends Controller
{

    public function index()
    {
        $paies = Paie::with('collaborator.user')->get();
        return response()->json($paies, 200);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'collaborator_id' => 'required|exists:collaborators,id',
            'mois' => 'required|string',
            'annee' => 'required|numeric',
            'salaire_base' => 'required|numeric',
            'prime' => 'nullable|numeric',
            'indemnite' => 'nullable|numeric',
            'retenue' => 'nullable|numeric',
            'commentaire' => 'nullable|string',
        ]);


        $validated['net_a_payer'] =
            ($validated['salaire_base'] + ($validated['prime'] ?? 0) + ($validated['indemnite'] ?? 0))
            - ($validated['retenue'] ?? 0);

        $paie = Paie::create($validated);

        return response()->json([
            'message' => 'Fiche de paie créée avec succès.',
            'data' => $paie
        ], 201);
    }


    public function show($id)
    {
        $paie = Paie::with('collaborator')->find($id);

        if (!$paie) {
            return response()->json(['message' => 'Fiche de paie introuvable.'], 404);
        }

        return response()->json($paie, 200);
    }


    public function update(Request $request, $id)
    {
        $paie = Paie::find($id);

        if (!$paie) {
            return response()->json(['message' => 'Fiche de paie introuvable.'], 404);
        }

        $validated = $request->validate([
            'salaire_base' => 'nullable|numeric',
            'prime' => 'nullable|numeric',
            'retenue' => 'nullable|numeric',
            'periode' => 'nullable|string',
        ]);

        $paie->update($validated);

        $paie->net_a_payer = ($paie->salaire_base + ($paie->prime ?? 0)) - ($paie->retenue ?? 0);
        $paie->save();

        return response()->json([
            'message' => 'Fiche de paie mise à jour avec succès.',
            'data' => $paie
        ], 200);
    }


    public function destroy($id)
    {
        $paie = Paie::find($id);

        if (!$paie) {
            return response()->json(['message' => 'Fiche de paie introuvable.'], 404);
        }

        $paie->delete();

        return response()->json(['message' => 'Fiche de paie supprimée avec succès.'], 200);
    }

    public function historique($collaborator_id)
    {
        $paies = Paie::where('collaborator_id', $collaborator_id)->get();

        return response()->json([
            'historique' => $paies
        ], 200);
    }

    public function bulletin($id)
    {
        $paie = Paie::with('collaborator.user')->find($id);

        if (!$paie) {
            return response()->json(['message' => 'Fiche de paie introuvable.'], 404);
        }

        return response()->json([
            'paie' => $paie,
            'collaborator' => $paie->collaborator,
            'user' => $paie->collaborator->user
        ], 200);
    }
}
