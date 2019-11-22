<?php
declare(strict_types=1);

// use App\Application\Actions\User\ListUsersAction;
// use App\Application\Actions\User\ViewUserAction;
// use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\App;

return function (App $app) {

    $app->options('/{routes:.+}', function ($req, $res, $args) {
        return $res;
    });

	// ADMIN
    require __DIR__ . '/routes/users.php';
    require __DIR__ . '/routes/tipos.php';
    require __DIR__ . '/routes/areas.php';
    require __DIR__ . '/routes/prioridades.php';
    require __DIR__ . '/routes/statuses.php';
    require __DIR__ . '/routes/chamados.php';
    require __DIR__ . '/routes/respostas.php';

    // CLIENT
    require __DIR__ . '/routes/chamados_cliente.php';
    require __DIR__ . '/routes/respostas_cliente.php';

    // $app->get('/', function (Request $request, Response $response) {
    //     // $response->getBody()->write('Hello world!');
    //     $response->getbody()->write(json_encode(User::select('usuId', 'usuNome', 'usuEmail', 'usuStatus', 'usuAtivo')->with(['chamados'])->paginate()));
    //     return $response;
    // });

    // $app->group('/users', function (Group $group) {
    // 	$group->get('', ListUsersAction::class);
    //     $group->get('/{id}', ViewUserAction::class);
    // });
};
