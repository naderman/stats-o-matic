<?php
/**
*
* @package Statsomatic
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

interface Statsomatic_RequestInterface
{
    public function get($varname, $default);
}
