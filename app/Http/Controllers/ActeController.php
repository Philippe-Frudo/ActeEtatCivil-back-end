<?php

namespace App\Http\Controllers;

use App\Models\Acte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActeController extends Controller
{

    protected $acte;

    public function __construct()
    {
        $this->acte = new Acte();
    }


    public function getTypesActe()
    {
        $typesDataActe = DB::table('type')->get();
        return  $typesDataActe;
    }

    /**
     * Display a listing of the resource.
     */
    public function getActes(Request $request)
    {
        $id_fonkotany = $request->id_fonkotany;  // Exemple: peut être null ou 2, etc.
        $id_commune = $request->id_commune;
        $id_region = $request->id_region;

        $result = $this->acte->query()
            ->select(
                'personnes.nom_person',
                'personnes.prenom_person',
                'personnes.sexe_person',
                'actes.date_acte',
                'actes.id_acte',
                'actes.id_type',
                'actes.date_enreg',
                'actes.id_enreg',
                'actes.nom_temoin',
                'actes.prenom_temoin',
                'fonkotany.code_fonkotany',
                'fonkotany.nom_fonkotany',
                'communes.code_commune',
                'communes.nom_commune',
                'regions.code_region',
                'regions.nom_region'
            )
            ->join('personnes', 'actes.id_person', '=', 'personnes.id_person')
            ->join('fonkotany', 'actes.id_fonkotany', '=', 'fonkotany.id_fonkotany')
            ->join('communes', 'actes.id_commune', '=', 'communes.id_commune')
            ->join('regions', 'actes.id_region', '=', 'regions.id_region')
            ->when($id_fonkotany, function ($query, $id_fonkotany) {
                return $query->where('fonkotany.id_fonkotany', $id_fonkotany);
            })
            ->when($id_commune, function ($query, $id_commune) {
                return $query->where('communes.id_commune', $id_commune);
            })
            ->when($id_region, function ($query, $id_region) {
                return $query->where('regions.id_region', $id_region);
            })
            ->orderBy('actes.id_acte')
            ->get();


        return $result;
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

        // return [];

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



    /**
     * Display a listing of the resource.
     */
    public function groupBirthday()
    {
        $actesParAnnee = $this->acte->select(DB::raw('YEAR(date_acte) as annee'), DB::raw('count(*) as nombre_actes'))
            ->groupBy('annee')
            ->orderBy('annee', 'asc')
            ->get();

        if (!$actesParAnnee) {
            return $actesParAnnee;
        }
        return "vide";
    }
}
