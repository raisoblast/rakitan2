<?php declare(strict_types=1);

use Aura\Session\SessionFactory;
use Aura\Sql\ExtendedPdo;
use League\Plates\Extension\ExtensionInterface;
use Noodlehaus\Config;
use Plasticbrain\FlashMessages\FlashMessages;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rakitan\Database;
use Rakitan\Lib\Aura\QueryFactory;
use Rakitan\Lib\Http\ServerRequestFactory;
use Rakitan\Lib\Template\MyPlatesExtension;
use Rakitan\Lib\Template\PlatesRenderer;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\EmitterInterface;
use Zend\Diactoros\Response\SapiEmitter;
use function DI\object;

require __DIR__ . '/../vendor/autoload.php';

$config = include(__DIR__ . '/config.php');
$route = include_once(__DIR__ . '/route.php');

return [
    // http
    ServerRequestInterface::class => function() {
        return ServerRequestFactory::fromGlobals();
    },
    ResponseInterface::class => object(Response::class),

    // response emitter
    EmitterInterface::class => object(SapiEmitter::class),

    // router
    'AltoRouter' => object(AltoRouter::class)->method('addRoutes', $route),

    // template engine
    Rakitan\Lib\Template\Renderer::class => object(PlatesRenderer::class),
    League\Plates\Engine::class => object()
        ->method('setDirectory', __DIR__ . '/../templates' . ($config['theme'] ? '/'.$config['theme'] : '')),
    ExtensionInterface::class => object(MyPlatesExtension::class)
        ->constructor($config['basePath']),

    // database: aura.sql
    Database::class => function() use ($config) {
        $db = new Database;
        foreach ($config['db'] as $dbName => $dbConfig) {
            $con = new ExtendedPdo($dbConfig['dsn'], $dbConfig['username'], $dbConfig['password']);
            $db->addConnection($dbName, $con);
        }
        return $db;
    },

    // aura.sqlquery
    QueryFactory::class => object()->constructor('mysql')->lazy(),

    // flash
    FlashMessages::class => object()->lazy(),

    // configuration
    Config::class => object()->constructor(__DIR__.'/config.php')->lazy(),

    // session
    Aura\Session\Session::class => function() {
        $sessionFactory = new SessionFactory();
        return $sessionFactory->newInstance($_COOKIE);
    },
];
