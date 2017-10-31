<?php declare(strict_types=1);

use Aura\Sql\Profiler\Profiler;
use DI\ContainerBuilder;
use Middleland\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Rakitan\Database;
use Rakitan\Lib\Aura\TracyAuraSql;
use Tracy\Debugger;
use Zend\Diactoros\Response\EmitterInterface;

$config = include_once(__DIR__.'/../config/config.php');

require __DIR__ . '/../vendor/autoload.php';

session_start();
if ('dev' == $config['environment']) {
    Debugger::enable(Debugger::DEVELOPMENT);
    //Tracy\OutputDebugger::enable();
} elseif ('prod' == $config['environment']) {
    Debugger::enable(Debugger::PRODUCTION);
}

$builder = new ContainerBuilder;
$builder->addDefinitions(__DIR__ . '/../config/di.php');
$builder->useAnnotations(true);
$container = $builder->build();

if ('dev' == $config['environment']) {
    $dbs = $container->get(Database::class);
    foreach ($dbs as $name => $con) {
        $auraPanel = new TracyAuraSql($name);
        $profiler = new Profiler($auraPanel);
        $con->setProfiler($profiler);
        $con->getProfiler()->setActive(true);
        $con->getProfiler()->setLogFormat('{duration}|{function}|{statement}|{values}');
        Debugger::getBar()->addPanel($auraPanel, 'Tracy:AuraSql:'.$name);
    }
}

$request = $container->get(ServerRequestInterface::class);

$middleware = [
    new Middlewares\BasePath($config['basePath']),
    Rakitan\Application::class
];

$dispatcher = new Dispatcher($middleware, $container);
$response = $dispatcher->dispatch($request);

$emitter = $container->get(EmitterInterface::class);
$emitter->emit($response);
