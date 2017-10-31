<?php declare(strict_types=1);
namespace Rakitan\Model\Manager;

use Rakitan\Database;
use Rakitan\Lib\Aura\QueryFactory;

/**
 * base model manager
 * @author arifk
 */
abstract class BaseManager
{
    protected $db;
    protected $query;

    public function __construct(Database $db, QueryFactory $query)
    {
        $this->db = $db;
        $this->query = $query;
    }

}