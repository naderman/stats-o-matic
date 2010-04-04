<?php
/**
*
* @package Statsomatic
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class Statsomatic_Aggregator
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function apply($expression)
    {
        switch ($this->name)
        {
            case 'ID':
                return $expression;

            case 'MAX':
            case 'MIN':
            case 'AVG':
            case 'COUNT':
                return $this->name . '(' . $expression . ')';
        }

        throw new Exception(sprintf("Unknown Aggregator '%s'", $this->name));
    }

    public function isId()
    {
        return $this->name == 'ID';
    }
}
