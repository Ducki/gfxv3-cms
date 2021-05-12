<?php
/*
//|-------------------------------------------|\\
//|                   Login                   |\\
//|-------------------------------------------|\\
*/
/**
 * Login action
 *
 * @author Ducki`
 * @package V3
 * @version 1.0.0
*/
defined('on_top') or die('You cannot access this file directly. Please go to index.php.<br />Du kannst diese Datei nicht direkt aufrufen. Bitte gehe zu index.php.');

/**
 * Login action class
 *
 * The login handlers
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


	public function run_action() {

		//-------------------------------------------
		//            Load the template
		//-------------------------------------------

		//$this->core->load_template('skin_news');


		//-------------------------------------------
		//             Set up structure
		//-------------------------------------------


		switch(@$this->core->input['cmd']) {

			case 'login_form':
			$this->login_form();
			break;

			case 'login':
			$this->login();
			break;

			case 'logout':
			$this->logout();
			break;

			default:
			$this->core->template->add_output("No command given.");
			break;

		}


		//-------------------------------------------
		//            Output and finish
		//-------------------------------------------

		$this->core->template->add_output($this->result);
		$this->core->template->output(array(	'title'	=> 'Login'));

	}


	//-------------------------------------------
	//              Action methods
	//-------------------------------------------


	/**
	 * Login Form
	 *
	 * Shows the login form
	 */
	private function login_form() {

		$this->result .= $this->core->templates['skin_global']->login_form();
		// gibts noch nicht! olo
	}



	/**
	 * Login
	 *
	 * The login
	 */
	private function login() {

		//-------------------------------------------
		//       Username and password given?
		//-------------------------------------------
		if (empty($this->core->input['username'])) {

			echo 'USRNAME not given, exiting.'; exit();

		}

		if (empty($this->core->input['password'])) {

			echo 'PASSWORD not given, exiting.'; exit();

		}

		//-------------------------------------------
		//           Check username length
		//-------------------------------------------
		if (strlen($this->core->input['username']) > 18) {

			echo 'USERNAME too long, exiting.'; exit();

		}

		//-------------------------------------------
		//           Check me if you can!
		//-------------------------------------------
		$res = $this->core->db->simple_select('	m_id AS member_id,
												name AS member_name,
												mgroup AS member_group',
												'members',
												'name = \''.mysql_escape_string($this->core->input['username']).'\' AND password = \''.md5($this->core->input['password']).'\'');

		$_SESSION = $this->core->db->fetch();

		if ($_SESSION['member_id']) {
			//|-------------------------------------------
			//| IE seems to change its accept-header more
			//| often than we like -> removed it
			//|-------------------------------------------
			$_SESSION['user_agent_hash'] = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);

			session_regenerate_id();
			$_SESSION['id'] = session_id();

			$this->core->db->insert('sessions', array(	'id'			=> $_SESSION['id'],
														'member_name'	=> $_SESSION['member_name'],
														'member_id'		=> $_SESSION['member_id'],
														'ip_address'	=> $_SERVER['REMOTE_ADDR'],
														'browser'		=> 'todo',
														'browser_key'	=> $_SESSION['user_agent_hash'],
														'time_t'		=> array('NOW()', 1),
														'member_group'	=> $_SESSION['member_group']));

			header('Location: http://localhost/gfx-v3/index.php');

			exit();

		}
		else {

			//-------------------------------------------
			//     We have either 2 or none members
			//-------------------------------------------
			die('falsches pw! >:(');
		}
		print_r($_SESSION);

	}


	private function logout() {

		unset($_SESSION);

		header('Location: http://localhost/gfx-v3/');
		exit();

	}


}

?>