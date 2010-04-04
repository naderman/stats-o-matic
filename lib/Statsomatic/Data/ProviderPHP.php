<?php
/**
*
* @package Statsomatic\Data
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

return array(
    'version' => array(
        'type' => 'varchar',
        'isArray' => false,
        'main' => true,
    ),
    'sapi' => array(
        'type' => 'varchar',
        'isArray' => false,
        'main' => true,
    ),
    'int_size' => array(
        'type' => 'int',
        'isArray' => false,
        'main' => true,
    ),
    'safe_mode' => array(
        'type' => 'int',
        'isArray' => false,
        'main' => true,
    ),
    'open_basedir' => array(
        'type' => 'int',
        'isArray' => false,
        'main' => true,
    ),
    'memory_limit' => array(
        'type' => 'varchar',
        'isArray' => false,
        'main' => true,
    ),
    'allow_url_fopen' => array(
        'type' => 'int',
        'isArray' => false,
        'main' => true,
    ),
    'allow_url_include' => array(
        'type' => 'int',
        'isArray' => false,
        'main' => true,
    ),
    'file_uploads' => array(
        'type' => 'int',
        'isArray' => false,
        'main' => true,
    ),
    'upload_max_filesize' => array(
        'type' => 'varchar',
        'isArray' => false,
        'main' => true,
    ),
    'post_max_size' => array(
        'type' => 'varchar',
        'isArray' => false,
        'main' => true,
    ),
    'disable_functions' => array(
        'type' => 'varchar',
        'isArray' => false,
        'main' => true,
    ),
    'disable_classes' => array(
        'type' => 'varchar',
        'isArray' => false,
        'main' => true,
    ),
    'enable_dl' => array(
        'type' => 'int',
        'isArray' => false,
        'main' => true,
    ),
    'magic_quotes_gpc' => array(
        'type' => 'int',
        'isArray' => false,
        'main' => true,
    ),
    'register_globals' => array(
        'type' => 'int',
        'isArray' => false,
        'main' => true,
    ),
    'filter.default' => array(
        'type' => 'varchar',
        'isArray' => false,
        'main' => true,
    ),
    'zend.ze1_compatibility_mode' => array(
        'type' => 'int',
        'isArray' => false,
        'main' => true,
    ),
    'unicode.semantics' => array(
        'type' => 'int',
        'isArray' => false,
        'main' => true,
    ),
    'zend_thread_safety' => array(
        'type' => 'int',
        'isArray' => false,
        'main' => true,
    ),
    'extensions' => array(
        'type' => 'varchar',
        'isArray' => true,
        'main' => false,
    ),
);
