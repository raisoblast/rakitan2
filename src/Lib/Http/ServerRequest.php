<?php declare(strict_types=1);
namespace Rakitan\Lib\Http;

use Zend\Diactoros\ServerRequest as DiactorosServerRequest;

class ServerRequest extends DiactorosServerRequest
{
    /**
     * mengambil variable dari attributes/queryParams/parsedBody
     * @param strin $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default=NULL)
    {
        if ($this !== $result = $this->getAttribute($key, $this)) {
            return $result;
        }
        $queryParams = $this->getQueryParams();
        if (array_key_exists($key, $queryParams)) {
            return $queryParams[$key];
        }
        $parsedBody = $this->getParsedBody();
        if (array_key_exists($key, $parsedBody)) {
            return $parsedBody[$key];
        }
        return $default;
    }

    /**
     * Cek metode request (GET, POST, dll)
     * @param string $method
     * @return boolean
     */
    public function isMethod($method)
    {
        return $this->getMethod() === strtoupper($method);
    }
}