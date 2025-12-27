<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RawMaterialController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = RawMaterial::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('raw-materials.edit', $row->id) . '" class="edit btn btn-primary btn-sm">Ubah</a>';
                    $btn .= ' <form action="' . route('raw-materials.destroy', $row->id) . '" method="POST" style="display:inline">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm">Hapus</button></form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('raw_materials.index');
    }

    public function create()
    {
        return view('raw_materials.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price_per_pallet' => 'required|numeric|min:0',
            'storage_cost' => 'required|numeric|min:0',
            'price_per_sheet' => 'required|numeric|min:0',
        ]);

        RawMaterial::create($validatedData);

        return redirect()->route('raw-materials.index')->with('success', 'Bahan baku berhasil dibuat.');
    }

    public function show(RawMaterial $rawMaterial)
    {
        return view('raw_materials.show', compact('rawMaterial'));
    }

    public function edit(RawMaterial $rawMaterial)
    {
        return view('raw_materials.edit', compact('rawMaterial'));
    }

    public function update(Request $request, RawMaterial $rawMaterial)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price_per_pallet' => 'required|numeric|min:0',
            'storage_cost' => 'required|numeric|min:0',
            'price_per_sheet' => 'required|numeric|min:0',
        ]);

        $rawMaterial->update($validatedData);

        return redirect()->route('raw-materials.index')->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function destroy(RawMaterial $rawMaterial)
    {
        $rawMaterial->delete();
        return redirect()->route('raw-materials.index')->with('success', 'Bahan baku berhasil dihapus.');
    }
}