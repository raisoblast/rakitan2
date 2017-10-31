<?php declare(strict_types=1);

namespace Rakitan\Lib\Template;

interface Renderer
{
    public function render($template, $data = [], $status = 200);
    public function renderJson($data = [], $status = 200);
}