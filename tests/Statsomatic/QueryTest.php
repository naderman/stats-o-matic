<?php
/**
*
* @package tests
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once 'lib/autoload.php';

class Statsomatic_QueryTest extends PHPUnit_Framework_TestCase
{
    private $query;

    public function setUp()
    {
        $dbAccess = new Statsomatic_DatabaseAccess();

        $this->query = $dbAccess->createSelectQuery();
    }

    public function testNextMain()
    {
        $alias1 = $this->query->nextMainAlias();
        $this->assertEquals('statistics_main AS m1', $alias1);
        $this->assertEquals('m1', $this->query->currentMainAlias());

        $alias2 = $this->query->nextMainAlias();
        $this->assertEquals('statistics_main AS m2', $alias2);
        $this->assertEquals('m2', $this->query->currentMainAlias());
    }

    public function testNextDetails()
    {
        $alias1 = $this->query->nextDetailsAlias();
        $this->assertEquals('statistics_details AS d1', $alias1);
        $this->assertEquals('d1', $this->query->currentDetailsAlias());

        $alias2 = $this->query->nextDetailsAlias();
        $this->assertEquals('statistics_details AS d2', $alias2);
        $this->assertEquals('d2', $this->query->currentDetailsAlias());
    }

    public function testNextValueAlias()
    {
        $alias1 = $this->query->nextValueAlias('foo.bar');
        $this->assertEquals('foo.bar AS value1', $alias1);

        $alias2 = $this->query->nextValueAlias('foo.bar');
        $this->assertEquals('foo.bar AS value2', $alias2);
    }

    public function testSelect()
    {
        $query = $this->query->select('*');

        $this->assertEquals($this->query, $query);
        $this->assertEquals('SELECT *', $this->query->getQuery());
    }

    public function testAddLeftJoin()
    {
        $this->query->from('foo');
        $this->query->addLeftJoin('bar', 'a', 'b');
        $this->query->select('banana');
        $this->query->addLeftJoin('monkey', 'b', 'c');

        $expectedQuery = 'SELECT banana FROM foo LEFT JOIN bar ON a = b LEFT JOIN monkey ON b = c';

        // test twice to make sure it is not reapplied
        $this->assertEquals($expectedQuery, $this->query->getQuery());
        $this->assertEquals($expectedQuery, $this->query->getQuery());
    }
}
