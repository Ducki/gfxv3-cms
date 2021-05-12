<?php
/*
//|-------------------------------------------|\\
//|                  v3 cms                   |\\
//|                                           |\\
//|-------------------------------------------|\\

Notizen/To-Do:
--------------

stuff und conf-cache
stuff-cache auch serialisiert in db
stuff-cache in eigener variable

!!! --> Cleaning von Referer u.a. Server-Variablen! !!!

login:
- nur nick speichern (in login-form)
- auto-login
- nix
- HTTPS (als Option)
- brute-force-schutz
- zahlencode

- rss f�r kommentare

- User-eigene Macros

- dateien nur l�schen, wenn nirgendwo bneutzt (news etc)

- notice bei einloggen eines bereits eingeloggten users mit aktiver session

- spam-hinweis bei PMs

- Formular-Retter (inhalt bei fehler anzeigen, z.b. bei comment/tutorial etc)

Away-Funktion:
- Funktion: automatisch als "wieder da" machen, bei n�chstem besuch (option)

--> Opera f�gt den Dateinamen an den MIME-Typ dran
--> Mozilla benutzt "none" um leere Datei-Upload-Felder zu zeigen


*/
/**
 * Main wrapper
 *
 * @author Ducki`
 * @package V3
 * @version 1.0.0
*/

//|-------------------------------------------
//|          Configurable elements
//|-------------------------------------------
/**
* mod_rewrite
*
* If enabled, create_url creates special URLs
*/
define('MOD_REWRITE', true);

/**
* Custom error handling
*
* If enabled, custom error messages
* will appear instead of standard php messages
*/
define('CUSTOM_ERROR', false);

/**
* Database debug output
*
* If enabled, debug information about select queries
* will be printed.
*/
define('SQL_DEBUG', false);

/**
* General debug output
*
* If enabled, debug information will be printed.
* MUST BE ENABLED FOR SQL_DEBUG OUTPUT!
*/
define('DEBUG_OUTPUT', false);

/**
* Root path
*
*/
define('ROOT_PATH', dirname(__FILE__)."/");

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
/*
session_cache_limiter("public");
session_cache_expire(90);
*/

//|===========================================
//|               Main program
//|===========================================
start_timer();

require ROOT_PATH.'sources/core.php';
require ROOT_PATH.'sources/core/session.php';
require ROOT_PATH.'sources/core/template.php';


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

$core->init_db();

$core->parse_input();

$core->load_skin();
$core->load_template('skin_global');

$core->languages = $core->get_allowed_languages();

if (!isset($_SESSION['lang'])) {
	
	$_SESSION['lang'] = $core->get_browser_lang();
	
}

$core->load_language('global', $_SESSION['lang']);
//$core->template->add_notify($core->lang['global']['lang_determined']);


$core->session->check_me();

if (MOD_REWRITE) $core->handle_request_uri();

if (!isset($core->input['act']) OR empty($core->input['act'])) {
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




//--------------------------------------------
//              Require and run
//--------------------------------------------


switch ($core->input['act']) {

	//--------------------------------------------
	//               The Homepage
	//--------------------------------------------
	case 'home':

		include ROOT_PATH.'sources/actions_public/home.php';

		$act_run			= new action();
		$act_run->core		= $core;
		$act_run->run_action();

		exit();

	break;

	//--------------------------------------------
	//                 The Rest
	//--------------------------------------------
	default:

		$core->input['act'] = preg_replace('/[^a-zA-Z0-9\-\_]/', '', $core->input['act']);

		if (file_exists(ROOT_PATH.'sources/actions_public/'.$core->input['act'].'.php')) {

			include ROOT_PATH.'sources/actions_public/'.$core->input['act'].'.php';

			$act_run			= new action();
			$act_run->core		= $core;
			$act_run->run_action();

			exit();

		}
		elseif (file_exists(ROOT_PATH.'modules/mod_'.$core->input['act'].'/mod_'.$core->input['act'].'.php')) {

			include ROOT_PATH.'modules/mod_'.$core->input['act'].'/mod_'.$core->input['act'].'.php';

			$mod_run			= new module();
			$mod_run->core		= $core;
			$mod_run->run_module();

			exit();

		}
	//--------------------------------------------
	//     Nothing found? So show an error :P
	//--------------------------------------------
	else {

		echo 'No matching action or module found, exiting.<br>Search program will be found later at this place, so wait and drink tea!'; exit();

	}

}



//|-------------------------------------------
//|           Custom error handler
//|-------------------------------------------

function my_error_handler($errno, $errstr, $errfile, $errline) {

	global $core;
	include_once ROOT_PATH.'sources/error_handler.php';

	$err = new error_handler($errno, $errstr, $errfile, $errline);
	$err->core = $core;
	$err->handle();

}
?>
