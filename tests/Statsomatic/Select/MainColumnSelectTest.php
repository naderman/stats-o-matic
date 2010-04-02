<?php
/**
*
* @package tests
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once 'lib/autoload.php';

class Statsomatic_Select_MainColumnSelectTest extends PHPUnit_Framework_TestCase
{
    protected $query;

    public function setUp()
    {
        $dbAccess = new Statsomatic_DatabaseAccess();

        $q = $dbAccess->createSelectQuery();
        $q->from($q->nextMainAlias());

        $this->query = $q;
    }

    public function testApply()
    {
        $select = new Statsomatic_Select_MainColumnSelect(new Statsomatic_Variable('provider', 'variable', 'int'), new Statsomatic_Aggregator('ID'));

        $select->apply($this->query);
        $this->assertEquals(
            'SELECT m1.provider_variable AS value1 FROM statistics_main AS m1 GROUP BY m1.provider_variable',
            $this->query->getQuery()
        );
    }
}
