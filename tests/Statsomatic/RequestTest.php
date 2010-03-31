<?php
/**
*
* @package tests
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once 'lib/autoload.php';

class Statsomatic_RequestTest extends PHPUnit_Framework_TestCase
{
    private $request;

    public function setUp()
    {
        $requestData = array(
            'foo' => 'bar',
        );

        $this->request = new Statsomatic_Request($requestData);
    }

    public function testRequestVar()
    {
        $this->assertEquals('bar', $this->request->get('foo', ''));
    }

    public function testDefaultValue()
    {
        $this->assertEquals('abc', $this->request->get('foobar', 'abc'));
    }
}
