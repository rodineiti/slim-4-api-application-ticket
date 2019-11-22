<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Tipo extends Eloquent
{
	protected $table = 'tbtipo';
	protected $primaryKey = 'tipId';
	
	public $timestamps = false;

	protected $fillable = [
		'tipDescricao',
	    'cor',
	];

	public function chamados()
    {
    	return $this->hasMany('App\Models\Chamado', 'tipId');
    }
}