<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Area;

$model = new Area();

// ROTAS ADMIN
$app->get('/api/v1/areas', function (Request $request, Response $response, array $args) use ($model) {
	$areas = $model->all();
	$response->getbody()->write(json_encode($areas));
	return $response;
});

$app->post('/api/v1/areas', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$area = $model->create($data);
	$response->getbody()->write(json_encode($area));
	return $response;
});

$app->get('/api/v1/areas/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$area = $model->findOrFail($args['id']);
	$response->getbody()->write(json_encode($area));
	return $response;
});

$app->put('/api/v1/areas/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$area = $model->findOrFail($args['id']);
	$area->update($data);
	$response->getbody()->write(json_encode($area));
	return $response;
});

$app->delete('/api/v1/areas/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$area = $model->findOrFail($args['id']);
	$area->delete();
	$response->getbody()->write(json_encode($area));
	return $response;
});

// ROTAS CLIENTE
$app->get('/api/v2/{clientToken}/areas', function (Request $request, Response $response, array $args) use ($model) {
	$areas = $model->all();
	$response->getbody()->write(json_encode($areas));
	return $response;
});

$app->post('/api/v2/{clientToken}/areas', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$area = $model->create($data);
	$response->getbody()->write(json_encode($area));
	return $response;
});

$app->get('/api/v2/{clientToken}/areas/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$area = $model->findOrFail($args['id']);
	$response->getbody()->write(json_encode($area));
	return $response;
});

$app->put('/api/v2/{clientToken}/areas/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$area = $model->findOrFail($args['id']);
	$area->update($data);
	$response->getbody()->write(json_encode($area));
	return $response;
});

$app->delete('/api/v2/{clientToken}/areas/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$area = $model->findOrFail($args['id']);
	$area->delete();
	$response->getbody()->write(json_encode($area));
	return $response;
});