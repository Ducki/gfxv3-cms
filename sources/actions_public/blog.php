<?php
/*
//|-------------------------------------------|\\
//|                User-Blog                  |\\
//|-------------------------------------------|\\
*/
/**
 * User-blog action
 *
 * @author Ducki`
 * @package V3
 * @version 1.0.0
*/
defined('on_top') or die('You cannot access this file directly. Please go to index.php.<br />Du kannst diese Datei nicht direkt aufrufen. Bitte gehe zu index.php.');

/**
 * User-blog action class
 *
 * The UCP
 *
 * @author Ducki`
 * @version 1.0.0
 */

class action {

	/**
	 * The User-blog class object
	 *
	 * @var core $core
	 */
	public $core;

	public $result;

	protected $breadcrump;


	public function run_action() {

		//-------------------------------------------
		//  Load the template and prepare something
		//-------------------------------------------

		$this->core->load_template('skin_ucp');

		$this->breadcrump = $this->core->templates['skin_global']->breadcrump_bit('index.php', 'Startseite');
		$this->breadcrump.= $this->core->templates['skin_global']->breadcrump_bit('blog', 'Blog');

		//-------------------------------------------
		//               Logged in?
		//-------------------------------------------
		if (!$_SESSION['member_id']) {

			$this->core->template->add_output('Erst einloggen!');

		}
		else {

			//-------------------------------------------
			//             Set up structure
			//-------------------------------------------
			switch(@$this->core->input['cmd']) {

				default:

					$title = 'Blog';

					$this->homepage();

				break;

			}

		}
		//-------------------------------------------
		//            Output and finish
		//-------------------------------------------

		$this->core->template->add_output($this->result);
		$this->core->template->output((array(	'title'	=> $title)));

	}


	//-------------------------------------------
	//              Action methods
	//-------------------------------------------

	/**
	 * Homepage
	 *
	 * The Homepage with everything
	 *
	 */
	private function homepage() {


	}



}

?>