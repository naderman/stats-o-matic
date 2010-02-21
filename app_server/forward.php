<?php

include('common.php');

do
{
	$columns = array();

	foreach ($important_variables as $column)
	{
		list($provider, $key, $type) = $column;

		if ($provider === 'PHP' || $provider === 'System')
		{
			$name = $provider . '_' . $key;
			$columns[] = $name;
			$types[] = $type;
		}
	}

	$sql = sprintf(
		'SELECT entry_id, install_id, %s
		FROM statistics_main
		WHERE forwarded = 0
		LIMIT 500',
		implode(', ', $columns)
	);

	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	$entry_ids = array();
	$entries = array();
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC, 0) !== false)
	{
		$entry_ids[] = (int) $row['entry_id'];
		$entries[$row['entry_id']] = array(0 => $row, 1 => array());
	}

	$num_forwarded = sizeof($entry_ids);

	if ($num_forwarded)
	{
		$sql = sprintf("SELECT entry_id, provider, variable, value_string, value_int
			FROM statistics_details
			WHERE entry_id IN (%s)
			AND (provider = 'PHP' OR provider = 'System')",
			implode(', ', $entry_ids)
		);

		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC) !== false)
		{
			$entry_id = (int) $row['entry_id'];
			$entries[$entry_id][1][] = $row;
		}

		unset($entry_ids);
		unset($stmt);

		$forward_content = array();
		foreach ($entries as $entry_id => $data)
		{
			list($main, $details) = $data;
			
			
		}
		
		$forward_content = array('data' => serialize($forward_content));
		$forward_content = http_build_query($forward_content);

		$context = stream_context_create(array(
			'http' => array(
				'method' => 'POST',
				'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
					"Content-Length: " . strlen($forward_content) . "\r\n",
				'content' => $forward_content,
		)));

		$result = file_get_contents(PHP_QA_URL, false, $context);
var_dump($result);exit;
		$forwarded_entry_ids = array_map('intval', explode(', ', $result));

		$sql = sprintf("UPDATE statistics_main
			SET forwarded = 1
			WHERE entry_id IN (%s)",
			implode(', ', $entry_ids)
		);

		$stmt = $pdo->prepare($sql);
		$stmt->execute();
	}
}
while ($num_forwarded > 0);

