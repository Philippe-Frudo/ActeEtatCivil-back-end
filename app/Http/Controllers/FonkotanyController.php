<?php

namespace App\Http\Controllers;

use App\Models\Fonkotany;
use Illuminate\Http\Request;

class FonkotanyController extends Controller
{
    protected $fonkotany;

    public function __construct()
    {
        $this->fonkotany = new Fonkotany();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->fonkotany->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fonkotany = $this->fonkotany->create($request->all());
        if ($fonkotany) {
            return response()->json(['status' => false, 'message' => "Une erreur s\'produit lors de l'ajuot"], 500);
        }
        return response()->json(['status' => true, 'message' => "Une nouvelle fonkotany a été ajouté"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $fonkotany = $this->fonkotany->find($id);
        if (!$fonkotany) {
            return response()->json(['status' => false, 'message' => "Un fonkotany est introuvable"], 404);
        }
        return response()->json($fonkotany, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $fonkotany = $this->fonkotany->find($id);
        if (!$fonkotany) {
            return response()->json(['status' => false, 'Donnee introuvable'], 404);
        }

        $resp = $fonkotany->update($request->all());
        if (!$resp) {
            return response()->json(['status' => false, 'message' => "Une erruer s'est produit lors de l'ajout du fonkotany"], 500);
        }
        return response()->json(['status' => true, 'message' => "Modification reuissi"], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fonkotany = $this->fonkotany->find($id);
        if (!$fonkotany) {
            return response()->json(['status' => false, 'Ce fnkotany n\'existe pas reuissi'], 404);
        }
        $resp = $fonkotany->delete();
        if (!$resp) {
            return response()->json(['status' => false, 'message' => "Une erruer s'est produit lors de la suppression du fonkotany"], 500);
        }
        return response()->json(['status' => true, 'message' => "Suppression reuissi"], 200);
    }
}
