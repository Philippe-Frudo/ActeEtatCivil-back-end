<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personne extends Model
{
    protected $table = 'personne';
    protected $primaryKey = 'id_person';
    public $incrementing = true;

    protected $fillable = [
        'nom_person',
        'prenom_person',
        'sexe_person',
        'adrs_person',
        'nom_m',
        'prenom_m',
        'date_nais_m',
        'lieu_nais_m',
        'age_m',
        'profession_m',
        'adrs_m',
        'nom_p',
        'prenom_p',
        'date_nais_p',
        'lieu_nais_p',
        'age_p',
        'profession_p',
        'adrs_p',
        'id_travail',
       ' date_create_person'
    ];

    use HasFactory;
}
