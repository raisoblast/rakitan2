<?php declare(strict_types=1);
namespace Rakitan;

use Aura\Sql\ExtendedPdoInterface;

/**
 * multiple database connection support
 * @author arifk
 *
 */
class Database implements \IteratorAggregate
{
    protected $connections;

    public function getIterator()
    {
        return new \ArrayIterator($this->connections);
    }

    public function addConnection(string $name, ExtendedPdoInterface $conn)
    {
        $this->connections[$name] = $conn;
    }

    /**
     * ambil koneksi berdasarkan nama di config.php
     * @param string $name
     * @return ExtendedPdoInterface
     */
    public function getConnection(string $name='default')
    {
        return $this->connections[$name];
    }
}