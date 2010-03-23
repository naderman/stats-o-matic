<?php
/**
*
* @package viewer
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

error_reporting(-1);

require 'SplClassLoader.php';
require 'ezcClassLoader.php';

set_include_path(
    // libraries
    realpath(dirname(__FILE__) . '/../lib') . PATH_SEPARATOR .
    // all other includes
    get_include_path()
);


$statsClassLoader = new SplClassLoader('Statsomatic');
$statsClassLoader->setNamespaceSeparator('_');
$statsClassLoader->register();

$ezcBaseLoader = new ezcClassLoader('Base');
$ezcBaseLoader->register();

$ezcGraphLoader = new ezcClassLoader('Graph');
$ezcGraphLoader->register();

$viewer = new Statsomatic_Viewer();
$viewer->display();
