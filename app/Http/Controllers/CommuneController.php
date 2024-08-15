<?php

namespace App\Http\Controllers;

use App\Models\commune;
use App\Models\District;
use Illuminate\Http\Request;

class CommuneController extends Controller
{
    protected $commune;

    public function __construct()
    {
        $this->commune = new commune();
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $communeData = $this->commune->join('district', 'district.id_district', '=', 'commune.id_district')
            ->select('commune.id_commune', 'commune.code_commune', 'commune.nom_commune', 'district.code_district')
            ->get();
        return $communeData;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $foundCommune = $this->commune->where('code_commune', $request['code_commune'])->first();
        if (!$foundCommune) {

            $response = $this->commune->create($request->all());

            if (!$response) {
                return response()->json(['status' => false, 'message' => "Erreur lors de l'ajout"]);
            }
            return response()->json(['status' => true, "message" => 'Une nouvelle commune a été ajouté']);
        }
        return response()->json(['status' => false, "message" => 'Cette commune existe deja dans la base de donnee']);
    }


    public function addAllcommune(Request $request)
    {
        // Décodage du JSON reçu dans le corps de la requête
        $requests = json_decode($request->getContent(), true);

        // Initialisation d'un tableau pour stocker les communes existants
        $communeExist = [];
        $i = 0;
        // Parcours de chaque commune dans la requête
        foreach ($requests as $commune) {

            // Vérification si le commune existe déjà dans la base de données
            $foundCommune = $this->commune->where('code_commune', $commune['code_commune'])->first();

            // Vérification si le code de la région est valide et existe dans la base de données et retoirne sa valeur grace a la methode pluck('code_district') 
            $codeCommuneWithDistrict = District::where('code_district', $commune['code_district'])->pluck('code_district')->first();

            // Si le commune n'existe pas et que le code de la région est valide
            if (!$foundCommune && ($codeCommuneWithDistrict === $commune["code_district"])) {
                // Création du nouveau commune
                $this->commune->create([
                    'code_commune' => $commune['code_commune'],
                    'nom_commune' => $commune['nom_commune'],
                    'id_district' => district::where('code_district', $commune['code_district'])->pluck('id_district')->first() //Retourne l'Identifiant de district
                ]);
            } else {
                // Stockage des informations sur les communes existants ou avec une région invalide
                $communeExist[$i] = $commune['code_commune'] . ', ' . $commune['nom_commune'] . ', code_district:' . $commune['code_district'];
                $i++;
            }
        }

        // Retourne une réponse JSON avec le statut et les communes rejetés
        return response()->json(['status' => !empty($communeExist), 'rejeter' => $communeExist]);
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

        // $commune = $this->commune->where("id_commune", $id)->where("code_commune", $request->code_commune)->first();
        $commune = $this->commune->find($id);
        if (!$commune) {
            return response()->json(['status' => false, 'message' => "Cette commune est introuvable"], 404);
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
            return "Ce commune n'existe pas";
        }

        $response = $commune->delete();
        if (!$response) {
            return "Un erreur s'est produit lors de la suppression";
        }
        return "Suppression reuissi";
    }
}
