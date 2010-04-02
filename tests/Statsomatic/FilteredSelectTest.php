<?php
/**
*
* @package tests
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once 'lib/autoload.php';

class Statsomatic_FilteredSelectTest extends PHPUnit_Framework_TestCase
{
    private $query;

    public function setUp()
    {
        $dbAccess = new Statsomatic_DatabaseAccess();

        $q = $dbAccess->createSelectQuery();
        $q->from($q->nextMainAlias());

        $this->query = $q;
    }

    public function testFilterAndSelect()
    {
        $filter1 = new Statsomatic_Filter_MainColumnFilter(new Statsomatic_Variable('PROVIDER2', 'variable2', 'varchar'), 'eq', 'abc');
        $filter2 = new Statsomatic_Filter_DetailsRowAnyFilter(new Statsomatic_Variable('PROVIDER1', 'variable1', 'int'), 'lt', 3);

        $select1 = new Statsomatic_Select_MainColumnSelect(new Statsomatic_Variable('PROVIDER2', 'variable2', 'varchar'), new Statsomatic_Aggregator('ID'));
        $select2 = new Statsomatic_Select_DetailsRowSelect(new Statsomatic_Variable('PROVIDER1', 'variable1', 'int'), new Statsomatic_Aggregator('MAX'));

        $filter1->apply($this->query);
        $filter2->apply($this->query);

        $select1->apply($this->query);
        $select2->apply($this->query);

        $this->assertEquals(
            "SELECT m1.PROVIDER2_variable2 AS value1, MAX(d2.value_int) AS value2 " .
            "FROM statistics_main AS m1 LEFT JOIN statistics_details AS d2 ON m1.entry_id = d2.entry_id " .
            "WHERE " .
                "m1.PROVIDER2_variable2 = :ezcValue1 " .
                "AND m1.entry_id IN ( " .
                    "SELECT d1.entry_id FROM statistics_details AS d1 WHERE d1.provider = :ezcValue2 AND d1.variable = :ezcValue3 AND d1.value_int < :ezcValue4 " .
                ") " .
                "AND d2.provider = :ezcValue5 AND d2.variable = :ezcValue6 " .
            "GROUP BY m1.PROVIDER2_variable2, d2.value_int"
            ,
            $this->query->getQuery()
        );
    }
}
