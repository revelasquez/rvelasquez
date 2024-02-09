<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'autor_id', 'lote', 'description'];

    public function autor()
    {
        return $this->belongsTo(Autor::class);
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamos::class);
    }
}
