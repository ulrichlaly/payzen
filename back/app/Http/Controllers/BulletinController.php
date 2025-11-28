<?php

namespace App\Http\Controllers;

use App\Models\Paie;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BulletinController extends Controller
{
    public function show($id)
    {
        $paie = Paie::with('collaborateur')->findOrFail($id);
        return view('bulletins.show', compact('paie'));
    }

    public function download($id)
    {
        $paie = Paie::with('collaborateur')->findOrFail($id);
        $pdf = Pdf::loadView('bulletins.pdf', compact('paie'));
        return $pdf->download('bulletin-' . $paie->collaborateur->nom . '-' . $paie->mois . '.pdf');
    }
}
