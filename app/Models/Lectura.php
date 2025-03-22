<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    use HasFactory;

    protected $table = 'lecturas';
    protected $fillable = ['sensor_id', 'valor', 'unidad', 'fecha_hora'];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
}
