<?php declare(strict_types=1);

namespace Rakitan;

use Psr\Container\ContainerInterface;
use Middlewares\Utils\CallableHandler;
use Psr\Http\Message\ResponseInterface;

class RouteDispatcher
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function dispatch($target, $params=[])
    {
        $callable = $this->createController($target);
        // jika callable berupa obyek response, maka langsung return
        if ($callable instanceof ResponseInterface) {
            return $callable;
        }
        return CallableHandler::execute($callable, $params);
    }

    /**
     * membuat instance dari class yg dikonsumsi oleh CallableHandler
     * @author arifk
     * @param string $target route yg ditemukan
     * @throws \Exception
     * @return mixed
     */
    public function createController($target)
    {
        $matches = null;
        if (false === preg_match('/::/', $target, $matches)) {
            throw new \Exception('Invalid route: '.$target, 404);
        }
        list($class, $method) = explode($matches[0], $target, 2);
        $fullClass = 'Rakitan\\Controller\\'.$class;
        if (!class_exists($fullClass)) {
            // find class in module
            $tmpClass = $fullClass;
            $fullClass = 'Rakitan\\Controller\\'.$class.'\\'.ucfirst($method);
            if (!class_exists($fullClass)) {
                throw new \Exception(sprintf('Controller "%s" or "%s" does not exist.', $tmpClass, $fullClass), 404);
            } else {
                $method = 'index';
            }
        }
        $instance = $this->container->get($fullClass);
        if (!method_exists($instance, $method)) {
            throw new \Exception(sprintf('Action "%s" not found on controller "%s"', $method, $class), 404);
        }
        if (method_exists($instance, '_init')) {
            $return = $instance->_init();
            if ($return instanceof ResponseInterface) {
                return $return;
            }
        }
        return [$instance, $method];
    }
}
