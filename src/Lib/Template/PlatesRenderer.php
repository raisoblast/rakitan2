<?php declare(strict_types=1);

namespace Rakitan\Lib\Template;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;

class PlatesRenderer implements Renderer
{
    public $engine;

    public function __construct(Engine $engine, ExtensionInterface $extension)
    {
        $this->engine = $engine;
        $extension->register($this->engine);
    }

    public function render($template, $data = [], $status = 200)
    {
        $content = $this->engine->render($template, $data);
        $response = new Response();
        $response->getBody()->write($content);
        return $response->withStatus($status);
    }

    public function renderJson($data = [], $status = 200)
    {
        $response = new JsonResponse($data);
        return $response->withStatus($status);
    }

}