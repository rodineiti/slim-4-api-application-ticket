<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Status;

$model = new Status();

// ROTAS ADMIN
$app->get('/api/v1/statuses', function (Request $request, Response $response, array $args) use ($model) {
	$statuses = $model->all();
	$response->getbody()->write(json_encode($statuses));
	return $response;
});

$app->post('/api/v1/statuses', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$status = $model->create($data);
	$response->getbody()->write(json_encode($status));
	return $response;
});

$app->get('/api/v1/statuses/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$status = $model->findOrFail($args['id']);
	$response->getbody()->write(json_encode($status));
	return $response;
});

$app->put('/api/v1/statuses/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$status = $model->findOrFail($args['id']);
	$status->update($data);
	$response->getbody()->write(json_encode($status));
	return $response;
});

$app->delete('/api/v1/statuses/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$status = $model->findOrFail($args['id']);
	$status->delete();
	$response->getbody()->write(json_encode($status));
	return $response;
});

// ROTAS CLIENTE
$app->get('/api/v2/{clientToken}/statuses', function (Request $request, Response $response, array $args) use ($model) {
	$statuses = $model->all();
	$response->getbody()->write(json_encode($statuses));
	return $response;
});

$app->post('/api/v2/{clientToken}/statuses', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$status = $model->create($data);
	$response->getbody()->write(json_encode($status));
	return $response;
});

$app->get('/api/v2/{clientToken}/statuses/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$status = $model->findOrFail($args['id']);
	$response->getbody()->write(json_encode($status));
	return $response;
});

$app->put('/api/v2/{clientToken}/statuses/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$status = $model->findOrFail($args['id']);
	$status->update($data);
	$response->getbody()->write(json_encode($status));
	return $response;
});

$app->delete('/api/v2/{clientToken}/statuses/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$status = $model->findOrFail($args['id']);
	$status->delete();
	$response->getbody()->write(json_encode($status));
	return $response;
});