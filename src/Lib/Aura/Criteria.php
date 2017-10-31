<?php declare(strict_types=1);
namespace Rakitan\Lib\Aura;

use Aura\SqlQuery\Common\Select;

/**
 * modif: ambil where, limit dan offset
 * @author arifk
 *
 */
class Criteria extends Select
{
    /** @var $builder \Aura\SqlQuery\Common\SelectBuilder */
    protected $builder;

    /**
     * buildWhere tanpa klausa WHERE
     * @return string
     */
    public function buildWhere($andOr)
    {
        if (!$this->where) {
            $andOr = '';
        }
        return PHP_EOL . $andOr . $this->builder->indent($this->where);
    }

    public function build($addWhere=FALSE, $andOr='AND')
    {
        $where = $this->buildWhere($andOr);
        if ($addWhere) {
            $where = $this->builder->buildWhere($this->where);
        }
        return $where
            . $this->builder->buildGroupBy($this->group_by)
            . $this->builder->buildHaving($this->having)
            . $this->builder->buildOrderBy($this->order_by)
            . $this->builder->buildLimitOffset($this->limit, $this->offset);
    }

}
