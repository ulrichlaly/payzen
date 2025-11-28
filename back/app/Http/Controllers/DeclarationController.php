<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use App\Models\Paie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeclarationController extends Controller
{
    public function index()
    {
        $declarations = Declaration::with('paie.collaborator.user')->get();
        return response()->json($declarations, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:sociale,fiscale',
            'paie_id' => 'required|exists:paies,id',
            'montant_total' => 'required|numeric|min:0',
            'date_declaration' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'details' => 'nullable|string',
        ]);

        // Générer une référence si non fournie
        if (empty($validated['reference'])) {
            $validated['reference'] = 'DECL-' . date('Y-m-d') . '-' . strtoupper(substr($validated['type'], 0, 3)) . '-' . rand(1000, 9999);
        }

        $validated['statut'] = 'en_attente';

        $declaration = Declaration::create($validated);

        return response()->json([
            'message' => 'Déclaration créée avec succès.',
            'data' => $declaration->load('paie')
        ], 201);
    }

    public function show($id)
    {
        $declaration = Declaration::with('paie.collaborator.user')->findOrFail($id);
        return response()->json($declaration, 200);
    }

    public function update(Request $request, $id)
    {
        $declaration = Declaration::findOrFail($id);

        $validated = $request->validate([
            'statut' => 'required|in:en_attente,soumise,validee,rejetee',
            'reference' => 'nullable|string|max:255',
            'details' => 'nullable|string',
        ]);

        $declaration->update($validated);

        return response()->json([
            'message' => 'Déclaration mise à jour avec succès.',
            'data' => $declaration
        ], 200);
    }

    public function destroy($id)
    {
        $declaration = Declaration::findOrFail($id);
        $declaration->delete();

        return response()->json([
            'message' => 'Déclaration supprimée avec succès.'
        ], 200);
    }

    public function generateAuto(Request $request)
    {
        DB::beginTransaction();

        try {
            $currentMonth = date('m');
            $currentYear = date('Y');

            // Récupérer toutes les paies du mois en cours
            $paies = Paie::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->get();

            $declarationsCreated = 0;

            foreach ($paies as $paie) {
                $salaireBase = $paie->salaire_base;

                $cnssTotal = $salaireBase * 0.20;
                Declaration::create([
                    'type' => 'sociale',
                    'paie_id' => $paie->id,
                    'montant_total' => $cnssTotal,
                    'date_declaration' => now(),
                    'reference' => 'CNSS-' . $currentYear . $currentMonth . '-' . $paie->id,
                    'statut' => 'en_attente'
                ]);

                $irTotal = ($salaireBase * 0.10) + ($salaireBase * 0.025);
                Declaration::create([
                    'type' => 'fiscale',
                    'paie_id' => $paie->id,
                    'montant_total' => $irTotal,
                    'date_declaration' => now(),
                    'reference' => 'IR-' . $currentYear . $currentMonth . '-' . $paie->id,
                    'statut' => 'en_attente'
                ]);

                $declarationsCreated += 2;
            }

            DB::commit();

            return response()->json([
                'message' => "$declarationsCreated déclarations générées automatiquement.",
                'count' => $declarationsCreated
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erreur lors de la génération automatique.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
