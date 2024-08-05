<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{

    use HasFactory;

    protected $table = 'region';

    protected $primaryKey = 'id_region';
    public $incrementing = true; // Si code_region n'est pas un entier auto-incrémenté

    protected $fillable = [
        'code_region',
        'nom_region',
    ];
}
