<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicamento;

class MedicamentoController extends Controller
{

    public function index()
    {
        $medicamentos = Medicamento::all()->groupBy('categoria');
        return view('medicamentos.index', compact('medicamentos'));
    }

    public function create()
    {
        return view('medicamentos.create');
    }

    public function store(Request $request){
        $request->validate([
            'nombre' => 'required',
            'presentacion' => 'required',
            'cantidad' => 'required',
            'mg' => 'required',
            'categoria' => 'required',
            'disponibilidad' => 'required|numeric',
            'image_path' => 'nullable|image'
        ]);

        $medExistente = Medicamento::where('nombre', $request->nombre)
            ->where('mg', $request->mg)
            ->where('presentacion', $request->presentacion)
            ->where('categoria', $request->categoria)
            ->first();

        $image = null;
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path')->store('medicamentos', 'public');
        }

        if ($medExistente) {

            $medExistente->disponibilidad += $request->disponibilidad;

            if ($image) {
                $medExistente->image_path = $image;
            }

            $medExistente->save();

        } else {

            Medicamento::create([
                'nombre' => $request->nombre,
                'presentacion' => $request->presentacion,
                'cantidad' => $request->cantidad,
                'mg' => $request->mg,
                'categoria' => $request->categoria,
                'disponibilidad' => $request->disponibilidad,
                'image_path' => $image
            ]);
        }

        return redirect()->route('medicamentos.index');
    }

    public function guardar($id)
    {
        $med = Medicamento::findOrFail($id);
        $med->guardado = !$med->guardado;
        $med->save();

        return response()->json([
            'status'=>$med->guardado
        ]);
    }
    /* ============================= */
/* LISTADO PUBLICO (CATEGORIAS) */
/* ============================= */
public function listadoPublico()
{
    $medicamentos = Medicamento::where('disponibilidad', 'Disponible')->get();

    return view('medicamentos.index', compact('medicamentos'));
}


/* ============================= */
/* BUSCADOR PUBLICO */
/* ============================= */
    public function buscarPublico(Request $request)
    {
        $request->validate([
            'buscar' => 'required|string|max:255'
        ]);

        $query = $request->buscar;

        $medicamentos = Medicamento::where('disponibilidad', 'Disponible')
            ->where(function ($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                ->orWhere('categoria', 'like', "%{$query}%")
                ->orWhere('presentacion', 'like', "%{$query}%");
            })
            ->get();

        return view('medicamentos.index', compact('medicamentos'));
    }
}