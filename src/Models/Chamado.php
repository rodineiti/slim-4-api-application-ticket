<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Chamado extends Eloquent
{
	protected $table = 'tbchamados';
	protected $primaryKey = 'chaId';
	protected $dates = ['completed_at'];

	protected $fillable = [
		'areId',
	    'priId',
	    'staId',
	    'tipId',
	    'usuId',
	    'chaNomeCliente',
	    'chaDescricao',
        'chaSlug',
        'chaToken',
	    'chaConteudo',
	    'completed_at'
	];

    public function user()
    {
        return $this->belongsTo('\App\Models\User', 'usuId');
    }

    public function respostas()
    {
        return $this->hasMany('\App\Models\Resposta', 'chaId');
    }

    public function tipo()
    {
        return $this->belongsTo('\App\Models\Tipo', 'tipId');
    }

    public function area()
    {
        return $this->belongsTo('\App\Models\Area', 'areId');
    }

    public function prioridade()
    {
        return $this->belongsTo('\App\Models\Prioridade', 'priId');
    }

    public function status()
    {
        return $this->belongsTo('\App\Models\Status', 'staId');
    }

    public function isCompleted()
    {
    	return (bool) $this->completed_at;
    }
}