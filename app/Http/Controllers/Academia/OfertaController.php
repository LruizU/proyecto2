<?php

declare(strict_types=1);

namespace App\Http\Controllers\Academia;

use App\Http\Controllers\Controller;
use App\Models\Oferta;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    public function index()
    {
        $ofertas = Oferta::query()
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(15);

        return view('academia.ofertas.index', compact('ofertas'));
    }

    public function create()
    {
        return view('academia.ofertas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'costo' => 'required|numeric|min:0',
        ]);

        Oferta::create($data);

        return redirect()
            ->route('academia.ofertas.index')
            ->with('success', 'Oferta creada correctamente.');
    }

    public function show(Oferta $oferta)
    {
        return view('academia.ofertas.show', compact('oferta'));
    }

    public function edit(Oferta $oferta)
    {
        return view('academia.ofertas.edit', compact('oferta'));
    }

    public function update(Request $request, Oferta $oferta)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'costo' => 'required|numeric|min:0',
        ]);

        $oferta->update($data);

        return redirect()
            ->route('academia.ofertas.index')
            ->with('success', 'Oferta actualizada correctamente.');
    }

    public function destroy(Oferta $oferta)
    {
        $oferta->delete();

        return redirect()
            ->route('academia.ofertas.index')
            ->with('success', 'Oferta eliminada.');
    }
}
