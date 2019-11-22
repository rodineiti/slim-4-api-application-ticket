<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Chamado;
use App\Models\Mail;
use App\Helpers\Helpers;
use Aura\Filter\FilterFactory;

$model = new Chamado();
$ObjMail = new Mail();

$app->get('/api/v2/{clientToken}/chamados', function (Request $request, Response $response, array $args) use ($model) {

	$filter_factory = new FilterFactory;
    $filter = $filter_factory->newSubjectFilter();
    $filter->validate('clientToken')->is('string');
    $filter->validate('clientToken')->isNot('int');

    $dataToken['clientToken'] = $args['clientToken'];
    
    if (!$filter->apply($dataToken)) {
        $messages = $filter->getFailures()
            ->getMessages();
        $response->getbody()->write(json_encode([
            'status' => 'error',
            'messages' => $messages
        ]));
        return $response;
    }

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
	$chamados = $chamados->where('chaToken', $dataToken['clientToken']);

	if (filter_input(INPUT_GET, 'chaDescricao')) {
		$chamados = $chamados->where('chaDescricao', 'LIKE', '%'. filter_input(INPUT_GET, 'chaDescricao') . '%');
		$chaDescricao = filter_input(INPUT_GET, 'chaDescricao');
	}

	if ($filter_list == 'active') {
		$chamados = $chamados->whereNull('completed_at');
	}

	if ($filter_list == 'close') {
		$chamados = $chamados->whereNotNull('completed_at');
	}

	$chamados = $chamados->with(['user','tipo','area','prioridade','status'])->paginate($per_page, ['*'], 'page', $page);
	$chamados_geral = $model->where('chaToken', $dataToken['clientToken'])->count();
	$chamados_ativo = $model->where('chaToken', $dataToken['clientToken'])->whereNull('completed_at')->count();
	$chamados_concluido = $model->where('chaToken', $dataToken['clientToken'])->whereNotNull('completed_at')->count();

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

$app->post('/api/v2/{clientToken}/chamados', function (Request $request, Response $response, array $args) use ($model, $ObjMail) {

	$filter_factory = new FilterFactory;
    $filter = $filter_factory->newSubjectFilter();
    $filter->validate('clientToken')->is('string');
    $filter->validate('clientToken')->isNot('int');

    $dataToken['clientToken'] = $args['clientToken'];
    
    if (!$filter->apply($dataToken)) {
        $messages = $filter->getFailures()
            ->getMessages();
        $response->getbody()->write(json_encode([
            'status' => 'error',
            'messages' => $messages
        ]));
        return $response;
    }

	$data = $request->getParsedBody();

	$filter_factory = new FilterFactory;
    $filter = $filter_factory->newSubjectFilter();
    $filter->validate('tipId')->is('int');
    $filter->validate('priId')->is('int');
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

	if (!isset($data['usuId']) || trim($data['usuId']) == '' || empty($data['usuId'])) {
		$data['usuId'] = 1;
	}

	$helper = new Helpers();
	$data['chaSlug'] = $helper->slug($data['chaDescricao']);
	$data['chaToken'] = $dataToken['clientToken'];
	$data['areId'] = 1;
	$data['staId'] = 5;

	$chamado = $model->create($data);
	
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
 //        'message' => 'Houve criação de um novo chamado número ' . $chamado->chaId,
 //    ]);    
        
	$response->getbody()->write(json_encode($chamado));
	return $response;
});

$app->get('/api/v2/{clientToken}/chamados/{id}', function (Request $request, Response $response, array $args) use ($model) {

	$filter_factory = new FilterFactory;
    $filter = $filter_factory->newSubjectFilter();
    $filter->validate('clientToken')->is('string');
    $filter->validate('clientToken')->isNot('int');

    $dataToken['clientToken'] = $args['clientToken'];
    
    if (!$filter->apply($dataToken)) {
        $messages = $filter->getFailures()
            ->getMessages();
        $response->getbody()->write(json_encode([
            'status' => 'error',
            'messages' => $messages
        ]));
        return $response;
    }

	$chamado = $model->where('chaToken', $dataToken['clientToken'])->with(['user','tipo','area','prioridade','status','respostas' => function($query){
            $query->with('user')->orderBy('created_at', 'DESC');
        }])->findOrFail($args['id']);
	$chamado['isCompleted'] = $chamado->isCompleted();

	$response->getbody()->write(json_encode($chamado));
	return $response;
});

$app->put('/api/v2/{clientToken}/chamados/{id}', function (Request $request, Response $response, array $args) use ($model, $ObjMail) {

	$filter_factory = new FilterFactory;
    $filter = $filter_factory->newSubjectFilter();
    $filter->validate('clientToken')->is('string');
    $filter->validate('clientToken')->isNot('int');

    $dataToken['clientToken'] = $args['clientToken'];
    
    if (!$filter->apply($dataToken)) {
        $messages = $filter->getFailures()
            ->getMessages();
        $response->getbody()->write(json_encode([
            'status' => 'error',
            'messages' => $messages
        ]));
        return $response;
    }

	$data = $request->getParsedBody();

	$filter_factory = new FilterFactory;
    $filter = $filter_factory->newSubjectFilter();
    $filter->validate('tipId')->is('int');
    $filter->validate('priId')->is('int');
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

	$chamado = $model->where('chaToken', $dataToken['clientToken'])->findOrFail($args['id']);
	$chamado->update($data);

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
 //        'message' => 'Houve uma atualização no chamado número ' . $chamado->chaId,
 //    ]);
    
	$response->getbody()->write(json_encode($chamado));
	return $response;
});

$app->put('/api/v2/{clientToken}/chamados/complete/{id}', function (Request $request, Response $response, array $args) use ($model, $ObjMail) {

	$filter_factory = new FilterFactory;
    $filter = $filter_factory->newSubjectFilter();
    $filter->validate('clientToken')->is('string');
    $filter->validate('clientToken')->isNot('int');

    $dataToken['clientToken'] = $args['clientToken'];
    
    if (!$filter->apply($dataToken)) {
        $messages = $filter->getFailures()
            ->getMessages();
        $response->getbody()->write(json_encode([
            'status' => 'error',
            'messages' => $messages
        ]));
        return $response;
    }

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

	$chamado = $model->where('chaToken', $dataToken['clientToken'])->findOrFail($args['id']);
	
	$strTitle = '';

	if ($data['completed']) {
		$data['completed_at'] = date("Y-m-d H:i:s");
		$strTitle = 'Fechado';
	} else {
		$data['completed_at'] = NULL;
		$strTitle = 'Reaberto';
	}

	$chamado->fill($data)->save();

	$address = ['email@email.com'];
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
 //        'message' => 'Houve uma atualização no chamado número ' . $chamado->chaId,
 //    ]);
    
	$response->getbody()->write(json_encode($chamado));
	return $response;
});