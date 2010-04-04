<?php
/**
*
* @package Statsomatic\Filter
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class Statsomatic_Filter_Factory extends Statsomatic_QueryParameterFactory
{
    static public $comperators = array(
        'eq' => true,
        'neq' => true,
        'gt' => true,
        'gte' => true,
        'lt' => true,
        'lte' => true,
    );

    protected function createQueryParamInstance($class, $filter, $variable)
    {
        $comperator = $this->selectComperator($filter['comperator']);

        return new $class(
            $variable,
            $comperator,
            $variable->castValue($filter['value'])
        );
    }

    protected function getParamClassName($type)
    {
        return 'Statsomatic_Filter_' . $type . 'Filter';
    }

    public function selectComperator($comperator)
    {
        if (isset(self::$comperators[$comperator]))
        {
            return $comperator;
        }

        throw new Exception(sprintf("Unknown comperator '%s'.", $comperator));
    }
}
