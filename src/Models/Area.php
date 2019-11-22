<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Area extends Eloquent
{
	protected $table = 'tbarea';
	protected $primaryKey = 'areId';
	
	public $timestamps = false;

	protected $fillable = [
		'areDescricao',
	    'cor',
	];

	public function chamados()
    {
    	return $this->hasMany('App\Models\Chamado', 'areId');
    }
}