<?php declare(strict_types=1);
namespace Rakitan\Controller;

use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Noodlehaus\Config;
use Plasticbrain\FlashMessages\FlashMessages;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rakitan\Database;
use Rakitan\Lib\Aura\QueryFactory;
use Rakitan\Lib\Auth;
use Rakitan\Lib\Template\Renderer;
use Rakitan\RouteDispatcher;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\RedirectResponse;

abstract class Base
{
    /** @var \Rakitan\Lib\Http\ServerRequest */
    protected $request;
    protected $response;
    protected $dispatcher;
    protected $view;
    protected $db;
    /* @var FlashMessages */
    protected $flash;
    protected $config;
    protected $auth;
    protected $query;

    public function __construct(Request $request, Response $response,
            RouteDispatcher $dispatcher, Renderer $view,
            FlashMessages $flash, Config $config,
            Database $db, Auth $auth, QueryFactory $query)
    {
        $this->request = $request;
        $this->response = $response;
        $this->dispatcher = $dispatcher;
        $this->view = $view;
        $this->config = $config;
        $this->flash = $flash;
        $this->db = $db;
        $this->auth = $auth;
        $this->query = $query;
        $this->view->engine->addData([
            'title' => 'Coba coba saja',
            'flash' => $this->flash,
            'auth' => $auth,
            'module' => $this->request->getAttribute('_module'),
            'controller' => $this->request->getAttribute('_controller'),
            'action' => $this->request->getAttribute('_action'),
        ]);
    }

    /**
     * Redirect to url
     * @param string $url format seperti di route.php, e.g. /login
     * @param array $cookies optional, cookies to be added to next request
     * @return RedirectResponse
     */
    public function redirect($url, $cookies=[])
    {
        $response = new RedirectResponse($this->config->get('basePath').$url);
        foreach ($cookies as $cookie) {
            if (!$cookie instanceof SetCookie) {
                throw new \InvalidArgumentException('Parameter is not a valid SetCookie object.');
            }
            $response = FigResponseCookies::set($response, $cookie);
        }
        return $response;
    }

    /**
     * Forward to another controller
     * @param string $target e.g. Home::error
     * @param array $params
     * @return Response object
     */
    public function forward($target, $params=[])
    {
        return $this->dispatcher->dispatch($target, $params);
    }
}