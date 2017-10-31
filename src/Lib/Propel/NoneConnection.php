<?php declare(strict_types=1);
namespace Rakitan\Lib\Propel;

use Propel\Runtime\Connection\ConnectionInterface;

class NoneConnection implements ConnectionInterface
{
    public function setName($name) {}
    public function getName() { return ''; }
    public function beginTransaction() {}
    public function commit() {}
    public function rollBack() {}
    public function inTransaction() {}
    public function getAttribute($attribute) {}
    public function setAttribute($attribute, $value) {}
    public function lastInsertId($name = null) {}
    public function getSingleDataFetcher($data) {}
    public function getDataFetcher($data) {}
    public function transaction(callable $callable) {}
    public function exec($statement) {}
    public function prepare($statement, $driver_options = null) {}
    public function query($statement) {}
    public function quote($string, $parameter_type = \PDO::PARAM_STR) {}
}