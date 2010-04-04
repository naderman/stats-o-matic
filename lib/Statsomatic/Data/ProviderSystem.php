<?php
/**
*
* @package Statsomatic\Data
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

return array(
    'os' => array(
        'type' => 'varchar',
        'isArray' => false,
        'main' => true,
    ),
    'httpd' => array(
        'type' => 'varchar',
        'isArray' => false,
        'main' => true,
    ),
    'private_ip' => array(
        'type' => 'int',
        'isArray' => false,
        'main' => false,
    ),
    'ipv6' => array(
        'type' => 'int',
        'isArray' => false,
        'main' => false,
    ),
);