<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Chamado;
use App\Models\Mail;
use App\Helpers\Helpers;
use Aura\Filter\FilterFactory;

$model = new Chamado();
$ObjMail = new Mail();

$app->get('/api/v1/chamados', function (Request $request, Response $response, array $args) use ($model) {

	$chaDescricao = '';
	$column = 'created_at';
	$direction = 'desc';
	$per_page = 10;
	$page = 1;
	$filter_list = 'all';

	if (filter_input(INPUT_GET, 'per_page')) {
		$per_page = filter_input(INPUT_GET, 'per_page');
	}

	if (filter_input(INPUT_GET, 'direction')) {
		$direction = filter_input(INPUT_GET, 'direction');
	}

	if (filter_input(INPUT_GET, 'column')) {
		$column = filter_input(INPUT_GET, 'column');
	}

	if (filter_input(INPUT_GET, 'page')) {
		$page = filter_input(INPUT_GET, 'page');
	}

	if (filter_input(INPUT_GET, 'filter_list')) {
		$filter_list = filter_input(INPUT_GET, 'filter_list');
	}

	$chamados = $model->orderBy($column, $direction);

	if (filter_input(INPUT_GET, 'chaDescricao')) {
		$chamados = $chamados
						->where('chaDescricao', 'LIKE', '%'. filter_input(INPUT_GET, 'chaDescricao') . '%')
						->orWhere('chaToken', 'LIKE', '%'. filter_input(INPUT_GET, 'chaDescricao') . '%');
		$chaDescricao = filter_input(INPUT_GET, 'chaDescricao');
	}

	if ($filter_list == 'active') {
		$chamados = $chamados->whereNull('completed_at');
	}

	if ($filter_list == 'close') {
		$chamados = $chamados->whereNotNull('completed_at');
	}

	$chamados = $chamados->with(['user','tipo','area','prioridade','status'])->paginate($per_page, ['*'], 'page', $page);
	$chamados_geral = $model->count();
	$chamados_ativo = $model->whereNull('completed_at')->count();
	$chamados_concluido = $model->whereNotNull('completed_at')->count();

	foreach ($chamados as $key => $value) {
		$chamados[$key]['isCompleted'] = $value->isCompleted();
	}

	$results = [
		'chamados' => $chamados,
		'geral' => $chamados_geral,
		'ativo' => $chamados_ativo,
		'concluido' => $chamados_concluido,
		'params' => [
			'total' => $chamados->total(),
			'per_page' => $chamados->perPage(),
			'current_page' => $chamados->currentPage(),
			'last_page' => $chamados->lastPage(),
			'next_page_url' => $chamados->nextPageUrl(),
			'prev_page_url' => $chamados->previousPageUrl(),
			'direction' => $direction,
			'column' => $column
		],
		'filters' => [
			'chaDescricao' => $chaDescricao
		]
	];

	$response->getbody()->write(json_encode($results));
	return $response;
});

$app->post('/api/v1/chamados', function (Request $request, Response $response, array $args) use ($model, $ObjMail) {
	$data = $request->getParsedBody();

	$filter_factory = new FilterFactory;
    $filter = $filter_factory->newSubjectFilter();
    $filter->validate('tipId')->is('int');
    $filter->validate('areId')->is('int');
    $filter->validate('priId')->is('int');
    $filter->validate('staId')->is('int');
    $filter->validate('chaDescricao')->is('string');
    $filter->validate('chaDescricao')->is('strlenMin', 3);
    $filter->validate('chaNomeCliente')->is('string');
    $filter->validate('chaNomeCliente')->is('strlenMin', 3);
    $filter->validate('chaConteudo')->is('string');
    
    if (!$filter->apply($data)) {
        $messages = $filter->getFailures()->getMessages();
        $response->getbody()->write(json_encode([
            'status' => 'error',
            'messages' => $messages
        ]));
        return $response;
    }

	if (!isset($data['usuId']) || trim($data['usuId']) == '' || empty($data['usuId'])) {
		$data['usuId'] = 1;
	}

	$helper = new Helpers();
	$data['chaSlug'] = $helper->slug($data['chaDescricao']);

	$chamado = $model->create($data);
	$chamados_ativo = $model->whereNull('completed_at')->count();

	$address = ['email@email.com'];
	$subject = 'Novo chamado número ' . $chamado->chaId;
	$bodyHtml = '
		<h1>Olá! Um novo chamado foi aberto.</h1>
		<p>Seguem os dados do chamado:</p>
		<p>Chamado: #'.$chamado->chaId.' </p>
		<p>Chamado: '.$chamado->chaDescricao.' </p>
		<p>Cliente: '.$chamado->chaNomeCliente.' </p>
		<p>Prioridade: '.$chamado->prioridade->priDescricao.' </p>
		<p>Tipo: '.$chamado->tipo->tipDescricao.' </p>
		<p>Data: '.$chamado->created_at.' </p>
	';

	if ($this->get('settings')['send_mail']['add_chamado']) {
		if ($ObjMail->send($address, utf8_decode($subject), utf8_decode($bodyHtml))) {
			$dataEmail['chaId'] = $chamado->chaId;
			$dataEmail['emaAddress'] = serialize($address);
			$dataEmail['emaSuject'] = $subject;
			$dataEmail['emaBody'] = $bodyHtml;
			$ObjMail->create($dataEmail);
		}
	}
		
	// $this->pusher->trigger('sis-channel', 'new-message', [
 //        'name_client' => $chamado->chaNomeCliente,
 //        'chamados_count' => $chamados_ativo,
 //        'message' => 'Houve criação de um novo chamado número ' . $chamado->chaId,
 //    ]);
    
    $response->getbody()->write(json_encode($chamado));
	return $response;
});

