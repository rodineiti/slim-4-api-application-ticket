<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
	protected $table = 'tbusuario';
	protected $primaryKey = 'usuId';
	
	public $timestamps = false;

	/**
     * Get Chamados usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chamados()
    {
        return $this->hasMany('App\Models\Chamado', 'usuId');
    }
}