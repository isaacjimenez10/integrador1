<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuracion';
    protected $fillable = ['sensor_id', 'minimo', 'maximo', 'fecha_actualizacion'];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
}

