<?php
/**
*
* @package tests
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class Statsomatic_DatabaseAccess
{
    public function __construct()
    {
        $db = ezcDbFactory::create('mysql://user:password@127.0.0.1/stats');
        ezcDbInstance::set($db);
    }

    public function getDb()
    {
        return ezcDbInstance::get();
    }

    public function createSelectQuery()
    {
        return new Statsomatic_Query($this->getDb()->createSelectQuery());
    }
}