<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$path = '/home/jeremy/arkserver/tamed/';
$first = true;

echo "{\n";
foreach (scandir($path) as $file) {
	if (in_array($file, ['.', '..']))
		continue;
	if (!$first)
		echo ",\n";
	echo '"' . $file . '": ';
	readfile($path . $file);
	$first = false;
}
echo "\n}\n";
