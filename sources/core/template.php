<?php
/*
//|-------------------------------------------|\\
//|              Template engine              |\\
//|-------------------------------------------|\\
*/
/**
 * Template engine
 *
 * @author Ducki`
 * @package V3
 * @version 1.0.0
*/
defined('on_top') or die('You cannot access this file directly. Please go to index.php.<br />Du kannst diese Datei nicht direkt aufrufen. Bitte gehe zu index.php.');

/**
 * Template engine
 *
 * Provides all template relevant functions
 * and controls the output.
 *
 * @author Ducki`
 * @version 1.0.0
 */

class template {

	/**
	 * The core object
	 *
	 * @var core $core
	 */
	public $core;

	private $output;

	private $notifications;

	//debug, nachher weq:
	public $op_count;

	public $error_msgs;


	/**
	 * Adds something to the output
	 *
	 * @param string $to_add The text
	 */
	public function add_output($to_add) {
		$this->output .= $to_add;
		$this->op_count++;
	}


	public function add_notify($msg) {

		$this->notifications .= $this->core->templates['skin_global']->notify($msg);
		
	}

	public function add_error($msg) {

		$this->error_msgs .= $msg;

	}

	public function output($stuff) {

		//|-------------------------------------------
		//|                Compress?
		//|-------------------------------------------

		if ($this->core->config['use_gzip_output']) {

			if (!ini_get('zlib.output_compression')) {

				ob_start('ob_gzhandler');

			}

		}

		$debug='';
		if (DEBUG_OUTPUT) {

			//|-------------------------------------------
			//|               Debug start
			//|-------------------------------------------
			/*|||*/ $debug.= '<pre style="width: 80%; padding: 10px; margin: 0 auto; display: block; border: 1px solid gray; background: lightgray;">';
			/*|||*/ $debug.= '<strong>Debug output:</strong><br />';
			/*|||*/ $debug.= "Output adding count: $this->op_count<br />";
			/*|||*/ $debug.= 'Session id: '.session_id().'<br />';
			/*|||*/ $debug.= 'Script execution time: '.end_timer().'<br />';
			/*|||*/ $debug.= 'Loaded templates: '.implode(', ',array_keys($this->core->templates)).'<br />';
			/*|||*/ $debug.= 'SQL query count: '.$this->core->db->query_count.'<br />';
			/*|||*/ $debug.= $this->core->db->sql_debug_output;
			/*|||*/ $debug.= '-------------------------------------------------';
			/*|||*/ $debug.= '<br /></pre>';
			//|-------------------------------------------
			//|                Debug end
			//|-------------------------------------------

		}

		//|-------------------------------------------
		//|                Which CSS?
		//|-------------------------------------------

		if ($this->core->skin['css_method'] == 'inline') {
			// Actually, all images don't work since the paths are differing :)
			$css_output = '<style type="text/css" media="screen">';
			$css_output.= $this->core->skin['css'];
			$css_output.= '</style>';

		}
		else {
			// Opera doesn't want that :/
			//$css_output = '<style type="text/css" media="screen">';
			//$css_output.= '@import url(\'images/'.$this->core->skin['image_dir'].'/css_general.css\');';
			//$css_output.= '</style>';
			$css_output = '<link rel="stylesheet" type="text/css" href="images/'.$this->core->skin['image_dir'].'/css_general.css" title="Standarddesign" media="screen">';

		}


		//|-------------------------------------------
		//|               JavaScript!
		//|-------------------------------------------
		if (isset($stuff['js'])) {

			if (file_exists(ROOT_PATH.'js/'.$stuff['js'])) {

			$js_output = '<script type="text/javascript" src="';
			$js_output.= 'js/'.$stuff['js'];
			$js_output.= '"></script>';

			}
			else {
				$js_output = null;
			}

		}
		else {
			$js_output = null;
		}


		//|-------------------------------------------
		//|           Who created da stuff?
		//|-------------------------------------------

		if (isset($stuff['creator'])) {

			/*switch ($stuff['creator']) {
				case 1:
					$creator = $this->core->templates['skin_global']->credits(1, 'Ducki');
					break;

				case 2:
					$creator = $this->core->templates['skin_global']->credits(2, 'crios');
					break;

				case 3:
					$creator = $this->core->templates['skin_global']->credits(3, 'Entrox-Scene');
					break;

				default:
					break;
			}*/
			$creator = null;

		}
		else {

			$creator = null;

		}

		//|-------------------------------------------
		//|            Replace everything
		//|-------------------------------------------

		$this->output = $this->error_msgs.$this->output;
		
		$this->core->skin['wrapper'] = str_replace('<% TITLE %>',	'Gaze - '.$stuff['title'],			$this->core->skin['wrapper']);
		$this->core->skin['wrapper'] = str_replace('<% CSS %>',		$css_output,						$this->core->skin['wrapper']);
		$this->core->skin['wrapper'] = str_replace('<% JS %>',		$js_output,							$this->core->skin['wrapper']);
		$this->core->skin['wrapper'] = str_replace('<% DEBUG %>',	$debug ? $debug : '',				$this->core->skin['wrapper']);
		$this->core->skin['wrapper'] = str_replace('<% NOTIFY %>',	$this->notifications,				$this->core->skin['wrapper']);
		$this->core->skin['wrapper'] = str_replace('<% OUTPUT %>',	$this->output,						$this->core->skin['wrapper']);


		//|-------------------------------------------
		//|                 Tidy up!
		//|-------------------------------------------

		if (preg_match('/application\/xhtml\+xml(; q=((0.[1-9])|1))?/', $_SERVER['HTTP_ACCEPT']) === 1) {

			$config = array('indent'	=> true,
			'output-xhtml'				=> true,
			'wrap'						=> 150,
			'add-xml-decl'				=> true,
			//'indent-spaces'				=> 4,
			//'vertical-space'			=> true,
			'doctype'					=> 'strict',
			'fix-uri'					=> true);

			$content_type = 'application/xhtml+xml';

		}
		else {

			$config = array('indent'	=> true,
			'output-html'				=> true,
			'wrap'						=> 150,
			//'indent-spaces'				=> 4,
			//'vertical-space'			=> true,
			'doctype'					=> 'strict',
			'fix-uri'					=> true);

			$content_type = 'text/html';

		}

		header('Content-type: '.$content_type.'; charset=ISO-8859-1');

		$this->core->skin['wrapper'] = str_replace('<% ENCODING %>',$content_type.'; charset=iso-8859-1', $this->core->skin['wrapper']);

		//|-------------------------------------------
		//|           Admin control hash
		//|    against Cross Site Request Forgery
		//|-------------------------------------------

		if ($_SESSION['member_group'] == $this->core->config['admin_group']) {

			$_SESSION['adh'] = uniqid(rand());
			output_add_rewrite_var('adh', $_SESSION['adh']);

		}


		$this->core->skin['wrapper'] = tidy_parse_string($this->core->skin['wrapper'], $config, 'latin1');

		$this->core->skin['wrapper']->cleanRepair();

		print $this->core->skin['wrapper'];


		//|-------------------------------------------
		//|                 The End.
		//|-------------------------------------------

	}


}

?>