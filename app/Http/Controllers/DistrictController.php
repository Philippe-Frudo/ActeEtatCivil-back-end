<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{

    protected $district;

    public function __construct() {
        $this->district = new District();
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->district->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $district = $this->district->created($request->all() );
        if (!$district) {
            return response()->json(['status'=>false, 'message'=>"Erreur lors de l'ajout"], 404);
        }
        
        return response()->json(['status'=>true, 'Une nouvelle region a été creé'], 200);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $district = $this->district->find($id);
        if (!$district) {
            return response()->json(['status'=>false, 'message'=>"Ce district n'existe pas"], 404);
        }
    
        return response()->json($district, 200);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $district = $this->district->find($id);
        if (!$district) {
            return response()->json(['status'=>false, 'message'=>"Ce district n'existe pas"], 404);
        }
        
        $response = $district->update($request->all() );
        if (!$response) {
            return response()->json(['status'=>false, 'message'=>"Erreur de la modification"], 500);
        }
        
        return response()->json(['status'=>true, 'Modification reuissi'], 200);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $district = $this->district->find($id);
        if (!$district) {
            return response()->json(['status'=>false, 'message'=>"Ce district n'existe pas"], 404);
        }
        $resp = $district->delete();
        if (!$resp) {
            return response()->json(['status'=>true, 'Erreur lors de la suppression reuissi'], 500);
        }
        return response()->json(['status'=>true, 'Suppression reuissi'], 200);
        
    }
}
