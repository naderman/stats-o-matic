<?php
/**
*
* @package Statsomatic\Filter
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class Statsomatic_Select_DetailsRowSelect implements Statsomatic_SelectInterface
{
    private $variable;
    private $aggregator;

    public function __construct(Statsomatic_Variable $variable, Statsomatic_Aggregator $aggregator = null)
    {
        $this->variable = $variable;
        $this->aggregator = $aggregator;
    }

    public function apply(Statsomatic_Query $q)
    {
        $q->addLeftJoin($q->nextDetailsAlias(), $q->mainColumn('entry_id'), $q->detailColumn('entry_id'));

        $q
            ->select(
                $q->nextValueAlias(
                    $this->aggregator->apply($q->detailColumn($this->variable->getValueColumn()))
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
            ->groupBy(
                $q->detailColumn($this->variable->getValueColumn())
            );
    }
}
