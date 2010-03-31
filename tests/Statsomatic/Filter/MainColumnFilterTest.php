<?php
/**
*
* @package tests
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once 'lib/autoload.php';

class Statsomatic_Filter_MainColumnFilterTest extends PHPUnit_Framework_TestCase
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
            array('eq', 2, PDO::PARAM_INT, 'm1.PROVIDER_variable = :ezcValue1'),
            array('gt', 2, PDO::PARAM_INT, 'm1.PROVIDER_variable > :ezcValue1'),
            array('lt', 2, PDO::PARAM_INT, 'm1.PROVIDER_variable < :ezcValue1'),
            array('lte', 2, PDO::PARAM_INT, 'm1.PROVIDER_variable <= :ezcValue1'),
            array('gte', 2, PDO::PARAM_INT, 'm1.PROVIDER_variable >= :ezcValue1'),
            array('eq','abc', PDO::PARAM_STR, 'm1.PROVIDER_variable = :ezcValue1'),
        );
    }

    /**
    * @dataProvider filterData
    */
    public function testApplyFilter($comperator, $value, $type, $sql)
    {
        $filter = new Statsomatic_Filter_MainColumnFilter('PROVIDER', 'variable', $comperator, $value, $type);
        $filter->apply($this->query);

        $this->assertEquals('SELECT m1.entry_id FROM statistics_main AS m1 WHERE ' . $sql, $this->query->getQuery());
    }

    public function testApplyMultipleFilter()
    {
        $filter1 = new Statsomatic_Filter_MainColumnFilter('PROVIDER', 'variable1', 'eq', 2, PDO::PARAM_INT);
        $filter2 = new Statsomatic_Filter_MainColumnFilter('PROVIDER', 'variable2', 'lt', 3, PDO::PARAM_INT);
        $filter3 = new Statsomatic_Filter_MainColumnFilter('PROVIDER', 'variable3', 'gte', 1, PDO::PARAM_INT);

        $filter1->apply($this->query);
        $this->assertEquals(
            'SELECT m1.entry_id FROM statistics_main AS m1 WHERE m1.PROVIDER_variable1 = :ezcValue1',
            $this->query->getQuery()
        );

        $filter2->apply($this->query);
        $this->assertEquals(
            'SELECT m1.entry_id FROM statistics_main AS m1 WHERE m1.PROVIDER_variable1 = :ezcValue1 AND m1.PROVIDER_variable2 < :ezcValue2',
            $this->query->getQuery()
        );

        $filter3->apply($this->query);
        $this->assertEquals(
            'SELECT m1.entry_id FROM statistics_main AS m1 WHERE m1.PROVIDER_variable1 = :ezcValue1 AND m1.PROVIDER_variable2 < :ezcValue2 AND m1.PROVIDER_variable3 >= :ezcValue3',
            $this->query->getQuery()
        );
    }
}
