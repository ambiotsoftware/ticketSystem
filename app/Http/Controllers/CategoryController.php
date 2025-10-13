<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('tickets')
            ->orderBy('nombre')
            ->paginate(15);
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:categories',
            'descripcion' => 'nullable|string',
            'color' => 'required|string|size:7|regex:/^#[0-9a-fA-F]{6}$/',
            'activa' => 'boolean'
        ]);

        $validated['activa'] = $request->has('activa');

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->loadCount('tickets');
        $tickets = $category->tickets()->with(['assignedUser', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('categories.show', compact('category', 'tickets'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:categories,nombre,' . $category->id,
            'descripcion' => 'nullable|string',
            'color' => 'required|string|size:7|regex:/^#[0-9a-fA-F]{6}$/',
            'activa' => 'boolean'
        ]);

        $validated['activa'] = $request->has('activa');

        $category->update($validated);

        return redirect()->route('categories.show', $category)
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->tickets()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'No se puede eliminar una categoría que tiene tickets asociados.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }
}
