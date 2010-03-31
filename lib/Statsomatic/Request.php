<?php
/**
*
* @package Statsomatic
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class Statsomatic_Request implements Statsomatic_RequestInterface
{
    private $requestData;

    public function __construct(array $requestData)
    {
        $this->requestData = $requestData;
    }

    public function get($varname, $default)
    {
        if (!isset($this->requestData[$varname]))
        {
            return $default;
        }
        else
        {
            return $this->requestData[$varname];
        }
    }
}
