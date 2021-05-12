<?php
/*
//|-------------------------------------------|\\
//|            Session management             |\\
//|-------------------------------------------|\\
*/
/**
 * Session management
 *
 * @author Ducki`
 * @package V3
 * @version 1.0.0
*/
defined('on_top') or die('You cannot access this file directly. Please go to index.php.<br />Du kannst diese Datei nicht direkt aufrufen. Bitte gehe zu index.php.');

/**
 * Session management class
 *
 * Provides all session and member relevant functions
 * @author Ducki`
 * @version 1.0.0
 * @todo Login
 */

class session {
	/**
	 * The Core class object
	 *
	 * @var core $core
	 */
	public	$core;
	public	$browser_key;


	function __construct() {

		session_start();

	}


	/**
	 * Check Me
	 *
	 *@return void
	 */
	public function check_me() {

		//|-------------------------------------------
		//|            Already logged in?
		//|-------------------------------------------
		if (isset($_SESSION['id']) ? $_SESSION['id'] : 0) {

			//|-------------------------------------------
			//| IE seems to change its accept-header more
			//| often than we like -> removed it
			//|-------------------------------------------
			if ((isset($_SESSION['user_agent_hash']) ? $_SESSION['user_agent_hash'] : 0) == md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'])) {

				$this->get_session();

			}
			else {

				/*foreach ($_SESSION as $k => $v) {
					$_SESSION[$k] = 0;
				}*/
				$_SESSION['id']				= 0;
				$_SESSION['member_id']		= 0;
				$_SESSION['member_name']	= 'Gast';

			}

		}
		else {
			
			$_SESSION['id']					= 0;
			$_SESSION['member_id']			= 0;
			$_SESSION['member_name']		= 'Gast';

		}
		//|-------------------------------------------
		//|             Cookie, anyone?
		//|-------------------------------------------
		if (!(isset($_SESSION['id']) ? $_SESSION['id'] : 0) AND $cookie_login_key = $this->core->get_cookie('login_key')) {

			$this->core->db->simple_select('COUNT(member_login_key) AS result', 'members', 'member_login_key = \''.($cookie_login_key ? $cookie_login_key : 0).'\'');
			$member_login_key = $this->core->db->fetch();

			if ($member_login_key['result'] == 1) {
				$this->get_session();
			}
			else {
				$_SESSION['id'] = 0;
			}

		}
		/*else {

			foreach ($_SESSION as $k => $v) {
				unset($_SESSION[$k]);
			}*/


		//|-------------------------------------------
		//|      Now load the concordant member
		//|-------------------------------------------

		if (isset($_SESSION['id']) ? $_SESSION['id'] : 0) {

			$this->core->db->simple_select('m_id AS member_id, name AS member_name, mgroup AS member_group, password, email, auto_logout, login_type', 'members', 'm_id='.$_SESSION['member_id']);
			$_SESSION = $this->core->db->fetch();

			if (!isset($_SESSION['member_id'])) {

				file_put_contents('error.txt', print_r($_SESSION, true));

			}

			$_SESSION['user_agent_hash'] = $this->browser_key;

			$browser = 'todo!';

			$this->core->db->delete('sessions', 'id = \''.session_id().'\'');

			session_regenerate_id();
			$_SESSION['id'] = session_id();

			$this->core->db->insert('sessions', array(	'id'			=> $_SESSION['id'],
														'member_name'	=> $_SESSION['member_name'],
														'member_id'		=> $_SESSION['member_id'],
														'ip_address'	=> $_SERVER['REMOTE_ADDR'],
														'browser'		=> $browser,
														'browser_key'	=> $_SESSION['user_agent_hash'],
														'time_t'		=> array('NOW()', 1),
														'member_group'	=> $_SESSION['member_group']));


		}
		//|-------------------------------------------
		//|             We have a guest
		//|-------------------------------------------
		else {
			$_SESSION['id']				= 0;
			$_SESSION['member_id']		= 0;
			$_SESSION['member_name']	= 'Gast';

			$_SESSION['id'] = session_id();
			$this->core->db->delete('sessions', 'id = \''.$_SESSION['id'].'\'');

			session_regenerate_id();
			$_SESSION['id'] = session_id();

			$browser = 'todo!';

			$this->core->db->insert('sessions', array(	'id'			=> $_SESSION['id'],
														'member_name'	=> 'Gast',
														'member_id'		=> 0,
														'ip_address'	=> $_SERVER['REMOTE_ADDR'],
														'browser'		=> $browser,
														'browser_key'	=> isset($_SESSION['user_agent_hash']) ? $_SESSION['user_agent_hash'] : 0 ,
														'time_t'		=> array('NOW()', 1),
														'member_group'	=> $this->core->config['guest_group']));

			$_SESSION['member_id']		= 0;
			$_SESSION['member_name']	= 'Gast';
			$_SESSION['member_group']	= $this->core->config['guest_group'];


		}

		//|-------------------------------------------
		//|         Clean out old sessions
		//|-------------------------------------------
		$this->core->db->delete('sessions', 'TIMESTAMPDIFF(MINUTE, time_t, NOW())>30');



	}

	/**
	 * Get Session
	 *
	 * Returns the user's session or unsets if n/a
	 *
	 */
	private function get_session() {

		if ($_SESSION['id']) {

			$this->core->db->simple_select('member_name, member_id, member_group, browser_key', 'sessions', 'id=\''.$_SESSION['id'].'\'');
			$row = $this->core->db->fetch();

			if ($row) {

				$_SESSION['member_id']	= $row['member_id'];
				$this->browser_key		= $row['browser_key'];

			}
			else {
				$_SESSION['member_id']	= 0;
				$_SESSION['id']			= 0;
			}

		}
		else {
			$_SESSION['member_id']		= 0;
			$_SESSION['id']				= 0;
		}

	}



}

?>