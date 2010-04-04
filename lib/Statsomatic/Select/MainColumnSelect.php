<?php
/**
*
* @package Statsomatic\Select
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class Statsomatic_Select_MainColumnSelect implements Statsomatic_SelectInterface
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
        $selectExpression = $this->aggregator->apply($q->mainColumn($this->variable->getColumnName()));

        $q
            ->select(
                $q->nextValueAlias(
                    $selectExpression
                )
            )
            ->orderBy(
                $selectExpression, ezcQuerySelect::DESC
            );

        if ($this->aggregator->isId())
        {
            $q->groupBy(
                $q->mainColumn($this->variable->getColumnName())
            );
        }
    }
}