$app->get('/api/v1/chamados/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$chamado = $model->with(['user','tipo','area','prioridade','status','respostas' => function($query){
            $query->with('user')->orderBy('created_at', 'DESC');
        }])->findOrFail($args['id']);
	$chamado['isCompleted'] = $chamado->isCompleted();

	$response->getbody()->write(json_encode($chamado));
	return $response;
});

$app->put('/api/v1/chamados/{id}', function (Request $request, Response $response, array $args) use ($model, $ObjMail) {
	$data = $request->getParsedBody();

	$filter_factory = new FilterFactory;
    $filter = $filter_factory->newSubjectFilter();
    $filter->validate('tipId')->is('int');
    $filter->validate('areId')->is('int');
    $filter->validate('priId')->is('int');
    $filter->validate('staId')->is('int');
    $filter->validate('chaDescricao')->is('string');
    $filter->validate('chaDescricao')->is('strlenMin', 3);
    $filter->validate('chaNomeCliente')->is('string');
    $filter->validate('chaNomeCliente')->is('strlenMin', 3);
    $filter->validate('chaConteudo')->is('string');
    
    if (!$filter->apply($data)) {
        $messages = $filter->getFailures()
            ->getMessages();
        $response->getbody()->write(json_encode([
            'status' => 'error',
            'messages' => $messages
        ]));
        return $response;
    }

	$chamado = $model->findOrFail($args['id']);
	if (!isset($data['usuId']) || trim($data['usuId']) == '' || empty($data['usuId'])) {
		$data['usuId'] = 1;
	}
	$chamado->update($data);
	$chamados_ativo = $model->whereNull('completed_at')->count();

	$address = ['email@email.com'];
	$subject = 'O chamado número ' . $chamado->chaId . ' foi alterado';
	$bodyHtml = '
		<h1>Olá! Houve uma alteração no chamado.</h1>
		<p>Seguem os dados do chamado:</p>
		<p>Chamado: #'.$chamado->chaId.' </p>
		<p>Chamado: '.$chamado->chaDescricao.' </p>
		<p>Cliente: '.$chamado->chaNomeCliente.' </p>
		<p>Prioridade: '.$chamado->prioridade->priDescricao.' </p>
		<p>Tipo: '.$chamado->tipo->tipDescricao.' </p>
		<p>Data: '.$chamado->created_at.' </p>
	';

	if ($this->get('settings')['send_mail']['edit_chamado']) {
		if ($ObjMail->send($address, utf8_decode($subject), utf8_decode($bodyHtml))) {
			$dataEmail['chaId'] = $chamado->chaId;
			$dataEmail['emaAddress'] = serialize($address);
			$dataEmail['emaSuject'] = $subject;
			$dataEmail['emaBody'] = $bodyHtml;
			$ObjMail->create($dataEmail);
		}
	}
	
	// $this->pusher->trigger('sis-channel', 'new-message', [
 //        'name_client' => $chamado->chaNomeCliente,
 //        'chamados_count' => $chamados_ativo,
 //        'message' => 'Houve uma atualização no chamado número ' . $chamado->chaId,
 //    ]);
    
	$response->getbody()->write(json_encode($chamado));
	return $response;
});

