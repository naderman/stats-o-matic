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
    private $provider;
    private $variable;
    private $comperator;
    private $value;
    private $type;

    public function __construct($provider, $variable, $comperator, $value, $type = PDO::PARAM_STR)
    {
        $this->provider = $provider;
        $this->variable = $variable;
        $this->comperator = $comperator;
        $this->value = $value;
        $this->type = $type;
    }

    public function apply(ezcQuerySelect $q)
    {
        $value_column = ($this->type == PDO::PARAM_INT) ? 'value_int' : 'value_string';

        $qDetail = $q->subSelect();
        $qDetail
            ->from($q->nextDetailsAlias())
            ->select($q->detailColumn('entry_id'))
            ->where(
                $q->expr->eq(
                    $q->detailColumn('provider'),
                    $q->bindValue($this->provider, null, PDO::PARAM_STR)
                ),
                $q->expr->eq(
                    $q->detailColumn('variable'),
                    $q->bindValue($this->variable, null, PDO::PARAM_STR)
                ),
                $q->expr->{$this->comperator}(
                    $q->detailColumn($value_column),
                    $q->bindValue($this->value, null, $this->type)
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
