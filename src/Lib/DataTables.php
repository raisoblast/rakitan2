<?php declare(strict_types=1);
namespace Rakitan\Lib;

use Aura\Sql\ExtendedPdoInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rakitan\Database;
use Rakitan\Lib\Aura\Criteria;

/**
 * helper untuk memudahkan penggunaan datatables
 * @author arifk
 *
 */
class DataTables
{
    /** @var \Rakitan\Lib\Http\ServerRequest */
    protected $request;
    protected $database;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * execute sql dan criteria dari queryParams ($_GET)
     * @param Criteria $criteria
     */
    public function execute(ExtendedPdoInterface $db, $sql, $bind=[], Criteria $criteria)
    {
        $start = $this->request->get('start');
        $length = $this->request->get('length');

        $stmt = $db->perform($sql, $bind);
        $rowTotal = $stmt->rowCount();

        $sqlFilter = $sql . $criteria->build();
        $bind = array_merge($bind, $criteria->getBindValues());
        $rowFiltered = $db->fetchAffected($sqlFilter, $bind);

        $criteria->limit($length)->offset($start);
        $sqlLimit = $sql . $criteria->build();
        $rows = $db->fetchAll($sqlLimit, $bind);

        return [$rowTotal, $rowFiltered, $rows];
    }
}