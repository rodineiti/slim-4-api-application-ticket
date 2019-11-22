<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Evento extends Eloquent
{
	protected $table = 'tbeventos';
	protected $primaryKey = 'eveId';
	
	public $timestamps = false;

	protected $fillable = [];

	public function chamados()
    {
    	return $this->hasMany('App\Models\Chamado', 'eveToken', 'chaToken');
    }
}