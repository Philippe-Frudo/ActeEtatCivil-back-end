<?php

namespace App\Http\Controllers;

use App\Models\Acte;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Builder\Param;

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
        return  response()->json($typesDataActe);
    }


    public function index(Request $request)
    {
        $filterType = (int) $request->query('id');
        $filterFonkotany = (int) $request->query('fonkotany');
        $filterCommune = (int) $request->query('commune');
        $filterDistrict = (int) $request->query('district');
        $filterRegion = (int) $request->query('region');

        $query = $this->acte->select(
            'personne.*',
            'acte.*',
            'travail.nom_travail',
            'fonkotany.code_fonkotany',
            'fonkotany.nom_fonkotany',
            'district.code_district',
            'district.nom_district',
            'commune.code_commune',
            'commune.nom_commune',
            'region.code_region',
            'region.nom_region',
            'officier.nom_off',
            'officier.prenom_off'
        )
            ->join('personne', 'personne.id_person', '=', 'acte.id_person')
            ->join('travail', 'travail.id_travail', '=', 'personne.id_travail')
            ->join('fonkotany', 'acte.id_fonkotany', '=', 'fonkotany.id_fonkotany')
            ->join('commune', 'commune.id_commune', '=', 'acte.id_commune')
            ->join('district', 'district.id_district', '=', 'commune.id_district')
            ->join('region', 'region.id_region', '=', 'district.id_region')
            ->join('officier', 'officier.id_off', '=', 'acte.id_off')
            ->when($filterType, function ($query, $filterType) {
                return $query->where('acte.id_type', $filterType);
            })
            ->when($filterFonkotany, function ($query, $filterFonkotany) {
                return $query->where('acte.id_fonkotany', $filterFonkotany);
            })
            ->when($filterCommune, function ($query, $filterCommune) {
                return $query->where('acte.id_commune', $filterCommune);
            })
            ->when($filterDistrict, function ($query, $filterDistrict) {
                return $query->where('district.id_district', $filterDistrict);
            })
            ->when($filterRegion, function ($query, $filterRegion) {
                return $query->where('region.id_region', $filterRegion);
            })
            ->orderBy('acte.id_acte');


        $results = $query->get();
        if ($results->isEmpty()) {
            return null;
        }

        $actes = [];
        foreach ($results as $result) {
            $actes[] = [
                "id_acte" => $result->id_acte,
                "nom_personne" => $result->nom_person,
                "prenom_personne" => $result->prenom_person,
                "sexe_personne" => $result->sexe_person,
                "adrsesse_personne" => $result->adrs_person,
                "nom_travail_personne" => $result->nom_travail,

                "numero_acte" => $result->num_acte,
                "type_acte" => DB::table('type')->where('id_type', $result->id_type)->first()->nom_type,
                "date_acte" => $result->date_acte,
                "heure_acte" => $result->heure_acte,
                "date_enreg" => $result->date_enreg,
                "heure_enreg" => $result->heure_enreg,

                "lieu_acte" => $result->lieu_acte,
                "fonkotany" => $result->nom_fonkotany, //. '(' . $result->code_fonkotany . ')',
                "district" => $result->nom_district,  //.'(' . $result->code_district . ')',
                "commune" => $result->nom_commune, // . '(' . $result->code_commune . ')',
                "region" => $result->nom_region, // . '(' . $result->code_region . ')',

                "nom_mere" => $result->nom_m,
                "prenom_mere" => $result->prenom_m,
                "date_naissance_mere" => $result->date_nais_m,
                "lieu_naissance_mere" => $result->lieu_nais_m,
                "age_mere" => $result->age_m,
                "profession_mere" => $result->profession_m,
                "adresse_mere" => $result->adrs_m,

                "noms_pere" => $result->nom_p,
                "prenom_pere" => $result->prenom_p,
                "date_naissance_pere" => $result->date_nais_p,
                "lieu_naissance_pere" => $result->lieu_nais_p,
                "age_pere" => $result->age_p,
                "profession_pere" => $result->profession_p,
                "adresse_pere" => $result->adrs_p,

                "nom_temoin" => $result->nom_temoin,
                "prenom_temoin" => $result->prenom_temoin,
                "sexe_temoin" => $result->sexe_temoin,
                "date_naissance_temoin" => $result->date_nais_temoin,
                "lieu_naissance_temoin" => $result->lieu_nais_temoin,
                "age_temoin" => $result->age_temoin,
                "adresse_temoin" => $result->adrs_temoin,
                "profession_temoin" => $result->profession_temoin,

                "nom_officier" => $result->nom_off,
                "prenom_officier" => $result->prenom_off,

                "id_commune" => $result->id_commune,
                "id_officier" => $result->id_off,
            ];
        }

        return $actes;
    }




    public function show(int $id)
    {
        // return $id;
        $acte = $this->acte->find($id);
        if (!$acte) {
            return null;
        }
        return $acte;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $numero = $this->acte->where("num_acte", $request->num_acte)->where("id_type", $request->id_type)->first();
        if ($numero) {
            return $numero->num_acte;
        }

        $acte = $this->acte->create($request->all());
        // return $request;
        if (!$acte) {
            return 0;
        }
        return 1;
    }


    /**
     * Display the specified resource.
     */
    public function getDetail(int $id)
    {
        $result = $this->acte->select(
            'personne.*',
            'acte.*',
            'travail.*',
            'fonkotany.*',
            'district.code_district',
            'district.nom_district',
            'commune.code_commune',
            'commune.nom_commune',
            'region.code_region',
            'region.nom_region',
            'officier.nom_off',
            'officier.prenom_off'
        )
            ->join('personne', 'personne.id_person', '=', 'acte.id_person')
            ->join('travail', 'travail.id_travail', '=', 'personne.id_travail')
            ->join('fonkotany', 'acte.id_fonkotany', '=', 'fonkotany.id_fonkotany')
            ->join('commune', 'commune.id_commune', '=', 'acte.id_commune')
            ->join('district', 'district.id_district', '=', 'commune.id_district')
            ->join('region', 'region.id_region', '=', 'district.id_region')
            ->join('officier', 'officier.id_off', '=', 'acte.id_off')
            ->where('acte.id_acte', $id)
            ->first();

        if (!$result) {
            return null;
        }

        return [
            "id_acte" => $result->id_acte,
            "id_person" => $result->id_person,
            "nom_person" => $result->nom_person,
            "prenom_person" => $result->prenom_person,
            "sexe_person" => $result->sexe_person,
            "adrs_person" => $result->adrs_person,
            "nom_travail" => $result->nom_travail,
            "id_travail" => $result->nom_travail,

            "num_acte" => $result->num_acte,
            "id_type" => $result->id_type,
            "type_acte" => DB::table('type')->where('id_type', $result->id_type)->first()->nom_type,
            "date_acte" => $result->date_acte,
            "heure_acte" => $result->heure_acte,
            "date_enreg" => $result->date_enreg,
            "heure_enreg" => $result->heure_enreg,

            "lieu_acte" => $result->lieu_acte,
            "nom_fonkotany" => $result->nom_fonkotany,
            "nom_district" => $result->nom_district,
            "nom_commune" => $result->nom_commune,
            "nom_region" => $result->nom_region,

            "nom_m" => $result->nom_m,
            "prenom_m" => $result->prenom_m,
            "date_nais_m" => $result->date_nais_m,
            "lieu_nais_m" => $result->lieu_nais_m,
            "age_m" => $result->age_m,
            "profession_m" => $result->profession_m,
            "adrs_m" => $result->adrs_m,

            "nom_p" => $result->nom_p,
            "prenom_p" => $result->prenom_p,
            "date_nais_p" => $result->date_nais_p,
            "lieu_nais_p" => $result->lieu_nais_p,
            "age_p" => $result->age_p,
            "profession_p" => $result->profession_p,
            "adrs_p" => $result->adrs_p,

            "nom_temoin" => $result->nom_temoin,
            "prenom_temoin" => $result->prenom_temoin,
            "sexe_temoin" => $result->sexe_temoin,
            "date_nais_temoin" => $result->date_nais_temoin,
            "lieu_nais_temoin" => $result->lieu_nais_temoin,
            "age_temoin" => $result->age_temoin,
            "adrs_temoin" => $result->adrs_temoin,
            "profession_temoin" => $result->profession_temoin,

            "nom_off" => $result->nom_off,
            "prenom_off" => $result->prenom_off,

            "id_commune" => $result->id_commune,
            "id_fonkotany" => $result->id_fonkotany,
            "id_off" => $result->id_off,
        ];
    }


    public function update(Request $request, int $id)
    {
        $acte = $this->acte->find($id);
        if (!$acte) {
            return null;
        }

        $resp = $acte->update($request->all());
        if (!$resp) {
            return 0;
        }
        return 1;
    }


    public function destroy(string $id)
    {
        $acte = $this->acte->find($id);
        if (!$acte) {
            return null;
        }

        $idPersonne = $acte->id_person;

        $resp = $acte->delete();

        // Rechercher encor s'il c'est encore utiliser ?
        $secondActe = $this->acte->where("id_person", $idPersonne)->first();
        if (!$secondActe) {
            return null;
        }

        return $idPersonne;
    }



    public function verifyNumActe(Request $request)
    {
        $numero = $request->numero;
        $type = $request->type;

        if ($numero && $type) {
            $result = $this->acte->where("num_acte", $numero)->where("id_type", $type)->first();
            if (!$result) {
                return null;
            }
            return $result->num_acte;
        }
        return null;
    }


    // Nombre d'acte de naissance
    public function countNaissance()
    {
        $results = $this->acte->where('id_type', 1)->count();
        if (!$results) {
            return 0;
        }
        return $results;
    }


    // Nombre de naissance regrouper par l'annee de naisance
    public function groupBirthday()
    {
        $results = $this->acte->select(DB::raw('YEAR(date_acte) as annee'), DB::raw('count(*) as nombre_actes'))
            ->where('id_type', 1)
            ->groupBy('annee')
            ->orderBy('annee', 'asc')
            ->get();

        if (!$results) {
            return 0;
        }
        return $results;
    }



    // Nombre d'enregistrement Aujourd'Hui
    public function registerToday()
    {
        $results = $this->acte->whereDate('created_at', Carbon::today())

            ->count();
        if (!$results) {
            return 0;
        }
        return $results;
    }



    // Nombre d'enregistrement par mois;
    public function getEnregistrementsParMois()
    {
        // Récupérer l'année actuelle
        $currentYear = Carbon::now()->year;
    
        // Récupérer le nombre d'enregistrements par mois pour l'année actuelle
        $enregistrements = $this->acte
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month')
            ->toArray();
    
        // Créer un tableau avec tous les mois de l'année avec des valeurs par défaut de 0
        $monthlyCounts = array_fill(1, 12, 0);
    
        // Remplir les valeurs avec les données récupérées
        foreach ($enregistrements as $month => $count) {
            $monthlyCounts[$month] = $count;
        }
    
        return response()->json($monthlyCounts);
    }


    // Nombre d'enregistrement Aujourd'Hui
    public function groupBirthdayWithCommune()
    {
        $results = $this->acte->whereDate('created_at', Carbon::today())
            ->count();

        if (!$results) {
            return null;
        }
        return $results;
    }
}
