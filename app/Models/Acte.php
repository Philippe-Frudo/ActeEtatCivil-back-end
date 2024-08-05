<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acte extends Model
{
    protected $table = 'acte';
    protected $primaryKey = 'id_acte';
    public $incrementing = true;

    protected $fillable = [
        'date_acte',
        'heure_acte',
        'lieu_acte',
        'date_enreg',
        'heure_enreg',
        'nom_temoin',
        'prenom_temoin',
        'sexe_temoin',
        'date_nais_temoin',
        'lieu_nais_temoin',
        'age_temoin',
        'adrs_temoin',
        'profession_temoin',
        'id_person',
        'code_commune',
        'id_fonkotany',
        'id_off'
    ];

    use HasFactory;
}
