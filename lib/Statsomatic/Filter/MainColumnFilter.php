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
        $q->where(
            $q->expr->{$this->comperator}(
                $q->currentMainAlias() . '.' . $this->provider . '_' . $this->variable,
                $q->bindValue($this->value, null, $this->type)
            )
        );
    }
}
