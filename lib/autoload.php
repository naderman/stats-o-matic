<?php
/**
*
* @package Statsomatic
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

error_reporting(-1);

set_include_path(
    // libraries
    realpath(dirname(__FILE__) . '/../lib') . PATH_SEPARATOR .
    // all other includes
    get_include_path()
);

require_once 'SplClassLoader.php';
require_once 'ezcClassLoader.php';

$statsClassLoader = new SplClassLoader('Statsomatic');
$statsClassLoader->setNamespaceSeparator('_');
$statsClassLoader->register();

$ezcBaseLoader = new ezcClassLoader('Base');
$ezcBaseLoader->register();

$ezcDatabaseLoader = new ezcClassLoader('Database');
$ezcDatabaseLoader->register();

$ezcGraphLoader = new ezcClassLoader('Graph');
$ezcGraphLoader->register();
