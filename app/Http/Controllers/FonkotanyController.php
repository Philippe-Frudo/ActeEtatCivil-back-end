<?php

namespace App\Http\Controllers;

use App\Models\Commune;
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
        $fonkotanyData = $this->fonkotany->join('commune', 'fonkotany.id_commune', '=', 'commune.id_commune')
            ->select('fonkotany.*', 'commune.code_commune')
            ->get();
        return $fonkotanyData;
    }


    
    public function addAllfonkotany(Request $request)
    {
        // return $request;
        // Décodage du JSON reçu dans le corps de la requête
        $requests = json_decode($request->getContent(), true);

        // Initialisation d'un tableau pour stocker les fonkotanys existants
        $fonkotanyExist = [];
        $i = 0;
        // Parcours de chaque fonkotany dans la requête
        foreach ($requests as $fonkotany) {

            // Vérification si le fonkotany existe déjà dans la base de données
            $foundFonkotany = $this->fonkotany->where('code_fonkotany', $fonkotany['code_fonkotany'])->first();

            // Vérification si le code de la commune est valide et existe dans la base de données et retoirne sa valeur grace a la methode pluck('code_commune') 
            $codeFonkotanyWithCommune = Commune::where('code_commune', $fonkotany['code_commune'])->pluck('code_commune')->first();

            // Si le fonkotany n'existe pas et que le code de la commune est valide
            if (!$foundFonkotany && $codeFonkotanyWithCommune) {
                // Création du nouveau fonkotany
                $this->fonkotany->create([
                    'code_fonkotany' => $fonkotany['code_fonkotany'],
                    'nom_fonkotany' => $fonkotany['nom_fonkotany'],
                    'id_commune' => commune::where('code_commune', $fonkotany['code_commune'])->pluck('id_commune')->first() //Retourne l'Identifiant de commune
                ]);
            } else {
                // Stockage des informations sur les fonkotanys existants ou avec une commune invalide
                $fonkotanyExist[$i] = $fonkotany['code_fonkotany'] . ', ' . $fonkotany['nom_fonkotany'] . ', code_commune:' . $fonkotany['code_commune'] . '  ';
                $i++;
            }
        }

        // Retourne une réponse JSON avec le statut et les fonkotanys rejetés
        return response()->json(['status' => !empty($fonkotanyExist), 'message' => 'ce(e) fonkotany est(sont) deja existe', 'rejeter' => $fonkotanyExist]);
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
