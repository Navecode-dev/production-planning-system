<?php

namespace App\Http\Controllers;
use App\Models\Store;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Store::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('stores.edit', $row->id) . '" class="edit btn btn-primary btn-sm">Ubah</a>';
                    $btn .= ' <form action="' . route('stores.destroy', $row->id) . '" method="POST" style="display:inline">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm">Hapus</button></form>';
                    return $btn;
                })
                ->addColumn('input_score', function($row){
                    return '<a href="'.route('stores.scores.edit', $row->id).'" class="btn btn-info btn-sm">Input Skor</a>';
                })
                ->rawColumns(['action', 'input_score'])
                ->make(true);
        }
        return view('stores.index');
    }

    public function create()
    {
        return view('stores.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'sales_area' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'person_in_charge' => 'required|string|max:255',
        ]);
        Store::create($validatedData);
        return redirect()->route('stores.index')->with('success', 'Toko berhasil dibuat.');
    }

    public function show(Store $store)
    {
        return view('stores.show', compact('store'));
    }

    public function edit(Store $store)
    {
        return view('stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'sales_area' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'person_in_charge' => 'required|string|max:255',
        ]);
        $store->update($validatedData);
        return redirect()->route('stores.index')->with('success', 'Toko berhasil diperbarui.');
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return redirect()->route('stores.index')->with('success', 'Toko berhasil dihapus.');
    }
}