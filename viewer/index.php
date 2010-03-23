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

$statsClassLoader = new SplClassLoader('Statsomatic', '.');
$statsClassLoader->setNamespaceSeparator('_');
$statsClassLoader->register();

$viewer = new Statsomatic_Viewer();
$viewer->display();