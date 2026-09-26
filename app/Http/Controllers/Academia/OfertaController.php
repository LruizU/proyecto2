<?php

declare(strict_types=1);

namespace App\Http\Controllers\Academia;

use App\Http\Controllers\Controller;
use App\Models\Oferta;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    /**
     * Display a listing of the ofertas.
     */
    public function index()
    {
        $ofertas = Oferta::orderBy('fecha_inicio', 'desc')->paginate(15);
        return view('academia.ofertas.index', compact('ofertas'));
    }

    /**
     * Show the form for creating a new oferta.
     */
    public function create()
    {
        return view('academia.ofertas.create');
    }

    /**
     * Store a newly created oferta in storage.
     */
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
        return redirect()->route('academia.ofertas.index')
            ->with('success', 'Oferta creada correctamente.');
    }

    /**
     * Display the specified oferta.
     */
    public function show(Oferta $oferta)
    {
        return view('academia.ofertas.show', compact('oferta'));
    }

    /**
     * Show the form for editing the specified oferta.
     */
    public function edit(Oferta $oferta)
    {
        return view('academia.ofertas.edit', compact('oferta'));
    }

    /**
     * Update the specified oferta in storage.
     */
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
        return redirect()->route('academia.ofertas.index')
            ->with('success', 'Oferta actualizada correctamente.');
    }

    /**
     * Remove the specified oferta from storage.
     */
    public function destroy(Oferta $oferta)
    {
        $oferta->delete();
        return redirect()->route('academia.ofertas.index')
            ->with('success', 'Oferta eliminada.');
    }
}


declare(strict_types=1);

namespace App\Http\Controllers\Academia;

use App\Http\Controllers\Controller;
use App\Models\Oferta;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    /**
     * Display a listing of the ofertas.
     */
    public function index()
    {
        $ofertas = Oferta::orderBy('fecha_inicio', 'desc')->paginate(15);
        return view('academia.ofertas.index', compact('ofertas'));
    }

    /**
     * Show the form for creating a new oferta.
     */
    public function create()
    {
        return view('academia.ofertas.create');
    }

    /**
     * Store a newly created oferta in storage.
     */
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
        return redirect()->route('academia.ofertas.index')
            ->with('success', 'Oferta creada correctamente.');
    }

    /**
     * Display the specified oferta.
     */
    public function show(Oferta $oferta)
    {
        return view('academia.ofertas.show', compact('oferta'));
    }

    /**
     * Show the form for editing the specified oferta.
     */
    public function edit(Oferta $oferta)
    {
        return view('academia.ofertas.edit', compact('oferta'));
    }

    /**
     * Update the specified oferta in storage.
     */
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
        return redirect()->route('academia.ofertas.index')
            ->with('success', 'Oferta actualizada correctamente.');
    }

    /**
     * Remove the specified oferta from storage.
     */
    public function destroy(Oferta $oferta)
    {
        $oferta->delete();
        return redirect()->route('academia.ofertas.index')
            ->with('success', 'Oferta eliminada.');
    }
}


namespace App\Http\Controllers\Academia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
