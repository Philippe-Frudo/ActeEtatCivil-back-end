<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

class CommuneController extends Controller
{

    protected $commune;

    public function __construct()
    {
        $this->commune = new District();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->commune->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response = $this->commune->create($request->all());

        if (!$response) {
            return response()->json(['status' => false, 'message' => "Erreur lors de l'ajout"], 404);
        }
        return response()->json(['status' => true, 'Une nouvelle commune a été ajouté'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $commune = $this->commune->find($id);
        if (!$commune) {
            return response()->json(['status' => false, 'message' => "Cette commune n'existe pas"], 404);
        }

        return response()->json($commune);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $commune = $this->commune->find($id);
        if (!$commune) {
            return response()->json(['status' => false, 'message' => "Cette commune n'existe pas"], 404);
        }
 
        $response = $commune->update($request->all());
        if (!$response) {
            return response()->json(['status' => false, 'message' => "Cette commune n'existe pas"], 404);
        }
        return response()->json(['status' => true, 'message' => "Modification reussi"], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $commune = $this->commune->find($id);
        if (!$commune) {
            return response()->json(['status' => false, 'message' => "Ce commune n'existe pas"], 404);
        }

        $response = $commune->delete();
        if (!$response) {
            return response()->json(['status' => false, 'message' => "Un erreur s'est produit lors de la suppression"], 500);
        }
        return response()->json(['status' => false, 'message' => "Suppression reuissi"], 200);
    }
}
