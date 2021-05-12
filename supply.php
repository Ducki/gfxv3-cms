<?php
/*
//|-------------------------------------------|\\
//|               The Supply                  |\\
//|-------------------------------------------|\\
*/
/**
 * The Supply
 * 
 * Supplies and compresses the CSS and JS files
 *
 * @author Ducki`
 * @package V3
 * @version 1.0.0
*/

//|-------------------------------------------
//|                Compress!
//|-------------------------------------------
if (!ini_get('zlib.output_compression')) {

	ob_start('ob_gzhandler');
	
}

//|-------------------------------------------
//|                  Clean
//|-------------------------------------------
$_GET['give'] = preg_replace('/[^a-zA-Z0-9\-\_]/', '', $_GET['give']);

//|-------------------------------------------
//|                  Call!
//|-------------------------------------------
switch ($_GET['give']) {
	case 'css_general':
		
		print_css();
		
		break;

	default:
		// Do nothing
		exit;
		break;
}


/**
 * Prints the compressed CSS file
 *
 */
function print_css() {
	
	header('Content-type: text/css');
	header("Expires: Mon, 28 Aug 2007 05:00:00 GMT"); 
	
	print $content = file_get_contents('images/default_skin/css_general.css');
	
}

?>