<?php
/**
*
* @package tests
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once 'lib/autoload.php';

class Statsomatic_Filter_FilterTest extends PHPUnit_Framework_TestCase
{
    private $query;

    public function setUp()
    {
        $dbAccess = new Statsomatic_DatabaseAccess();

        $q = $dbAccess->createSelectQuery();
        $q->from($q->nextMainAlias());
        $q->select($q->currentMainAlias() . '.entry_id');

        $this->query = $q;
    }

    public function testCombineFilters()
    {
        $filter1 = new Statsomatic_Filter_MainColumnFilter('PROVIDER2', 'variable2', 'eq', 'abc', PDO::PARAM_STR);
        $filter2 = new Statsomatic_Filter_DetailsRowAnyFilter('PROVIDER1', 'variable1', 'lt', 3, PDO::PARAM_INT);

        $filter1->apply($this->query);
        $filter2->apply($this->query);

        $this->assertEquals(
            "SELECT m1.entry_id FROM statistics_main AS m1 WHERE m1.PROVIDER2_variable2 = :ezcValue1 AND m1.entry_id IN ( SELECT d1.entry_id FROM statistics_details AS d1 WHERE d1.provider = :ezcValue2 AND d1.variable = :ezcValue3 AND d1.value_int < :ezcValue4 )",
            $this->query->getQuery()
        );
    }
}
