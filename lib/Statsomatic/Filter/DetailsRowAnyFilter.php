<?php
/**
*
* @package Statsomatic\Filter
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class Statsomatic_Filter_DetailsRowAnyFilter implements Statsomatic_FilterInterface
{
    private $variable;
    private $comperator;
    private $value;

    public function __construct(Statsomatic_Variable $variable, $comperator, $value)
    {
        $this->variable = $variable;
        $this->comperator = $comperator;
        $this->value = $value;
    }

    public function apply(Statsomatic_Query $q)
    {
        $valueColumn = $this->variable->getValueColumn();;

        $qDetail = $q->subSelect();
        $qDetail
            ->from($q->nextDetailsAlias())
            ->select($q->detailColumn('entry_id'))
            ->where(
                $q->expr->eq(
                    $q->detailColumn('provider'),
                    $q->bindValue($this->variable->getProvider(), null, PDO::PARAM_STR)
                ),
                $q->expr->eq(
                    $q->detailColumn('variable'),
                    $q->bindValue($this->variable->getName(), null, PDO::PARAM_STR)
                ),
                $q->expr->{$this->comperator}(
                    $q->detailColumn($valueColumn),
                    $q->bindValue($this->value, null, $this->variable->getPdoType())
                )
            )
        ;

        $q->where(
            $q->expr->in(
                $q->mainColumn('entry_id'),
                $qDetail
            )
        );
    }
}
