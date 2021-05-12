<?php


mysql_connect('localhost', 'gaze', 'gazedev');
mysql_select_db('gazedev');

$sql = 'SELECT * FROM permissions';
$res = mysql_query($sql);


echo '<pre>';

while ($row = mysql_fetch_assoc($res)) {
	
	$foo['permissions'][$row['g_id']] = $row;
	
}

file_put_contents('cache/stuff_cache.php', serialize($foo));

echo 'Permissions cached.<br><br>';
print_r($foo);

?>