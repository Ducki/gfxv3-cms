<?php

error_reporting(E_ALL);

mysql_connect('localhost', 'root', '');
mysql_select_db('gfx-v3');


//|-------------------------------------------
//|                The HTML
//|-------------------------------------------

$res=mysql_query('SELECT set_id, group_name FROM skin_templates');

while ($row=mysql_fetch_assoc($res)) {

	$group_titles[$row['group_name']] = $row;

}

$out='';
$files=0;

foreach ($group_titles as $name => $group) {

	$out.='<?php
/*
This file has been generated automatically.
Changes in here will be reseted when rebuilding cache.
If you want to change the template go to the acp.
*/

class '.$name.'{
	
';

	$res2=mysql_query('SELECT group_name, section_content, func_name, func_data FROM skin_templates WHERE group_name = \''.$name.'\'');
	$tmp='';
	while ($row2=mysql_fetch_assoc($res2)) {

		$tmp="
//|-------------------------------------------
//| {$row2['func_name']}
//|-------------------------------------------
function {$row2['func_name']}({$row2['func_data']}) {

return <<<TOP
{$row2['section_content']}
TOP;

}


";
		$out.=$tmp;
	}

	$out.='}

?>
';

	file_put_contents('cache/skin/'.$name.'.php', $out);
	$lenght=strlen($out);
	$files++;
	$out='';
}

echo 'Templates successfully rebuilded.

'.$files.' template files written.
'.$lenght.' bytes - '.(round($lenght/1024, 3)).' kb.<br><br>';


//|-------------------------------------------
//|                The CSS
//|-------------------------------------------

$res = mysql_query('SELECT name, css, image_dir FROM skin_sets');
$row=mysql_fetch_assoc($res);
$output='';

$output = "/*
//|-------------------------------------------|\\
//|          Cascading Style Sheets           |\\
//|-------------------------------------------|\\
//|-------------------------------------------|\\
//| {$row['name']}
//|-------------------------------------------|\\
*/

";
$output.= $row['css'];

file_put_contents('images/'.$row['image_dir'].'/css_general.css', $output);

echo 'CSS successfully rebuilded.

'.strlen($output).' bytes - '.(round(strlen($output)/1024, 3)).' kb.';

?>