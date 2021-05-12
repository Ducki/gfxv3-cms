<?php
/*
//|-------------------------------------------|\\
//|                    Home                   |\\
//|-------------------------------------------|\\
*/
/**
 * UCP action
 *
 * @author Ducki`
 * @package V3
 * @version 1.0.0
*/
defined('on_top') or die('You cannot access this file directly. Please go to index.php.<br />Du kannst diese Datei nicht direkt aufrufen. Bitte gehe zu index.php.');

/**
 * UCP action class
 *
 * The UCP
 *
 * @author Ducki`
 * @version 1.0.0
 */

class action {

	/**
	 * The UCP class object
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

		$this->core->load_template('skin_ucp');

		$this->breadcrump = $this->core->templates['skin_global']->breadcrump_bit('index.php', 'Startseite');
		$this->breadcrump.= $this->core->templates['skin_global']->breadcrump_bit('ucp', 'Kontrollcenter');

		//|-------------------------------------------
		//|               Logged in?
		//|-------------------------------------------
		if (!$_SESSION['member_id']) {

			$this->core->template->add_output('Erst einloggen!');

		}
		else {

			//|-------------------------------------------
			//|             Set up structure
			//|-------------------------------------------

			// select DATE_FORMAT(date_added, '%d.%m %H:%i') from act_news;
			// -> 26.04 23:42
			// :)

			// class="active_tn"

			switch(@$this->core->input['cmd']) {

				case 'save_note':
					$title = 'Kontrollcenter';
					$this->save_note();
				break;

				default:

					$title = 'Kontrollcenter';

					$this->homepage();

				break;

			}

		}
		//|-------------------------------------------
		//|            Output and finish
		//|-------------------------------------------

		$this->core->template->add_output($this->result);
		$this->core->template->output((array(	'title'	=> $title)));

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

		//|-------------------------------------------
		//|           The primary stuff..
		//|-------------------------------------------
		$this->result	.= $this->core->templates['skin_global'	]	->head_strip();
		$this->result	.= $this->core->templates['skin_ucp'	]	->left_navigation();
		$content_1		 = $this->core->templates['skin_global'	]	->breadcrump($this->breadcrump);
		$content_2		 = $this->core->templates['skin_global'	]	->page_intro($_SESSION['member_name'].'s Kontrollcenter', 'Nimm hier Einstellungen vor und verwalte deine persönlichen Sachen.');

		//|-------------------------------------------
		//|   Get the homepage (stuff comes later)
		//|-------------------------------------------

		$this->core->db->simple_select('notes', 'members', 'm_id ='.$_SESSION['member_id']);
		$res = $this->core->db->fetch();

		$content_2		.= $this->core->templates['skin_ucp'	]	->ucp_home($res['notes']);

		//|-------------------------------------------
		//|            The final stuff
		//|-------------------------------------------
		$content_3		= $this->core->templates['skin_global'	]	->right_navigation($this->core->handle_login_box());
		$this->result	.= $this->core->templates['skin_global'	]	->right_block($content_1, $content_2, $content_3);

	}


	/**
	 * Save Note
	 *
	 * Saves the users notes
	 *
	 */
	private function save_note() {
		//|-------------------------------------------
		//|     No matter what comes, put it in!
		//|-------------------------------------------
		$this->core->db->update('members', array('notes' => $this->core->input['notes']), 'm_id='.$_SESSION['member_id']);

		//|-------------------------------------------
		//|          Redirect for clean F5
		//|-------------------------------------------
		header('Location: http://localhost/gfx-v3/ucp');


	}


}

?>