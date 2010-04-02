<?php
/**
*
* @package tests
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once 'lib/autoload.php';

class Statsomatic_AggregatorTest extends PHPUnit_Framework_TestCase
{
    public function testApplyCount()
    {
        $aggregator = new Statsomatic_Aggregator('COUNT');
        $this->assertEquals('COUNT(foo.bar)', $aggregator->apply('foo.bar'));
    }

    public function testApplyId()
    {
        $aggregator = new Statsomatic_Aggregator('ID');
        $this->assertEquals('foo.bar', $aggregator->apply('foo.bar'));
    }
}
