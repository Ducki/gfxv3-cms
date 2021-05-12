<?php
/*
//|-------------------------------------------|\\
//|                    Home                   |\\
//|-------------------------------------------|\\
*/
/**
 * Home action
 *
 * @author Ducki`
 * @package V3
 * @version 1.0.0
*/
defined('on_top') or die('You cannot access this file directly. Please go to index.php.<br />Du kannst diese Datei nicht direkt aufrufen. Bitte gehe zu index.php.');

/**
 * Home action class
 *
 * The homepage
 *
 * @author Ducki`
 * @version 1.0.0
 */

class action {

	/**
	 * The Core class object
	 *
	 * @var core $core
	 */
	public $core;

	public $result;

	protected $breadcrump;


	public function run_action() {

		//|-------------------------------------------
		//|  Load the template and prepare something
		//|-------------------------------------------

		//$this->core->load_template('skin_news');


		//|-------------------------------------------
		//|             Set up structure
		//|-------------------------------------------

		switch(@$this->core->input['cmd']) {

			case 'moep':
					$title = 'Moep';
					$this->moep();
				break;
			
			default:

				$title	= 'Gaze-Testoutputs';

				$this->homepage();

			break;

		}


		//|-------------------------------------------
		//|            Output and finish
		//|-------------------------------------------

		$this->core->template->add_output($this->result);
		$this->core->template->output((array(
												'title'		=> $title
											)));

	}


	//|-------------------------------------------
	//|              Action methods
	//|-------------------------------------------

	/**
	 * Homepage
	 *
	 * The Homepage with everything
	 *
	 */
	private function homepage() {

		$this->result .= $this->core->lang['global']['moep'];

	}
	
	
	/**
	 * Moep
	 *
	 * The Moep
	 *
	 */
	private function moep() {

		$this->result .= 'Moep!';

	}


}

?>