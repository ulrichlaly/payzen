<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json(Role::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Rôle créé avec succès',
            'data' => $role,
        ], 201);
    }


    public function show($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255|unique:roles,name,' . $id,
            'description' => 'nullable|string|max:255',
        ]);

        $role->update($request->only('name', 'description'));

        return response()->json([
            'message' => 'Rôle mis à jour avec succès',
            'data' => $role,
        ]);
    }


    public function destroy($id)
    {
        Role::destroy($id);
        return response()->json(['message' => 'Rôle supprimé avec succès']);
    }
}
