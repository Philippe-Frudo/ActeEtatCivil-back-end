<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travail extends Model
{
    protected $table = 'travail';
    protected $primaryKey = 'id_travail';
    public $incrementing = true;

    protected $fillable = [
        'nom_travail'
    ];
    
    use HasFactory;
}
