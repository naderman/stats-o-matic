<?php
/**
*
* @package Statsomatic\Filter
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class Statsomatic_Filter_Factory
{
    static public $comperators = array(
        'eq' => true,
        'neq' => true,
        'gt' => true,
        'gte' => true,
        'lt' => true,
        'lte' => true,
    );

    protected $dataDefinitions;

    public function __construct(Statsomatic_Data_Definitions $dataDefinitions)
    {
        $this->dataDefinitions = $dataDefinitions;
    }

    public function filtersFromArray(array $filterDefinitions)
    {
        $filters = array();

        foreach ($filterDefinitions as $filter)
        {
            $filters[] = $this->filterFromArray($filter);
        }

        return $filters;
    }

    public function filterFromArray(array $filter)
    {
        $class = $this->selectFilterClass($filter['type']);
        $comperator = $this->selectComperator($filter['comperator']);

        $variable = $this->lookupVariable($filter['provider'], $filter['variable'], $filter['type']);

        return new $class(
            $variable->getProvider(),
            $variable->getName(),
            $comperator,
            $variable->castValue($filter['value']),
            $variable->getPdoType()
        );
    }

    public function selectFilterClass($type)
    {
        $class = 'Statsomatic_Filter_' . $type . 'Filter';

        if (class_exists($class))
        {
            return $class;
        }

        throw new Exception(sprintf("Unknown filter type '%s'.", $type));
    }

    public function selectComperator($comperator)
    {
        if (isset(self::$comperators[$comperator]))
        {
            return $comperator;
        }

        throw new Exception(sprintf("Unknown comperator '%s'.", $comperator));
    }

    public function lookupVariable($provider, $variable, $filterType)
    {
        $variable = $this->dataDefinitions->lookup($provider, $variable);

        if (preg_match('/^' . $variable->getScopeName() . '/', $filterType))
        {
            return $variable;
        }

        throw new Exception(sprintf("Filter type ('%s') does not match variable scope: '%s'", $filterType, $variable->getScopeName()));
    }
}
