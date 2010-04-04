<?php
/**
*
* @package Statsomatic
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

abstract class Statsomatic_QueryParameterFactory
{
    protected $dataDefinitions;

    public function __construct(Statsomatic_Data_Definitions $dataDefinitions)
    {
        $this->dataDefinitions = $dataDefinitions;
    }

    public function paramsFromArray(array $paramDefinitions)
    {
        $params = array();

        foreach ($paramDefinitions as $param)
        {
            $params[] = $this->paramFromArray($param);
        }

        return $params;
    }

    public function paramFromArray(array $param)
    {
        if (!isset($param['type']))
        {
            $param['type'] = '';
        }

        $variable = $this->dataDefinitions->lookup($param['provider'], $param['variable']);
        $class = $this->selectParamClass($variable->getScopeName() . $param['type']);

        return $this->createQueryParamInstance($class, $param, $variable);
    }

    public function selectParamClass($type)
    {
        $class = $this->getParamClassName($type);

        if (class_exists($class))
        {
            return $class;
        }

        throw new Exception(sprintf("Unknown parameter type '%s'.", $type));
    }

    abstract protected function createQueryParamInstance($class, $param, $variable);
    abstract protected function getParamClassName($type);
}
