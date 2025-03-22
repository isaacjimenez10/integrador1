<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $table = 'sensores';
    protected $fillable = ['nombre', 'tipo', 'ubicacion', 'fecha_registro'];

    public function lecturas()
    {
        return $this->hasMany(Lectura::class);
    }

    public function alertas()
    {
        return $this->hasMany(Alerta::class);
    }

    public function configuracion()
    {
        return $this->hasOne(Configuracion::class);
    }
}

