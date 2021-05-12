<?php

session_start();

session_regenerate_id();

$_SESSION['id']				= session_id();
$_SESSION['member_id']		= 1;
$_SESSION['member_name']	= 'Ducki';
$_SESSION['member_group']	= 'admin';

$_SESSION['user_agent_hash'] = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$_SERVER['HTTP_ACCEPT']);


mysql_connect('localhost', 'root', '');
mysql_select_db('gfx-v3');

mysql_query('INSERT INTO sessions (id,member_name,member_id,ip_address,browser,browser_key,time_t,member_group) VALUES(
\''.$_SESSION['id'].'\',
\'Ducki\',
\'1\',
\''.$_SERVER['REMOTE_ADDR'].'\',
\'todo!\',
\''.$_SESSION['user_agent_hash'].'\',
\'NOW()\',
\''.$_SESSION['member_group'].'\')');

?>