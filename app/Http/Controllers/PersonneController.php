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
        return $this->personne->select('personne.*', 'travail.nom_travail')
            ->join('travail', 'travail.id_travail', '=', 'personne.id_travail')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $personne = $this->personne->create($request->all());
        if ($personne) {

            // Utilisation de latest (si la colonne est bien id_person)
            $idPerson = Personne::latest('id_person')->first()->id_person;
            if (!$idPerson) {
                return null;
            }

            return $idPerson;
        }

        return null;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
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
    public function update(Request $request, int $id)
    {
        return $request;
        $perso = $this->personne->find($id);
        if (!$perso) {
            return response()->json(['status' => false, 'message' => " donne introuvablee "], 404);
        }

        $resp = $perso->update(
            $request->all()
            // [
            //     'nom_person' => $request->nom_person,
            //     'prenom_person' => $request->nom_person,
            //     'sexe_person' => $request->nom_person,
            //     'adrs_person' => $request->nom_person,
            //     'nom_m' => $request->nom_person,
            //     'prenom_m' => $request->nom_person,
            //     'date_nais_m' => $request->nom_person,
            //     'lieu_nais_m' => $request->nom_person,
            //     'age_m' => $request->nom_person,
            //     'profession_m' => $request->nom_person,
            //     'adrs_m' => $request->nom_person,
            //     'nom_p' => $request->nom_person,
            //     'prenom_p' => $request->nom_person,
            //     'date_nais_p' => $request->nom_person,
            //     'lieu_nais_p' => $request->nom_person,
            //     'age_p' => $request->nom_person,
            //     'profession_p' => $request->nom_person,
            //     'adrs_p' => $request->nom_person,
            //     'id_travail' => $request->nom_person,
            // ]
        );
        if (!$resp) {
            return response()->json(['status' => false, 'Une erreur s\'est produit'], 404);
        }
        return response()->json(['status' => true, 'message' => "Modification reuissi"], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $perso = $this->personne->find($id);
        if (!$perso) {
            return response()->json(['status' => false, 'Cette personne n\'est pas trouvé'], 404);
        }

        $resp = $perso->delete();
        if (!$resp) {
            return response()->json(['status' => false, 'message' => "Une erruer s'est produit lors de la suppression du personne"], 500);
        }
        return response()->json(['status' => true, 'message' => "Suppression reuissi"], 200);
    }


    public function lastPersonne()
    {
        $lastPerson = $this->personne->orderBy('id_person', 'desc')->first();
        $lastIdPerson = $lastPerson ? $lastPerson->id_person : null;
        if ($lastIdPerson) {
            return $lastIdPerson;
        }
        return null;
    }
}
