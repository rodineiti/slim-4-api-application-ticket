<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resposta extends Eloquent
{
	protected $table = 'tbrespostas';
	protected $primaryKey = 'resId';
	use SoftDeletes;
	
	protected $fillable = [
		'chaId',
	    'usuId',
	    'resNome',
	    'resConteudo',
	];

    public function user()
    {
        return $this->belongsTo('\App\Models\User', 'usuId');
    }

    public function chamado()
    {
        return $this->belongsTo('\App\Models\Chamado', 'chaId');
    }
}