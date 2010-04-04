<?php

define('DB_SERVER', 'localhost');
define('DB_NAME', 'stats');
define('DB_USER', 'user');
define('DB_PW', 'password');
define('DB_UNIX_SOCKET', '/var/run/mysqld/mysqld.sock'); // or leave empty if uneccessary

//define('PHP_QA_URL', 'qa.php.net/stats/receive_forwarded_stats.php');
define('PHP_QA_URL', "http://localhost/~naderman/phpbb/questionnaire/receive_forwarded_stats.php");//'http://qa.php.net/stats/receive_forwarded_stats.php');

// type can be one of: bool, int, varchar, text - cannot be used for arrays
$important_variables = array(
	array('PHP', 'version', 'varchar'),
	array('PHP', 'sapi', 'varchar'),
	array('PHP', 'int_size', 'int'),
	array('PHP', 'safe_mode', 'int'),
	array('PHP', 'open_basedir', 'int'),
	array('PHP', 'memory_limit', 'varchar'),
	array('PHP', 'allow_url_fopen', 'int'),
	array('PHP', 'allow_url_include', 'int'),
	array('PHP', 'file_uploads', 'int'),
	array('PHP', 'upload_max_filesize', 'varchar'),
	array('PHP', 'post_max_size', 'varchar'),
	array('PHP', 'disable_functions', 'varchar'),
	array('PHP', 'disable_classes', 'varchar'),
	array('PHP', 'enable_dl', 'int'),
	array('PHP', 'magic_quotes_gpc', 'int'),
	array('PHP', 'register_globals', 'int'),
	array('PHP', 'filter.default', 'varchar'),
	array('PHP', 'zend.ze1_compatibility_mode', 'int'),
	array('PHP', 'unicode.semantics', 'int'),
	array('PHP', 'zend_thread_safty', 'int'),

	array('System', 'os', 'varchar'),
	array('System', 'httpd', 'varchar'),

	// add application specific ones here
);

@set_magic_quotes_runtime(0);
define('STRIP', (get_magic_quotes_gpc()) ? true : false);

try
{
	$unix_socket = '';
	if (DB_UNIX_SOCKET)
	{
		$unix_socket = ';unix_socket=' . DB_UNIX_SOCKET;
	}

	$pdo = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . $unix_socket, DB_USER, DB_PW);
}
catch (Exception $e)
{
	die("Internal server error while processing statistic data: Couldn't connect to database");
}

function pdo_type_from_string($type)
{
	switch ($type)
	{
		case 'bool':
			return PDO::PARAM_BOOL;
		break;
		case 'int':
			return PDO::PARAM_INT;
		break;
	}

	return PDO::PARAM_STR;
}