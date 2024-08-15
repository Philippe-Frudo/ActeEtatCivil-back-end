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

        $users = [
            [
                "nom_off" => "Lainantenaina",
                'prenom_off' => "Frudo",
                'sexe_off' => "M",
                'email_off' => "l.p.n.frudo@gmail.com",
                'motPass_off' => "frudo123",
                'id_commune' => "11",
                'isDelete' => false,
                'isConfirm' => true,
                'isAdmin' => true,
            ],
            [
                "nom_off" => "admin",
                'prenom_off' => "admin",
                'sexe_off' => "M",
                'email_off' => "admin@gmail.com",
                'motPass_off' => "admin123",
                'id_commune' => "1",
                'isDelete' => false,
                'isConfirm' => true,
                'isAdmin' => true,
            ],
            [
                "nom_off" => "user",
                'prenom_off' => "",
                'sexe_off' => "M",
                'email_off' => "user@gmail.com",
                'motPass_off' => "user123",
                'id_commune' => "1",
                'isDelete' => false,
                'isConfirm' => true,
                'isAdmin' => false,
            ],
        ];


        $officier = $this->officier->all();

        // Si le table est vide alors créer ces utilisateurs
        if (!$officier) {
            foreach ($users as $user) {
                $this->officier->create($user);
            }
        }
    }


    public function index()
    {
        $officierData = $this->officier->query()
            ->select(
                'officier.id_off',
                'officier.nom_off',
                'officier.prenom_off',
                'officier.sexe_off',
                'officier.email_off',
                'officier.isConnect',
                'officier.isAdmin',
                'officier.isConfirm',
                'officier.isDelete',
                'commune.id_commune',
                'commune.code_commune',
                'commune.nom_commune',
                'district.nom_district',
                'region.nom_region'
            )
            ->join('commune', 'commune.id_commune', '=', 'officier.id_commune')
            ->join("district", 'district.id_district', '=', 'commune.id_district')
            ->join("region", 'region.id_region', '=', 'district.id_region')
            ->where('isDelete', 0)
            ->get();


        return $officierData;
    }



    public function store(Request $request)
    {
        $verify = $this->officier->where('email_off', $request->email_off)->first();

        if ($verify) {
            if ($verify->email_off) {
                return response()->json(['status' => false, 'message' => "Cet email existe déjà dans la base"]);
            }

            if ($verify->id_commune &&  !$verify->isDelete) {
                return response()->json(['status' => false, 'message' => "Votre commune a déjà un compte"]);
            }
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


    public function show(int $id)
    {
        $officier = $this->officier->find($id);
        if (!$officier) {
            return response()->json(['status' => false, 'message' => "Le officier est introuvable"]);
        }
        return ($officier);
    }


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


    public function destroy(int $id)
    {
        $officier = $this->officier->find($id);
        if ($officier) {
            return response()->json(['status' => false, 'Cette fonkotany n\'existe pas'], 404);
        }
        $resp = $officier->delete();
        if (!$resp) {
            return response()->json(['status' => false, 'message' => "Une erruer s'est produit lors de la suppression du officier"], 500);
        }
        return response()->json(['status' => true, 'message' => "Suppression reuissi"], 200);
    }


    public function authentication(Request $request)
    {
        $email = $request->userEmail;
        $password = $request->password;

        // return $request;
        if (!$email || !$password) {
            return response()->json(['status' => false, 'message' => 'Verifier votre champ'], 404);
        }

        $officier = $this->officier->where('email_off', $email)->first();

        if (!$officier) {
            return response()->json(['status' => false, 'message' => 'Email ou mot de passe oublier']);
        } else if (!$officier->isConfirm) {
            return response()->json(['status' => false, 'message' => 'Ce compte est en cours de confirmation']);
        } else if ($officier->isDelete) {
            return response()->json(['status' => false, 'message' => 'Ce compte est déja supprimé']);
        } else {
            $verification_mot_de_passe = Hash::check($password, $officier->motPass_off);
            if (!$verification_mot_de_passe) {
                return response()->json(['status' => false, 'message' => 'Mot de passe incorrect']);
            }

            // $data = [
            //     "id" => $officier->id_off,
            //     "nom" => $officier->nom_off,
            //     "prenom" => $officier->prenom_off,
            //     "commune" => $officier->id_commune
            // ];

            $data = [
                "email" => $officier->email_off,
                "myConnect" => $officier->motPass_off
            ];

            return response()->json(['status' => true, 'data' => $data]);
        }
    }

    public function verifyConnect(Request $request)
    {
        $password = $request->myConnect;
        $mail = $request->email;

        // return $password;

        $officier = $this->officier
            ->where("motPass_off", $password)
            ->where("email_off", $mail)
            ->first();
        if (!$officier) {
            return null;
        }

        $officier->update([
            'isConnect' => 1
        ]);

        $result = $this->officier->where("id_off", $officier->id_off)->first();

        return  [
            "id" => $result->id_off,
            "nom" => $result->nom_off,
            "prenom" => $result->prenom_off,
            "email" => $result->email_off,
            "sexe" => $result->sexe_off,
            "commune" => $result->id_commune,
            "connect" => $result->isConnect,
            "isAdmin" => $result->isAdmin,
        ];
    }


    // LogOut Officier (utilisateur)
    public function logOut(Request $request)
    {
        $password = $request->myConnect;
        $mail = $request->email;

        $result = $this->officier
            ->where("motPass_off", $password)
            ->where("email_off", $mail)
            ->first();

        $result;
        if (!$result) {
            return null;
        }

        $resp = $result->update(['isConnect' => 0]);

        if (!$resp) {
            return 0;
        }
        return 1;
    }



    // Confirm Officier (utilisateur)
    public function confirmOfficier(Request $request)
    {
        $result = $this->officier->find($request->id);
        if (!$result) {
            return null;
        }

        $resp = $result->update(['isConfirm' => 1]);

        if (!$resp) {
            return 0;
        }
        return 1;
    }



    // Delete Officier (utilisateur)
    public function deleteOfficier(Request $request)
    {
        $result = $this->officier->find($request->id);
        if (!$result) {
            return null;
        }

        $resp = $result->update(['isDelete' => 1]);

        if (!$resp) {
            return 0;
        }

        return 1;
    }



    // Nombre d'acte de naissance
    public function nombreOfficier()
    {
        $results = $this->officier->where('isDelete', false)
            ->where('isConfirm', true)
            ->count();

        if (!$results) {
            return 0;
        }
        return $results;
    }
}
