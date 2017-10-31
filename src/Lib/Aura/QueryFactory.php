<?php declare(strict_types=1);
namespace Rakitan\Lib\Aura;

/**
 * generate sql setelah WHERE
 * @author arifk
 *
 */
class QueryFactory extends \Aura\SqlQuery\QueryFactory
{
    public function newCriteria()
    {
        return new Criteria($this->getQuoter(), $this->newBuilder('Select'));
    }

}