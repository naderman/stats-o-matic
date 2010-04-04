<?php
/**
*
* @package Statsomatic\Select
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class Statsomatic_Select_Factory extends Statsomatic_QueryParameterFactory
{
    protected function createQueryParamInstance($class, $select, $variable)
    {
        return new $class($variable, new Statsomatic_Aggregator($select['aggregator']));
    }

    protected function getParamClassName($type)
    {
        return 'Statsomatic_Select_' . $type . 'Select';
    }
}
