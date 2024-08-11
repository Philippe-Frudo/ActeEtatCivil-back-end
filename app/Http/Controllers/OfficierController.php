<?php

namespace App\Http\Controllers;

use App\Models\Officier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OfficierController extends Controller
{
    protected $officier;

    public function __construct()
    {
        $this->officier = new Officier();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $officierData = $this->officier->join('commune', 'commune.id_commune', '=', 'officier.id_commune')
            ->select(
                'officier.id_off',
                'officier.nom_off',
                'officier.prenom_off',
                'officier.sexe_off',
                'commune.code_commune',
                'commune.nom_commune'
            )->get();
        return $officierData;
    }
    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        $verify = $this->officier->where('email_off', $request->email_off)->first();

        if ($verify->email_off) {
            return response()->json(['status' => false, 'message' => "Cet email existe déjà dans la base"], 404);
        }

        if ($verify->id_commune &&  !$verify->isDelete) {
            return response()->json(['status' => false, 'message' => "Votre commune a déjà un compte"], 404);
        }

        try {
            $officier = $this->officier->create([
                "nom_off" => $request->nom_off,
                "prenom_off" => $request->prenom_off,
                "sexe_off" => $request->sexe_off,
                "email_off" => $request->email_off,
                "motPass_off" => Hash::make($request->motPass_off),
                "id_commune" => $request->id_commune
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        if (!$officier) {
            return response()->json(['status' => false, 'message' => "Une eurreur s'est produit lors de création"], 404);
        }
        return response()->json(['status' => true, 'message' => "En attendant la confirmation de l'admin, envoyer par email"], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $officier = $this->officier->find($id);
        if (!$officier) {
            return response()->json(['status' => false, 'message' => "Le officier est introuvable"]);
        }
        return ($officier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $officier = $this->officier->find($id);
        if ($officier) {
            return response()->json(['status' => false, 'message' => " donne introuvablee "], 404);
        }
        $resp = $officier->update($request->all());
        if (!$resp) {
            return response()->json(['status' => false, 'Une erreur s\'est produit'], 200);
        }
        return response()->json(['status' => true, 'message' => "Modification reuissi"], 201);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {

        $officier = $this->officier->find($id);
        if ($officier) {
            return response()->json(['status' => false, 'Cette f0nkotany n\'existe pas'], 404);
        }
        $resp = $officier->delete();
        if (!$resp) {
            return response()->json(['status' => false, 'message' => "Une erruer s'est produit lors de la suppression du officier"], 500);
        }
        return response()->json(['status' => true, 'message' => "Suppression reuissi"], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function authentication(Request $request)
    {
        if (!$request->email_off || !$request->motPass_off) {
            return response()->json(['status' => false, 'Verifier votre champ'], 404);
        }
        $officier = $this->officier->where('email_off', $request->email_off)->first();

        if (!$officier) {
            return response()->json(['status' => false, 'message' => 'Email ou mot de passe oublier'], 404);
        } else if (!$officier->isConfirmAdmin) {
            return response()->json(['status' => false, 'message' => 'Ce compte est en cours de confirmation'], 404);
        } else if ($officier->isDelete) {
            return response()->json(['status' => false, 'message' => 'Ce compte est déja supprimé'], 404);
        } else {
            $verification_mot_de_passe = Hash::check($request->motPass_off, $officier->motPass_off);
            if (!$verification_mot_de_passe) {
                return response()->json(['status' => false, 'message' => 'Mot de passe incorrect'], 404);
            }

            $data = [
                "id" => $officier->id_off,
                "nom" => $officier->nom_off,
                "prenom" => $officier->prenom_off,
                "commune" => $officier->id_commune
            ];

            return response()->json(['status' => true, "data" => $data], 200);
        }
    }
}
