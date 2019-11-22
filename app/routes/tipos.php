<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Tipo;

$model = new Tipo();

// ROTAS ADMIN
$app->get('/api/v1/tipos', function (Request $request, Response $response, array $args) use ($model) {
	$tipos = $model->all();
	$response->getbody()->write(json_encode($tipos));
	return $response;
});

$app->post('/api/v1/tipos', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$tipo = $model->create($data);
	$response->getbody()->write(json_encode($tipo));
	return $response;
});

$app->get('/api/v1/tipos/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$tipo = $model->findOrFail($args['id']);
	$response->getbody()->write(json_encode($tipo));
	return $response;
});

$app->put('/api/v1/tipos/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$tipo = $model->findOrFail($args['id']);
	$tipo->update($data);
	$response->getbody()->write(json_encode($tipo));
	return $response;
});

$app->delete('/api/v1/tipos/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$tipo = $model->findOrFail($args['id']);
	$tipo->delete();
	$response->getbody()->write(json_encode($tipo));
	return $response;
});


// ROTAS CLIENTE
$app->get('/api/v2/{clientToken}/tipos', function (Request $request, Response $response, array $args) use ($model) {
	$tipos = $model->all();
	$response->getbody()->write(json_encode($tipos));
	return $response;
});

$app->post('/api/v2/{clientToken}/tipos', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$tipo = $model->create($data);
	$response->getbody()->write(json_encode($tipo));
	return $response;
});

$app->get('/api/v2/{clientToken}/tipos/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$tipo = $model->findOrFail($args['id']);
	$response->getbody()->write(json_encode($tipo));
	return $response;
});

$app->put('/api/v2/{clientToken}/tipos/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$tipo = $model->findOrFail($args['id']);
	$tipo->update($data);
	$response->getbody()->write(json_encode($tipo));
	return $response;
});

$app->delete('/api/v2/{clientToken}/tipos/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$tipo = $model->findOrFail($args['id']);
	$tipo->delete();
	$response->getbody()->write(json_encode($tipo));
	return $response;
});