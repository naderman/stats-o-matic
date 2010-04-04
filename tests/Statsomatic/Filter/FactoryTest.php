<?php
/**
*
* @package tests
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once 'lib/autoload.php';

class Statsomatic_Filter_FactoryTest extends PHPUnit_Framework_TestCase
{
    protected $factory;

    public function setUp()
    {
        $this->factory = new Statsomatic_Filter_Factory(
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
        $filterDefinitions = array(
            array(
                'provider' => 'provider1',
                'variable' => 'variable1',
                'comperator' => 'eq',
                'value' => 'foo',
            ),
            array(
                'type' => 'Any',
                'provider' => 'provider2',
                'variable' => 'variable2',
                'comperator' => 'lte',
                'value' => '3',
            ),
        );

        $filters = $this->factory->paramsFromArray($filterDefinitions);

        $this->assertEquals(
            $filters[0],
            new Statsomatic_Filter_MainColumnFilter(new Statsomatic_Variable('provider1', 'variable1', 'varchar', false, Statsomatic_Variable::MAIN), 'eq', 'foo')
        );
        $this->assertEquals(
            $filters[1],
            new Statsomatic_Filter_DetailsRowAnyFilter(new Statsomatic_Variable('provider2', 'variable2', 'int'), 'lte', 3, PDO::PARAM_INT)
        );
    }
}
