<?php

namespace App\Http\Controllers;

use App\Models\Acte;
use Illuminate\Http\Request;

class ActeController extends Controller
{

    protected $acte;

    public function __construct()
    {
        $this->acte = new Acte();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $acte = $this->acte->all();
        if (!$acte) {
            return response()->json(['status' => false, 'message' => "Objet vide"], 404);
        }

        return $acte;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $acte = $this->acte->create($request->all());
        if (!$acte) {
            return response()->json(['status' => false, 'message' => "Un erreur s'est produit lors de l'ajout"], 404);
        }
        return response()->json(['status' => true, 'message' => "Une nouvelle acte a été ajouté"], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $acte = $this->acte->find($id);
        if (!$acte) {
            return response()->json(['status' => false, 'message' => "Le acte est introuvable"], 500);
        }
        return response()->json($acte, 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $acte = $this->acte->find($id);

        if (!$acte) {
            return response()->json(['status' => true, 'message' => "Acte introuvable"], 500);
        }

        $resp = $acte->update($request->all());

        if (!$resp) {
            return response()->json(['status' => false, 'message' => "Une erruer s'est produit lors de l'ajout du acte"], 500);
        }
        return response()->json(['status' => true, 'message' => "Modification reuissi"], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $acte = $this->acte->find($id);
        if (!$acte) {
            return response()->json(['status' => false, 'message' => "Acte introuvable"], 500);
        }

        $resp = $acte->delete();
        if (!$resp) {
            return response()->json(['status' => false, 'message' => "Une erruer s'est produit lors de la suppression du acte"], 500);
        }

        return response()->json(['status' => true, 'message' => "Suppression reuissi"], 200);
    }
}
