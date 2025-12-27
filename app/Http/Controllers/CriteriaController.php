<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CriteriaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Criteria::select(
                [
                    'id',
                    'name',
                    'weight',
                    'type',
                    'category',
                    'created_at',
                    'updated_at',
                ]
            )->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn(
                    'action',
                    function ($row) {
                        $btn = '<a href="' . route(
                            'criteria.edit',
                            $row->id
                        ) . '" class="edit btn btn-primary btn-sm">Edit</a>';
                        $btn .= ' <form action="' . route(
                            'criteria.destroy',
                            $row->id
                        ) . '" method="POST" style="display:inline">' . csrf_field() . method_field(
                            'DELETE'
                        ) . '<button type="submit" class="btn btn-danger btn-sm">Delete</button></form>';

                        return $btn;
                    }
                )
                ->rawColumns(["action"])
                ->make(true);
        }

        return view("criteria.index");
    }

    public function create()
    {
        return view("criteria.create");
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
                "name" => "required|string|max:255",
                "weight" => "required|numeric|min:0|max:1",
                "type" => "required|in:benefit,cost",
                "category" => "required|in:product,store",
            ]);

        if (!$this->validateTotalWeight($validatedData['category'], $validatedData['weight'])) {
            return back()->withInput()->withErrors(['weight' => 'Total bobot untuk kategori ini melebihi 1.']);
        }
        
        Criteria::create($request->all());

        return redirect()->route("criteria.index")
            ->with("success", "Kriteria berhasil dibuat.");
    }

    public function show(string $id)
    {
        $criteria = Criteria::find($id);

        return view("criteria.show", compact("criteria"));
    }

    public function edit(string $id)
    {
        $criteria = Criteria::find($id);

        return view("criteria.edit", compact("criteria"));
    }

    public function update(Request $request, string $id)
    {
        $criteria = Criteria::findOrFail($id);

        $validatedData = $request->validate([
                "name" => "required|string|max:255",
                "weight" => "required|numeric|min:0|max:1",
                "type" => "required|in:benefit,cost",
                "category" => "required|in:product,store",
            ]);

        if ($criteria->category != $validatedData['category'] || $criteria->weight != $validatedData['weight']) {
            if (!$this->validateTotalWeight($validatedData['category'], $validatedData['weight'], $id)) {
                return back()->withInput()->withErrors(['weight' => 'Total bobot untuk kategori ini melebihi 1.']);
            }
        }

        $criteria = Criteria::find($id);
        $criteria->update($request->all());

        return redirect()->route("criteria.index")
            ->with("success", "Kriteria berhasil diperbarui.");
    }

    private function validateTotalWeight(string $category, float $weight, ?int $excludedId = null): bool
    {
        $query = Criteria::where('category', $category);

        if ($excludedId !== null) {
            $query->where('id', '!=', $excludedId);
        }

        $totalWeight = $query->sum('weight') + $weight;

        return $totalWeight <= 1;
    }

    public function destroy(string $id)
    {
        Criteria::find($id)->delete();

        return redirect()->route("criteria.index")
            ->with("success", "Kriteria berhasil dihapus.");
    }
}
