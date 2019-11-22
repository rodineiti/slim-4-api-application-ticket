<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Prioridade extends Eloquent
{
	protected $table = 'tbprioridade';
	protected $primaryKey = 'priId';
	
	public $timestamps = false;

	protected $fillable = [
		'priDescricao',
	    'cor',
	];

	public function chamados()
    {
    	return $this->hasMany('App\Models\Chamado', 'priId');
    }
}