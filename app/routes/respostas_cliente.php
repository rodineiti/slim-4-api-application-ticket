<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Chamado;
use App\Models\Resposta;
use App\Models\Mail;
use Aura\Filter\FilterFactory;

$modelChamado = new Chamado();
$model = new Resposta();
$ObjMail = new Mail();

$app->post('/api/v2/{clientToken}/respostas', function (Request $request, Response $response, array $args) use ($model, $ObjMail, $modelChamado) {
	
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
    $filter->validate('chaId')->is('int');
    $filter->validate('resNome')->is('string');
    $filter->validate('resNome')->isNot('int');
    $filter->validate('resConteudo')->is('string');
    
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
		$data['usuId'] = 0;
	}

	$resposta = $model->create($data);
	$chamado = $modelChamado->where('chaToken', $dataToken['clientToken'])->findOrFail($resposta->chaId);
	$chamados_ativo = $modelChamado->where('chaToken', $dataToken['clientToken'])->whereNull('completed_at')->count();

	$address = ['email@email.com'];
	$subject = 'O chamado número ' . $chamado->chaId . ' foi respondito';
	$bodyHtml = '
		<h1>Olá! Houve uma resposta no chamado.</h1>
		<p>Seguem os dados do chamado:</p>
		<p>Chamado: #'.$chamado->chaId.' </p>
		<p>Chamado: '.$chamado->chaDescricao.' </p>
		<p>Cliente: '.$chamado->chaNomeCliente.' </p>
		<p>Prioridade: '.$chamado->prioridade->priDescricao.' </p>
		<p>Tipo: '.$chamado->tipo->tipDescricao.' </p>
		<p>Data: '.$chamado->created_at.' </p>
	';

	if ($this->get('settings')['send_mail']['reply_chamado']) {
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
 //        'message' => 'Houve uma resposta no chamado número ' . $chamado->chaId,
 //    ]);

	$response->getbody()->write(json_encode($resposta));
    return $response;
});

$app->delete('/api/v2/{clientToken}/respostas/{id}', function (Request $request, Response $response, array $args) use ($model, $ObjMail) {

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

	$resposta = $model->join('tbchamados', 'tbchamados.chaId', '=', 'tbrespostas.chaId')->findOrFail($args['id']);
	$resposta->delete();
    
	$response->getbody()->write(json_encode($resposta));
    return $response;
});