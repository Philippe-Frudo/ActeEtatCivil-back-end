<?php

namespace App\Http\Controllers;

use App\Models\Travail;
use Illuminate\Http\Request;

class TravailController extends Controller
{

    protected $travail;

    public function __construct()
    {
        $this->travail = new Travail();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->travail->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $travail = $this->travail->create($request->all());
        if (!$travail) {
            return response()->json(['status' => false, 'message' => "Donner introuvable"], 404);
        }
        return response()->json(['status' => false, 'message' => "Une nouvelle travail a été ajouté"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $travail = $this->travail->find($id);
        if ($travail) {
            return response()->json($travail, 200);
        }
        return response()->json(['status' => false, 'message' => "Un travail est introuvable"]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $travail = $this->travail->find($id);
        if (!$travail) {
            return response()->json(['status' => false, 'Une erreur s\'est produit'], 404);
        }
        $resp = $travail->update($request->all());
        if (!$resp) {
            return response()->json(['status' => false, 'message' => "Une erruer s'est produit lors de l'ajout du travail"], 404);
        }
        return response()->json(['status' => true, 'message' => "Modification reuissi"], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $travail = $this->travail->find($id);
        if (!$travail) {
            return response()->json(['status' => false, 'message' => "Donner introuvable"], 404);
        }
        $resp = $travail->delete();
        if (!$resp) {
            return response()->json(['status' => false, 'Cette fnkotany n\'existe pas '], 500);
        }
        return response()->json(['status' => true, 'message' => "Suppression reuissi"], 201);
    }
}
