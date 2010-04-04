<?php
/**
*
* @package tests
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once 'lib/autoload.php';

class Statsomatic_Select_FactoryTest extends PHPUnit_Framework_TestCase
{
    protected $factory;

    public function setUp()
    {
        $this->factory = new Statsomatic_Select_Factory(
            new Statsomatic_Data_Definitions(
                array(
                    'provider1' => array(
                        'variable1' => array(
                            'type' => 'varchar',
                            'isArray' => false,
                            'main' => true,
                        ),
                    ),
                    'provider2' => array(
                        'variable2' => array(
                            'type' => 'int',
                            'isArray' => false,
                            'main' => false,
                        ),
                    ),
                )
            )
        );
    }

    public function testFromArray()
    {
        $selectDefinitions = array(
            array(
                'provider' => 'provider1',
                'variable' => 'variable1',
                'aggregator' => 'ID',
            ),
            array(
                'provider' => 'provider2',
                'variable' => 'variable2',
                'aggregator' => 'COUNT',
            ),
        );

        $selects = $this->factory->paramsFromArray($selectDefinitions);

        $this->assertEquals(
            $selects[0],
            new Statsomatic_Select_MainColumnSelect(new Statsomatic_Variable('provider1', 'variable1', 'varchar', false, Statsomatic_Variable::MAIN), new Statsomatic_Aggregator('ID'))
        );
        $this->assertEquals(
            $selects[1],
            new Statsomatic_Select_DetailsRowSelect(new Statsomatic_Variable('provider2', 'variable2', 'int'), new Statsomatic_Aggregator('COUNT'))
        );
    }
}
