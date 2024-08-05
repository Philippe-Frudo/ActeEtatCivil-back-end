<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;

class RegionController extends Controller
{

    protected $region;

    public function __construct()
    {
        $this->region = new Region();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->region->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $region = $this->region->create($request->all());
        if (!$region) {
            return response()->json(['status' => false, 'message' => "Un erreur s'est produit lors d'enregistrement"], 500);
        }
        return response()->json(['status' => false, 'message' => "Une nouvelle region a été ajouté"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $region = $this->region->find($id);

        if (!$region) {
            return response()->json(['status' => false, 'message' => "Cette région n'existe pas"], 404);
        }
        return response()->json(['status' => true, 'message' => `Modification de la region {$region->code_region} est reussi`], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $region = $this->region->find($id);
        $region->update($request->all());
        return $region;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $region = $this->region->find($id);
        return $region->delete();
    }
}
