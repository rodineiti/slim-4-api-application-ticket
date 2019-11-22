<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Status extends Eloquent
{
	protected $table = 'tbstatus';
	protected $primaryKey = 'staId';
	
	public $timestamps = false;

	protected $fillable = [
		'staDescricao',
	    'cor',
	];

	public function chamados()
    {
    	return $this->hasMany('App\Models\Chamado', 'staId');
    }
}