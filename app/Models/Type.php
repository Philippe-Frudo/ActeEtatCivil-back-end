<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'type';
    protected $primaryKey = 'id_type';
    public $incrementing = true;

    protected $fillable = [
        'nom_type'
    ];

    use HasFactory;

}
