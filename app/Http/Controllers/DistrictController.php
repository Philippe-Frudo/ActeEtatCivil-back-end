<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DistrictController extends Controller
{

    protected $district;

    public function __construct()
    {
        $this->district = new District();
    }


    /**
     * Display a listing of the resodeurce.
     */
    public function index()
    {
        $districtData = $this->district->join('region', 'region.id_region', '=', 'district.id_region')
            ->select('district.id_district', 'district.code_district', 'district.nom_district', 'region.code_region')
            ->get();
        return $districtData;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $district = $this->district->where('code_district', $request->code_district)->first();


        if (!$district) {

            $response = $this->district->create($request->all());
            if (!$response) {
                return response()->json(['status' => false, 'message' => "une erreur s'est produit lors de l'ajout"]);
            }
            return response()->json(['status' => true, 'message' => 'Une nouvelle region a été creé'], 200);
        }

        return response()->json(['status' => false, 'message' => 'Ce code district existe déjà dans la base.']);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function addAlldistrict(Request $request)
    {
        // Décodage du JSON reçu dans le corps de la requête
        $requests = json_decode($request->getContent(), true);

        // Initialisation d'un tableau pour stocker les districts existants
        $districtExist = [];
        $i = 0;
        // Parcours de chaque district dans la requête
        foreach ($requests as $district) {

            // Vérification si le district existe déjà dans la base de données
            $foundDistrict = $this->district->where('code_district', $district['code_district'])->first();

            // Vérification si le code de la région est valide et existe dans la base de données
            $codeDistrictWithRegion = Region::where('code_region', $district['code_region'])->pluck('code_region')->first();
            // Si le district n'existe pas et que le code de la région est valide
            if (!$foundDistrict && ($codeDistrictWithRegion === $district["code_region"])) {
                // Création du nouveau district
                $this->district->create([
                    'code_district' => $district['code_district'],
                    'nom_district' => $district['nom_district'],
                    'id_region' => Region::where('code_region', $district['code_region'])->pluck('id_region')->first()
                ]);
            } else {
                // Stockage des informations sur les districts existants ou avec une région invalide
                $districtExist[$i] = $district['code_district'] . ' ' . $district['nom_district'] . '  region:' . $district['code_region'] . ', ';
                $i++;
            }
        }

        // Retourne une réponse JSON avec le statut et les districts rejetés
        return response()->json(['status' => !empty($districtExist), 'rejeter' => $districtExist]);
    }


    public function show(string $id)
    {
        $district = $this->district->find($id);
        if (!$district) {
            return response()->json(['status' => false, 'message' => "Cette district n'existe pas"], 404);
        }

        return response()->json($district, 200);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $district = $this->district->where("id_district", $request->id_district)->where("code_district", $request->code_district)->first();
        $district = $this->district->find($id);
        if (!$district) {
            return response()->json(['status' => false, 'message' => "Ce district est introuvable"], 404);
        }

        $response = $district->update($request->all());
        if (!$response) {
            return response()->json(['status' => false, 'message' => "Erreur de la modification"], 500);
        }

        return response()->json(['status' => true, 'message' => 'Modification reuissi'], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $district = $this->district->find($id);
        if (!$district) {
            return response()->json(['status' => false, 'message' => "Ce district n'existe pas"], 404);
        }
        $resp = $district->delete();
        if (!$resp) {
            return response()->json(['status' => true, 'Erreur lors de la suppression reuissi'], 500);
        }
        return response()->json(['status' => true, 'Suppression reuissi'], 200);
    }
}
