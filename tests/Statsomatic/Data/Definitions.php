<?php
/**
*
* @package tests
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once 'lib/autoload.php';

class Statsomatic_Data_DefinitionsTest extends PHPUnit_Framework_TestCase
{
    protected $definitions;

    public function setUp()
    {
        $providers = array(
            'foo' => array(
                'bar' => array(
                    'type' => 'int',
                    'isArray' => false,
                    'main' => true,
                ),
                'foobar' => array(
                    'type' => 'varchar',
                    'isArray' => true,
                    'main' => false,
                ),
            ),
        );

        $this->definitions = new Statsomatic_Data_Definitions($providers);
    }

    public function testLookup()
    {
        $variable = $definitions->lookup('foo', 'bar');

        $this->assertEquals('foo', $variable->getProvider());
        $this->assertEquals('bar', $variable->getVariable());
        $this->assertEquals('int', $variable->getType());
        $this->assertEquals(false, $variable->isArray());
        $this->assertEquals('MainColumn', $variable->getScopeName());
    }
}
