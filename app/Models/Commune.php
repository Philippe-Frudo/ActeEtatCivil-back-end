<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    protected $table = 'commune';
    protected $primaryKey = 'id_commune';
    public $incrementing = true;
    protected $fillable = [
        'code_commune',
        'nom_commune',
        'id_district'
    ];

    use HasFactory;

    public function district() {
        return $this->belongsTo(District::class, 'id_district');
    }
}
