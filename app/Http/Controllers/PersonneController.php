<?php

namespace App\Http\Controllers;

use App\Models\Personne;
use Illuminate\Http\Request;

class PersonneController extends Controller
{

    protected $personne;

    public function __construct()
    {
        $this->personne = new Personne();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->personne->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $personne = $this->personne->create($request->all());
        if (!$personne) {
            return response()->json(['status' => false, 'message' => "Donner introuvable"], 404);
        }
        return response()->json($personne, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $personne = $this->personne->find($id);
        if (!$personne) {
            return response()->json(['status' => false, 'message' => "Le personne est introuvable"]);
        }
        return response()->json($personne, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $personne = $this->personne->find($id);
        if ($personne) {
            return response()->json(['status' => false, 'message' => " donne introuvablee "], 404);
        }
        $resp = $personne->update($request->all());
        if (!$resp) {
            return response()->json(['status' => false, 'Une erreur s\'est produit'], 200);
        }
        return response()->json(['status' => true, 'message' => "Modification reuissi"], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $personne = $this->personne->find($id);
        if ($personne) {
            return response()->json(['status' => false, 'Cette f0nkotany n\'eSxiste pas'], 404);
        }
        $resp = $personne->delete();
        if (!$resp) {
            return response()->json(['status' => false, 'message' => "Une erruer s'est produit lors de la suppression du personne"], 500);
        }
        return response()->json(['status' => true, 'message' => "Suppression reuissi"], 200);
    }
}
