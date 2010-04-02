<?php
/**
*
* @package Statsomatic\Filter
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class Statsomatic_Filter_MainColumnFilter implements Statsomatic_FilterInterface
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
        $q->where(
            $q->expr->{$this->comperator}(
                $q->mainColumn($this->variable->getColumnName()),
                $q->bindValue($this->value, null, $this->variable->getPdoType())
            )
        );
    }
}
