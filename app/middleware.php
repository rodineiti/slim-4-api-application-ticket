<?php
declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use App\Application\Middleware\JsonBodyParserMiddleware;
use Tuupola\Middleware\JwtAuthentication;
use App\Models\Bootstrap;
use Slim\App;

return function (App $app) {
	$app->add(SessionMiddleware::class);
	$app->add(JsonBodyParserMiddleware::class);

	$app->add(new JwtAuthentication([
		"path" => ["/api"],
		"ignore" => ["/api"],
    	"secret" => "supersecretkeyyoushouldnotcommittogithub",
    	"error" => function ($response, $arguments) {
	        $data["status"] = "error";
	        $data["message"] = $arguments["message"];
	        return $response
	            ->withHeader("Content-Type", "application/json")
	            ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
	    }
	]));
	
	$container = $app->getContainer();    
    Bootstrap::load($container);
};
