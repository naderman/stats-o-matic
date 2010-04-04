<?php
/**
*
* @package tests
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once 'lib/autoload.php';

class Statsomatic_VariableTest extends PHPUnit_Framework_TestCase
{
    public function testAccessors()
    {
        $variable = new Statsomatic_Variable('foo', 'bar', 'int', false, Statsomatic_Variable::MAIN);

        $this->assertEquals('foo', $variable->getProvider());
        $this->assertEquals('bar', $variable->getName());
        $this->assertEquals('int', $variable->getType());
        $this->assertEquals(false, $variable->isArray());
        $this->assertEquals('MainColumn', $variable->getScopeName());
        $this->assertEquals('foo_bar', $variable->getColumnName());
    }

    public function testGlobalColumn()
    {
        $variable = new Statsomatic_Variable(false, 'bar', 'int', false, Statsomatic_Variable::MAIN);

        $this->assertEquals(false, $variable->getProvider());
        $this->assertEquals('bar', $variable->getColumnName());
    }

    static public function dataTypes()
    {
        return array(
            array('int', PDO::PARAM_INT, 'value_int'),
            array('varchar', PDO::PARAM_STR, 'value_string'),
            array('text', PDO::PARAM_STR, 'value_string'),
        );
    }

    /**
    * @dataProvider dataTypes
    */
    public function testPdoType($type, $pdoType, $valueColumn)
    {
        $variable = new Statsomatic_Variable('foo', 'bar', $type);

        $this->assertEquals($pdoType, $variable->getPdoType());
        $this->assertEquals($valueColumn, $variable->getValueColumn());
    }

    static public function castValues()
    {
        return array(
            array('foo', 'int', 0),
            array('foo', 'varchar', 'foo'),
            array('foo', 'text', 'foo'),
            array(23, 'int', 23),
            array(23, 'varchar', '23'),
            array(23, 'text', '23'),
            array('23', 'int', 23),
            array('23', 'varchar', '23'),
            array('23', 'text', '23'),
        );
    }

    /**
    * @dataProvider castValues
    */
    public function testCastValue($value, $type, $expected)
    {
        $variable = new Statsomatic_Variable('foo', 'bar', $type);
        $this->assertEquals($expected, $variable->castValue($value));
    }
}
