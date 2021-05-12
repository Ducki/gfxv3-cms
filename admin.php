<?php
/*
//|-------------------------------------------|\\
//|                   v3cms                   |\\
//|-------------------------------------------|\\
//|                                           |\\
//|-------------------------------------------|\\

Notizen/To-Do:
--------------



*/
/**
 * ACP executable wrapper
 *
 * @author Ducki`
 * @package V3
 * @version 1.0.0
*/

/**
* Root path
*
*/
define('ROOT_PATH', dirname(__FILE__)."/");

/**
* Custom error handling
*
* If enabled, custom error messages
* will appear instead of standard php messages
*/
define('CUSTOM_ERROR', true);

/**
* Database debug output
*
* If enabled, debug information about select queries
* will be printed.
*/
define('SQL_DEBUG', 0);

/**
* General debug output
*
* If enabled, debug information will be printed.
* MUST BE ENABLED FOR SQL_DEBUG OUTPUT!
*/
define('DEBUG_OUTPUT', 0);

//|-------------------------------------------
//|      No configurable elements below
//|-------------------------------------------

/**
* On top
*
* Is the user on index.php?
*/
define ('on_top', 1);


@set_magic_quotes_runtime(0);
//ini_set('zlib.output_compression', 1);
error_reporting(E_ALL | E_STRICT);

if (CUSTOM_ERROR) {
	set_error_handler("my_error_handler");
}

//|===========================================
//|               Main program
//|===========================================

require_once ROOT_PATH.'sources/core.php';
require_once ROOT_PATH.'sources/core/session.php';
require_once ROOT_PATH.'sources/core/template.php';

//|-------------------------------------------
//|        Initiate our main classes
//|-------------------------------------------

$core = new core;

$core->session = new session;
$core->session->core=$core;

$core->template = new template;
$core->template->core=$core;


//|-------------------------------------------
//|             Init some stuff
//|-------------------------------------------

start_timer();

$core->init_db();
$core->get_cache();

$core->parse_input();

$core->load_skin(true);
$core->load_template('skin_acp_global');

$core->session->check_me();

if (!is_array($core->input)) {
	$core->input['act'] = 'home';
}


//|-------------------------------------------
//|                  Timer
//|-------------------------------------------

function start_timer() {

	global $start_time;
	$mtime = explode (' ', microtime());
	$start_time = $mtime[1] + $mtime[0];

}

function end_timer() {

	global $start_time;
	$mtime = explode (' ', microtime());
	$mtime = $mtime[1] + $mtime[0];
	$endtime = $mtime;
	$totaltime = round (($endtime - $start_time), 5);
	return $totaltime;

}

start_timer();




//|-------------------------------------------
//|           Custom error handler
//|-------------------------------------------

function my_error_handler($errno, $errstr, $errfile, $errline) {

	switch ($errno) {
		case E_ERROR:
		echo '<div style="width: 50%; margin: 0 auto; padding: 10px; background: #ffdddd; border: 1px solid #f00;"><h1 style="color: #c00;">V3 DEVELOPMENT ERROR</h1> ['.$errno.'] '.$errstr.' (Line: '.$errline.' of '.$errfile.')</div><br />';
		exit(1);
		break;

		case E_WARNING:
		echo '<div style="width: 50%; margin: 0 auto; padding: 10px; background: #ffdddd; border: 1px solid #f00;"><h1 style="color: #c00;">V3 DEVELOPMENT WARNING</h1><p>['.$errno.'] '.$errstr.'</p>Line: <b>'.$errline.'</b><br />File: <b>'.$errfile.'</b></div><br />';
		break;

		case E_NOTICE:
		if (strpos($errstr, 'Undefined index') === false) {
			echo '<div style="width: 50%; margin: 0 auto; padding: 10px; background: #ffdddd; border: 1px solid #f00;"><h1 style="color: #c00;">V3 DEVELOPMENT NOTICE</h1><p>['.$errno.'] '.$errstr.'</p>Line: <b>'.$errline.'</b><br />File: <b>'.$errfile.'</b></div><br />';
		}
		break;

		case E_USER_ERROR:
		echo '<div style="width: 50%; margin: 0 auto; padding: 10px; background: #ffdddd; border: 1px solid #f00;"><h1 style="color: #c00;">V3 TRIGGERED ERROR</h1><p>['.$errno.'] '.$errstr.'</p>Line: <b>'.$errline.'</b><br />File: <b>'.$errfile.'</b></div><br />';
		break;

		default:
		echo '<div style="width: 50%; margin: 0 auto; padding: 10px; background: #ffdddd; border: 1px solid #f00;"><b>Unkown error type:</b><p>['.$errno.'] '.$errstr.'</p>Line: <b>'.$errline.'</b><br />File: <b>'.$errfile.'</b></div><br />';
		break;
	}

}

?>
