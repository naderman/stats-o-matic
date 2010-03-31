<?php
/**
*
* @package tests
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once 'lib/autoload.php';

class Statsomatic_Filter_DetailsRowAnyFilterTest extends PHPUnit_Framework_TestCase
{
    private $query;

    public function setUp()
    {
        $dbAccess = new Statsomatic_DatabaseAccess();

        $q = $dbAccess->createSelectQuery();
        $q->from($q->nextMainAlias());
        $q->select($q->mainColumn('entry_id'));

        $this->query = $q;
    }

    static public function filterData()
    {
        return array(
            array('lte', 2, PDO::PARAM_INT, 'd1.value_int <= :ezcValue3'),
            array('eq','abc', PDO::PARAM_STR, 'd1.value_string = :ezcValue3'),
        );
    }

    /**
    * @dataProvider filterData
    */
    public function testApplyFilter($comperator, $value, $type, $sql)
    {
        $filter = new Statsomatic_Filter_DetailsRowAnyFilter('PROVIDER', 'variable', $comperator, $value, $type);
        $filter->apply($this->query);

        $this->assertEquals('SELECT m1.entry_id FROM statistics_main AS m1 WHERE m1.entry_id IN ( SELECT d1.entry_id FROM statistics_details AS d1 WHERE d1.provider = :ezcValue1 AND d1.variable = :ezcValue2 AND ' . $sql . ' )', $this->query->getQuery());
    }
}
