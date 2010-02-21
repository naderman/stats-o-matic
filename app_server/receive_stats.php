<?php

include('../common.php');

if (isset($_GET['create_tables']))
{
	display_create_tables($important_variables);
	exit;
}

$data = read_post_serialized('systemdata');

if (!$data || !isset($data['install_id']) || !is_string($data['install_id']))
{
	die("Internal server error while processing statistic data: Bad input!");
}

$install_id = $data['install_id'];
unset($data['install_id']);
$data['questions'] = read_post('questions', 'array');

if (!save_data($pdo, $important_variables, $install_id, $data))
{
	die("Internal server error while processing statistic data: Failed to save data!");
}

echo '<p>Thank you for your support! Have fun with your application!</p>';

function read_post($variable, $type)
{
	if (!isset($_POST[$variable]) || gettype($_POST[$variable]) != $type)
	{
		$x = null;
		settype($x, $type);
		return $x;
	}

	$result = $_POST[$variable];

	if (STRIP)
	{
		resursive_stripslashes($result);
	}

	return $result;
}

function recursive_stripslashes(&$value)
{
	if (is_string($value))
	{
		$value = stripslashes($value);
	}
	else if (is_array($value))
	{
		foreach ($value as $key => &$sub_value)
		{
			recursive_stripslashes($sub_value);
		}
	}
}

function read_post_serialized($variable)
{
	$data = read_post($variable, 'string');
	$data = base64_decode($data, true);
	$data = unserialize($data);

	if (!is_array($data))
	{
		return array();
	}

	return $data;
}

function save_data($pdo, $important_variables, $install_id, $data)
{
	$entry_id = uniqid($install_id . '_', true);

	$columns = array();
	$values = array();

	foreach ($important_variables as $column)
	{
		list($provider, $key, $type) = $column;
		$name = $provider . '_' . $key;

		// important variables need to be set
		// and they must not be arrays
		if (!isset($data[$provider][$key]) || is_array($data[$provider][$key]))
		{
			return false;
		}

		$columns[] = $name;
		$values[] = $data[$provider][$key];
		$types[] = $type;
	}

	$sql = sprintf(
		'INSERT INTO statistics_main (install_id, entry_id, forwarded, `%s`, timestamp) VALUES(?, ?, 0, %s, NOW())',
		implode('`, `', $columns),
		implode(', ', array_fill(0, sizeof($columns), '?'))
	);

	$stmt = $pdo->prepare($sql);

	$offset = 1;
	$stmt->bindValue($offset++, $install_id, PDO::PARAM_STR);
	$stmt->bindValue($offset++, $entry_id, PDO::PARAM_STR);

	foreach ($values as $key => $value)
	{
		$type = pdo_type_from_string($types[$key]);

		$stmt->bindValue($offset++, $value, $type);
	}

	if (!$stmt->execute())
	{
		return false;
	}

	$sql = 'INSERT INTO statistics_details
		(entry_id, provider, variable, value_string, value_int)
		VALUES (?, ?, ?, ?, ?)';
	$stmt = $pdo->prepare($sql);

	$stmt->bindValue(1, $entry_id, PDO::PARAM_STR);

	foreach ($data as $provider => $variables)
	{
		if (!is_array($variables))
		{
			continue;
		}

		$stmt->bindValue(2, $provider, PDO::PARAM_STR);

		foreach ($variables as $variable => $value)
		{
			$stmt->bindValue(3, $variable, PDO::PARAM_STR);
			$name = $provider . '_' . $variable;

			// only if it's not one of the important variables
			if (!isset($values[$name]))
			{
				if (is_array($value))
				{
					foreach ($value as $item)
					{
						if (is_array($item))
						{
							continue;
						}

						$stmt->bindValue(4, $item, PDO::PARAM_STR);
						$stmt->bindValue(5, $item, PDO::PARAM_INT);

						if (!$stmt->execute())
						{
							return false;
						}
					}
				}
				else
				{
					$stmt->bindValue(4, $value, PDO::PARAM_STR);
					$stmt->bindValue(5, $value, PDO::PARAM_INT);

					if (!$stmt->execute())
					{
						return false;
					}
				}
			}
		}
	}

	return true;
}

function display_create_tables($important_variables)
{
	$query_main = "CREATE TABLE `statistics_main` (
	`install_id` VARCHAR( 128 ) CHARACTER SET ascii COLLATE ascii_bin NOT NULL ,
	`entry_id` VARCHAR( 151 ) CHARACTER SET ascii COLLATE ascii_bin NOT NULL ,
	`forwarded` TINYINT(1) NOT NULL ,\n";

	foreach ($important_variables as $column)
	{
		list($provider, $key, $type) = $column;
		$name = $provider . '_' . $key;

		switch ($type)
		{
			case 'int':
				$type = 'INT';
			break;
			case 'varchar':
				$type = 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin';
			break;
			case 'text':
				$type = 'TEXT CHARACTER SET utf8 COLLATE utf8_bin';
			break;
		}

		$query_main .= "\t`$name` $type NOT NULL ,\n";
	}
	$query_main .= "\t`timestamp` DATETIME NOT NULL,
	KEY( `install_id` ),
	PRIMARY KEY ( `entry_id` )\n);";

	$query_details = "CREATE TABLE `statistics_details` (
	`entry_id` VARCHAR( 151 ) CHARACTER SET ascii COLLATE ascii_bin NOT NULL ,
	`provider` VARCHAR( 128 ) CHARACTER SET ascii COLLATE ascii_bin NOT NULL ,
	`variable` VARCHAR( 128 ) CHARACTER SET ascii COLLATE ascii_bin NOT NULL ,
	`value_string` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin NULL,
	`value_int` INT NULL,
	KEY( `entry_id` )\n);";

	echo "<pre>\n", $query_main, "\n</pre>\n<pre>\n", $query_details, "\n</pre>\n";
}
