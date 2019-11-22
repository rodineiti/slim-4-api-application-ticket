<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\User;

$model = new User();

$app->get('/api/v1/users', function (Request $request, Response $response, array $args) use ($model) {
	$users = $model->all();
	$response->getbody()->write(json_encode($users));
	return $response;
});

$app->post('/api/v1/users', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$user = $model->create($data);
	$users_count = $model->count();
	$response->getbody()->write(json_encode($user));
	return $response;
});

$app->get('/api/v1/users/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$user = $model->findOrFail($args['id']);
	$users_count = $model->count();
	$response->getbody()->write(json_encode($user));
	return $response;
});

$app->put('/api/v1/users/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$data = $request->getParsedBody();
	$user = $model->findOrFail($args['id']);
	$user->update($data);
	$users_count = $model->count();
	$response->getbody()->write(json_encode($user));
	return $response;
});

$app->delete('/api/v1/users/{id}', function (Request $request, Response $response, array $args) use ($model) {
	$user = $model->findOrFail($args['id']);
	$user->delete();
	$users_count = $model->count();
	$response->getbody()->write(json_encode($user));
	return $response;
});