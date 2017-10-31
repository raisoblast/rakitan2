<?php declare(strict_types=1);
namespace Rakitan\Lib\Propel;

use Propel\Runtime\Adapter\AdapterInterface;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\ColumnMap;

class NoneAdapter implements AdapterInterface
{
    public function getConnection($conparams)
    {
        return new NoneConnection();
    }

    public function setCharset(ConnectionInterface $con, $charset) {}
    public function ignoreCaseInOrderBy($in) {}
    public function getStringDelimiter() {}
    public function concatString($s1, $s2) {}
    public function subString($s, $pos, $len) {}
    public function strLength($s) {}
    public function quoteIdentifier($text) {}
    public function quoteIdentifierTable($table) {}
    public function quote($text) {}
    public function isGetIdBeforeInsert() {}
    public function isGetIdAfterInsert() {}
    public function getDeleteFromClause(Criteria $criteria, $tableName) {}
    public function getId(ConnectionInterface $con, $name = null) {}
    public function formatTemporalValue($value, ColumnMap $cMap) {}
    public function getTimestampFormatter() {}
    public function getDateFormatter() {}
    public function getTimeFormatter() {}
    public function getGroupBy(Criteria $criteria) {}
}