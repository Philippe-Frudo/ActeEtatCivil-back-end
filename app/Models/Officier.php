<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officier extends Model
{

    protected $table = "officier";
    protected $primaryKey = 'id_off';
    public $incrementing = true;

    protected $fillable = [
        'nom_off',
        'prenom_off',
        'sexe_off',
        'email_off',
        'motPass_off',
        'id_commune',
        'isDelete',
        'isConnect',
        'isConfirm',
        'isAdmin',
    ];

    use HasFactory;


    public function commune()
    {
        return $this->belongsTo(Commune::class, 'id_commune');
    }
}
