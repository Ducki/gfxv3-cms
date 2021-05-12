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
echo '--- '.$_SESSION['id'].' -- AGENT HASH: --- '.$_SESSION['user_agent_hash'];
echo 'BROWSER_STUFF --- '.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$_SERVER['HTTP_ACCEPT'];
		//|-------------------------------------------
		//|            Already logged in?
		//|-------------------------------------------
		if (isset($_SESSION['id']) ? $_SESSION['id'] : 0) {

			if ($_SESSION['user_agent_hash'] == md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$_SERVER['HTTP_ACCEPT'])) {
echo '<br>BROWSER MATCH -- '.$_SESSION['id'].'<br>';

				$this->get_session();

			}
			else {
echo '<br> !!! NO BROWSER MATCH -- '.$_SESSION['id'].'<br>';
				/*foreach ($_SESSION as $k => $v) {
					$_SESSION[$k] = 0;
				}*/
				foreach ($_SESSION as $k => $v) {
					unset($_SESSION[$k]);
				}

			}

		}
		else {
echo '<br> !!! NO starting session ID -- '.$_SESSION['id'].'<br>';
			foreach ($_SESSION as $k => $v) {
				unset($_SESSION[$k]);
			}

		}
		//|-------------------------------------------
		//|              Cookie, anyone?
		//|-------------------------------------------
		if (!(isset($_SESSION['id']) ? $_SESSION['id'] : 0) AND $cookie_login_key = $this->core->get_cookie('login_key')) {
echo 'COOKIE -- '.$_SESSION['id'].'<br>';
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
echo 'COOKIE ELSE -- '.$_SESSION['id'].'<br>';
			foreach ($_SESSION as $k => $v) {
				unset($_SESSION[$k]);
			}*/


		//|-------------------------------------------
		//|      Now load the concordant member
		//|-------------------------------------------

		if (isset($_SESSION['id']) ? $_SESSION['id'] : 0) {
echo 'LOAD MEMBER -- '.$_SESSION['id'].'<br>';
			$this->core->db->simple_select('m_id AS member_id, name AS member_name, mgroup AS member_group, password, email, auto_logout, login_type', 'members', 'm_id='.$_SESSION['member_id']);
			$_SESSION = $this->core->db->fetch();
			$_SESSION['user_agent_hash']= $this->browser_key;
echo 'MEMBER LOADED: '; print_r($_SESSION);
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
echo 'GUEST -- '.$_SESSION['id'].' <br>';
			foreach ($_SESSION as $k => $v) {
				unset($_SESSION[$k]);
			}

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

echo 'GUEST2 -- '.$_SESSION['id'].' <br>';
		}

		//|-------------------------------------------
		//|         Clean out old sessions
		//|-------------------------------------------
		$this->core->db->delete('sessions', 'TIMESTAMPDIFF(MINUTE, time_t, NOW())>30');



	}

	/**
	 * Get Session
	 *
	 * [...]
	 *
	 */
	private function get_session() {

		if ($_SESSION['id']) {
echo 'eins -- '.$_SESSION['id'].'<br>';
			$this->core->db->simple_select('member_name, member_id, member_group, browser_key', 'sessions', 'id=\''.$_SESSION['id'].'\'');
			$row = $this->core->db->fetch();

			if ($row) {
echo 'zwei -- '.$_SESSION['id'].' --- member-id: '.$row['member_id'].' <br>';
				$_SESSION['member_id'] = $row['member_id'];
				$this->browser_key = $row['browser_key'];

			}
			else {
echo 'drei -- '.$_SESSION['id'].'<br>';
				unset($_SESSION);
				$_SESSION['member_id'] = 0;
				$_SESSION['id'] = 0;
			}

		}
		else {
echo 'vier -- '.$_SESSION['id'].'<br>';
			$_SESSION['member_id'] = 0;
			$_SESSION['id'] = 0;
		}

	}



}

?>