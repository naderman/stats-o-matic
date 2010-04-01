<?php
/**
*
* @package Statsomatic
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class Statsomatic_Variable
{
    const MAIN = 1;
    const DETAILS = 2;

    static public $types = array(
        'int' => true,
        'varchar' => true,
        'text' => true,
    );

    protected $provider;
    protected $name;
    protected $type;
    protected $isArray;
    protected $scope;

    public function __construct($provider, $name, $type, $isArray = false, $scope = Statsomatic_Variable::DETAILS)
    {
        $this->provider = $provider;
        $this->name = $name;
        $this->type = $type;
        $this->isArray = (bool) $isArray;
        $this->scope = $scope;

        if (!isset(Statsomatic_Variable::$types[$type]))
        {
            throw new Exception(sprintf("Unknown type '%s'.", $type));
        }

        if ($this->scope == Statsomatic_Variable::MAIN && $isArray)
        {
            throw new Exception(sprintf("Main variable %s %s must not be an array.", $provider, $scope));
        }
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function isArray()
    {
        return $this->isArray;
    }

    public function getScopeName()
    {
        switch ($this->scope)
        {
            case Statsomatic_Variable::MAIN:
                return 'MainColumn';

            case Statsomatic_Variable::DETAILS:
                return 'DetailsRow';
        }
    }

    public function getPdoType()
    {
        switch ($this->type)
        {
            case 'int':
                return PDO::PARAM_INT;

            case 'text':
            case 'varchar':
                return PDO::PARAM_STR;
        }
    }

    public function castValue($value)
    {
        if (is_array($value))
        {
            $value = '';
        }

        switch ($this->type)
        {
            case 'int':
                return (int) $value;

            case 'text':
            case 'varchar':
                return '' . $value;
        }
    }

    public function getValueColumn()
    {
        switch ($this->type)
        {
            case 'int':
                return 'value_int';

            case 'text':
            case 'varchar':
                return 'value_string';
        }
    }
}
