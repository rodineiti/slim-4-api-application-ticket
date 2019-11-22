<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Prioridade;

$model = new Prioridade();

// ROTAS ADMIN
$app->get('/api/v1/prioridades', function (Request $request, Response $response, array $args) use ($model) {
	$prioridades = $model->all();
	$response->getbody()->write(json_encode($prioridades));
	return $response;
});

$app->post('/api/v1/prioridades', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$prioridade = $model->create($data);
	$response->getbody()->write(json_encode($prioridade));
	return $response;
});

$app->get('/api/v1/prioridades/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$prioridade = $model->findOrFail($args['id']);
	$response->getbody()->write(json_encode($prioridade));
	return $response;
});

$app->put('/api/v1/prioridades/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$prioridade = $model->findOrFail($args['id']);
	$prioridade->update($data);
	$response->getbody()->write(json_encode($prioridade));
	return $response;
});

$app->delete('/api/v1/prioridades/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$prioridade = $model->findOrFail($args['id']);
	$prioridade->delete();
	$response->getbody()->write(json_encode($prioridade));
	return $response;
});

// ROTAS CLIENTE
$app->get('/api/v2/{clientToken}/prioridades', function (Request $request, Response $response, array $args) use ($model) {
	$prioridades = $model->all();
	$response->getbody()->write(json_encode($prioridades));
	return $response;
});

$app->post('/api/v2/{clientToken}/prioridades', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$prioridade = $model->create($data);
	$response->getbody()->write(json_encode($prioridade));
	return $response;
});

$app->get('/api/v2/{clientToken}/prioridades/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$prioridade = $model->findOrFail($args['id']);
	$response->getbody()->write(json_encode($prioridade));
	return $response;
});

$app->put('/api/v2/{clientToken}/prioridades/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$prioridade = $model->findOrFail($args['id']);
	$prioridade->update($data);
	$response->getbody()->write(json_encode($prioridade));
	return $response;
});

$app->delete('/api/v2/{clientToken}/prioridades/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$prioridade = $model->findOrFail($args['id']);
	$prioridade->delete();
	$response->getbody()->write(json_encode($prioridade));
	return $response;
});