<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produtos extends Model
{
    // mostra o relacionamentos entre as tabelas
    public function mostrarComentarios() {
        return $this->hasMany('App\Comentario', 'produtos_id', 'id');
    }
}
