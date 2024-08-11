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
        $resp = $this->travail->where('nom_travail', $request['nom_travail'])->first();

        if (!$resp) {
            $this->travail->create([
                'nom_travail' => $request['nom_travail']
            ]);
        } else {
            return response()->json(['status' => false, 'message' => "C'est travail est dejà existe"]);
        }
        return response()->json(['status' => true, 'message' => "Une nouvelle travail a été ajouté"], 201);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function addAlltravail(Request $request)
    {
        $requests = json_decode($request->getContent(), true); // decodage du JSON

        $travailExist = [];
        $i = 0;
        foreach ($requests as $travail) {

            $resp = $this->travail->where('nom_travail', $travail['nom_travail'])->first();
            if (!$resp) {
                $this->travail->create([
                    'nom_travail' => $travail['nom_travail']
                ]);
            } else {
                $travailExist[$i] = $travail['nom_travail'];
                $i++;
            }
        }
        return response()->json(['status' => !empty($travailExist), 'rejeter' => $travailExist]);
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
    public function update(Request $request)
    {
        // Afficher le contenu de la requête pour débogage
        logger()->info('Requête reçue pour mise à jour : ', $request->all());
        // Vérifier que l'ID est bien présent dans la requête
        if (!$request->has('id_travail')) {
            return response()->json(['status' => false, 'message' => 'ID de travail manquant'], 400);
        }

        // Rechercher l'enregistrement par son identifiant
        $travail = $this->travail->find($request->id_travail);

        // Vérifier si l'enregistrement est trouvé
        if (!$travail) {
            return response()->json(['status' => false, 'message' => 'Travail non trouvé'], 404);
        }

        // Mettre à jour le nom du travail
        $travail->update([
            'nom_travail' => $request->nom_travail
        ]);

        return response()->json(['status' => true, 'message' => 'Modification réussie'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $travail = $this->travail->find($id);
        if (!$travail) {
            return response()->json(['status' => false, 'message' => "travail introuvable"], 404);
        }
        $travail->delete();
        return response()->json(['status' => true, 'message' => "Suppression reuissi"], 200);
    }
}
