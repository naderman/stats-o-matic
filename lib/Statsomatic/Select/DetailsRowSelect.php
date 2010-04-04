<?php
/**
*
* @package Statsomatic\Select
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class Statsomatic_Select_DetailsRowSelect implements Statsomatic_SelectInterface
{
    private $variable;
    private $aggregator;

    public function __construct(Statsomatic_Variable $variable, Statsomatic_Aggregator $aggregator)
    {
        $this->variable = $variable;
        $this->aggregator = $aggregator;
    }

    public function apply(Statsomatic_Query $q)
    {
        $q->addLeftJoin($q->nextDetailsAlias(), $q->mainColumn('entry_id'), $q->detailColumn('entry_id'));

        $selectExpression = $this->aggregator->apply($q->detailColumn($this->variable->getValueColumn()));

        $q
            ->select(
                $q->nextValueAlias(
                    $selectExpression
                )
            )
            ->where(
                $q->expr->eq(
                    $q->detailColumn('provider'),
                    $q->bindValue($this->variable->getProvider(), null, PDO::PARAM_STR)
                ),
                $q->expr->eq(
                    $q->detailColumn('variable'),
                    $q->bindValue($this->variable->getName(), null, PDO::PARAM_STR)
                )
            )
            ->orderBy(
                $selectExpression, ezcQuerySelect::DESC
            );

        if ($this->aggregator->isId())
        {
            $q->groupBy(
                $q->detailColumn($this->variable->getValueColumn())
            );
        }
    }
}
