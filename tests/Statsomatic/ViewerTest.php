<?php
/**
*
* @package tests
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require 'lib/autoload.php';

class Statsomatic_ViewerTest extends PHPUnit_Framework_TestCase
{
    private $viewer;

    public function setUp()
    {
        $this->viewer = new Statsomatic_Viewer();
    }

    public function testDisplay()
    {
        $request = $this->getMock('Statsomatic_RequestInterface');
        $this->assertEquals('', $this->viewer->display($request));
    }
}
