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
    }

    static public function dataTypes()
    {
        return array(
            array('int', PDO::PARAM_INT),
            array('varchar', PDO::PARAM_STR),
            array('text', PDO::PARAM_STR),
        );
    }

    /**
    * @dataProvider dataTypes
    */
    public function testPdoType($type, $pdoType)
    {
        $variable = new Statsomatic_Variable('foo', 'bar', $type);
        $this->assertEquals($pdoType, $variable->getPdoType());
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
