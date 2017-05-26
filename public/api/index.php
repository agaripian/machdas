<?php

// load composers autoloader
require __DIR__ . '/../../vendor/autoload.php';

// load config
$config = require __DIR__ . '/../../config.php';

//require_once('./../../backend/Behance/Middleware/TokenAuth.php');

// create DI-container
$container = new \Slim\Container([
    'settings' => [
        'displayErrorDetails'               => $config['debugging'],
        'db'                                => $config['db'],
        'determineRouteBeforeAppMiddleware' => true

    ]
]);

// prepare eloquent
$container['db'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager();
    $capsule->addConnection($container['settings']['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    return $capsule;
};

// add error handler
$container['notFoundHandler'] = function () {
    /** @noinspection PhpUnusedParameterInspection */
    /** @noinspection PhpDocSignatureInspection */
    return function ($request, \Slim\Http\Response $response) {
        return $response
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->withJson(['message' => 'Not Found']);
    };
};

$container['notAllowedHandler'] = function () {
    /** @noinspection PhpUnusedParameterInspection */
    /** @noinspection PhpDocSignatureInspection */
    return function ($request, \Slim\Http\Response $response, array $methods) {
        return $response
            ->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withJson(['message' => 'Method must be one of: ' . implode(', ', $methods)]);
    };
};

$container['errorHandler'] = function ($container) {
    /** @noinspection PhpUnusedParameterInspection */
    /** @noinspection PhpDocSignatureInspection */
    return function ($request, \Slim\Http\Response $response, Exception $exception) use ($container) {
        $payload = ['message' => 'Something went wrong!'];

        // if debugging enabled add trace to response
        if ($container['settings']['displayErrorDetails'] === true) {
            $payload['trace'] = $exception->getTrace();
        }

        return $response
            ->withStatus(500)
            ->withJson($payload);
    };
};

// create app
$app = new \Slim\App($container);

$app->add(new \Behance\Middleware\TokenAuth($app));

// routing
$app->group('/user', function () {
    /* @var \Slim\App $this */
    $this->post('/{id:\d+}/addimage', new \Behance\Action\User\AddImage($this));
    $this->get('/{id:\d+}/getallimages', new \Behance\Action\User\GetAllImages($this));
});

$app->group('/auth', function () {
    /* @var \Slim\App $this */
    $this->post('/login', \Behance\Action\Auth\Login::class);
    $this->post('/signup', \Behance\Action\User\Create::class);
});

// initialize eloquent
$container->get('db');

// start app
$app->run();
