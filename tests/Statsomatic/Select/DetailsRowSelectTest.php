<?php
/**
*
* @package tests
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once 'lib/autoload.php';

class Statsomatic_Select_DetailsRowSelectTest extends PHPUnit_Framework_TestCase
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
        $select = new Statsomatic_Select_DetailsRowSelect(new Statsomatic_Variable('provider', 'variable', 'int'), new Statsomatic_Aggregator('ID'));

        $select->apply($this->query);
        $this->assertEquals(
            'SELECT d1.value_int AS value1 FROM statistics_main AS m1 LEFT JOIN statistics_details AS d1 ON m1.entry_id = d1.entry_id WHERE d1.provider = :ezcValue1 AND d1.variable = :ezcValue2 GROUP BY d1.value_int ORDER BY d1.value_int DESC',
            $this->query->getQuery()
        );
    }

    public function testApplyAggregate()
    {
        $select = new Statsomatic_Select_DetailsRowSelect(new Statsomatic_Variable('provider', 'variable', 'int'), new Statsomatic_Aggregator('COUNT'));

        $select->apply($this->query);
        $this->assertEquals(
            'SELECT COUNT(d1.value_int) AS value1 FROM statistics_main AS m1 LEFT JOIN statistics_details AS d1 ON m1.entry_id = d1.entry_id WHERE d1.provider = :ezcValue1 AND d1.variable = :ezcValue2 ORDER BY COUNT(d1.value_int) DESC',
            $this->query->getQuery()
        );
    }
}