$app->delete('/api/v1/chamados/{id}', function (Request $request, Response $response, array $args) use ($model, $ObjMail) {
	$chamado = $model->findOrFail($args['id']);
	$chamado->respostas()->delete();
	$chamado->delete();

	$chamados_ativo = $model->whereNull('completed_at')->count();

	$address = ['email@email.com'];
	$subject = 'Exclusão de Chamado: O chamado número ' . $chamado->chaId . ' foi deletado';
	$bodyHtml = '
		<h1>Olá! O chamado '.$chamado->chaId.' foi deletado.</h1>
		<p>Seguem os dados do chamado:</p>
		<p>Chamado: #'.$chamado->chaId.' </p>
		<p>Chamado: '.$chamado->chaDescricao.' </p>
		<p>Cliente: '.$chamado->chaNomeCliente.' </p>
		<p>Prioridade: '.$chamado->prioridade->priDescricao.' </p>
		<p>Tipo: '.$chamado->tipo->tipDescricao.' </p>
		<p>Data: '.$chamado->created_at.' </p>
	';

	if ($this->get('settings')['send_mail']['del_chamado']) {
		if ($ObjMail->send($address, utf8_decode($subject), utf8_decode($bodyHtml))) {
			$dataEmail['chaId'] = $chamado->chaId;
			$dataEmail['emaAddress'] = serialize($address);
			$dataEmail['emaSuject'] = $subject;
			$dataEmail['emaBody'] = $bodyHtml;
			$ObjMail->create($dataEmail);
		}
	}
	
    // $this->pusher->trigger('sis-channel', 'new-message', [
    //     'name_client' => $chamado->chaNomeCliente,
    //     'chamados_count' => $chamados_ativo,
    //     'message' => 'Houve uma exclusão do chamado número ' . $chamado->chaId,
    // ]);
    
	$response->getbody()->write(json_encode($chamado));
	return $response;
});

$app->put('/api/v1/chamados/complete/{id}', function (Request $request, Response $response, array $args) use ($model, $ObjMail) {
	$data = $request->getParsedBody();

	$filter_factory = new FilterFactory;
    $filter = $filter_factory->newSubjectFilter();
    $filter->validate('completed')->is('bool');
    
    if (!$filter->apply($data)) {
        $messages = $filter->getFailures()
            ->getMessages();
        $response->getbody()->write(json_encode([
            'status' => 'error',
            'messages' => $messages
        ]));
        return $response;
    }

	$chamado = $model->findOrFail($args['id']);
	
	$strTitle = '';

	if ($data['completed']) {
		$data['completed_at'] = date("Y-m-d H:i:s");
		$strTitle = 'Fechado';
	} else {
		$data['completed_at'] = NULL;
		$strTitle = 'Reaberto';
	}

	$chamado->fill($data)->save();

	$chamados_ativo = $model->whereNull('completed_at')->count();

	$address = ['email@email@email.com'];
	$subject = 'O chamado número ' . $chamado->chaId . ' foi marcado como ' . $strTitle;
	$bodyHtml = '
		<h1>Olá! Houve uma alteração no chamado. Ele foi marcado como ' . $strTitle . '</h1>
		<p>Seguem os dados do chamado:</p>
		<p>Chamado: #'.$chamado->chaId.' </p>
		<p>Chamado: '.$chamado->chaDescricao.' </p>
		<p>Cliente: '.$chamado->chaNomeCliente.' </p>
		<p>Prioridade: '.$chamado->prioridade->priDescricao.' </p>
		<p>Tipo: '.$chamado->tipo->tipDescricao.' </p>
		<p>Data: '.$chamado->created_at.' </p>
	';

	if ($this->get('settings')['send_mail']['edit_chamado']) {
		if ($ObjMail->send($address, utf8_decode($subject), utf8_decode($bodyHtml))) {
			$dataEmail['chaId'] = $chamado->chaId;
			$dataEmail['emaAddress'] = serialize($address);
			$dataEmail['emaSuject'] = $subject;
			$dataEmail['emaBody'] = $bodyHtml;
			$ObjMail->create($dataEmail);
		}
	}
	
	// $this->pusher->trigger('sis-channel', 'new-message', [
 //        'name_client' => $chamado->chaNomeCliente,
 //        'chamados_count' => $chamados_ativo,
 //        'message' => 'Houve uma atualização no chamado número ' . $chamado->chaId,
 //    ]);
    
	$response->getbody()->write(json_encode($chamado));
	return $response;
});