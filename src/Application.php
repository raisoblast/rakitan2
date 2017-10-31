<?php declare(strict_types=1);
namespace Rakitan;

use Psr\Http\Message\ServerRequestInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;

/**
 * The Rakitan Framework
 *
 * @author Arif Kurniawan <arifk97@gmail.com>
 */

class Application implements MiddlewareInterface
{
    protected $response;
    /* @var \AltoRouter */
    protected $router;
    protected $dispatcher;
    protected $container;

    public function __construct(ResponseInterface $response, RouteDispatcher $dispatcher,
            \AltoRouter $router, ContainerInterface $container)
    {
        $this->response = $response;
        $this->router = $router;
        $this->dispatcher = $dispatcher;
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $match = $this->router->match($request->getUri()->getPath());
        if ($match) {
            list($module, $controller, $action) = $this->processRoute($match);
            $request = $request->withAttribute('_module', $module)
                ->withAttribute('_controller', $controller)
                ->withAttribute('_action', $action);
            $this->container->set(ServerRequestInterface::class, $request);
            return $this->dispatcher->dispatch($match['target'], $match['params']);
        } else {
            return $this->dispatcher->dispatch('Home::error');
        }
    }

    private function processRoute(&$match)
    {
        $module = '';
        $controller = '';
        $action = '';
        if ($match['name'] == 'default-route') {
            $controller = $match['params']['controller'];
            $action = isset($match['params']['action']) ? $match['params']['action'] : 'index';
            $match['target'] = ucfirst($controller).'::'.$action;
            $match['params'] = array_slice($match['params'], 2); // hapus yg sudah diproses (controller+action)
        } elseif ($match['name'] == 'default-module-route') {
            $module = $match['params']['module'];
            $controller = $match['params']['controller'];
            $action = $match['params']['action'];
            $match['target'] = ucfirst($module).'\\'.ucfirst($controller).'::'.$action;
            $match['params'] = array_slice($match['params'], 3); // hapus yg sudah diproses (module+controller+action)
        } else {
            list($controller, $action) = explode('::', $match['target']);
            $controller = strtolower($controller);
            if (strpos($controller, '\\') !== false) {
                list($module, $controller) = explode('\\', $controller, 2);
            }
        }
        return [$module, $controller, $action];
    }

}