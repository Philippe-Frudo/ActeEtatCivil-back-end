<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'district';
    protected $primaryKey = 'id_district';
    public $incrementing = true;

    protected $fillable = [
        'code_district',
        'nom_district',
        'code_region',
    ];


}
