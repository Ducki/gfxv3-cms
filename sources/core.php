<?php
/*
//|-------------------------------------------|\\
//|                Core class                 |\\
//|-------------------------------------------|\\
*/
/**
 * Core class
 *
 * The main core class.
 * It holds allmost all other classes,
 * e.g. database, cache or member-settings.
 *
 * @author Ducki`
 * @package V3
 * @version 1.0.0
*/
defined('on_top') or die('You cannot access this file directly. Please go to index.php.<br />Du kannst diese Datei nicht direkt aufrufen. Bitte gehe zu index.php.');

/**
 * Core class
 *
 * The main core class.
 * It holds allmost all other classes,
 * e.g. database, cache or member-settings.
 *
 * @author Ducki`
 * @package V3
 * @version 1.0.0
 */
class core {

	public $settings;

	public $session;

	/**
	 * The template object
	 *
	 * @var template $template
	 */
	public $template;

	public $lang;

	protected $stuff_cache;

	public $config;

	public $magic_quotes;

	public $input;
	
	public $files;

	public $skin;

	public $templates;

	public $member;
	
	public $request;
	
	public $languages;
	

	/**
	 * Database Object
	 *
	 * @var db $db
	 */
	public $db;

	function __call($m, $a) {
		trigger_error('Called to not existing method <b>'.$m.'</b>', E_USER_ERROR);
	}

	function __get($m) {
		trigger_error('Called to not existing member <b>'.$m.'</b>', E_USER_ERROR);
	}

	function __construct() {
		
		//|-------------------------------------------
		//|           Gimme the settings!
		//|-------------------------------------------

		require_once ROOT_PATH.'config.php';

		$this->config = $c_vars;
		
		//|-------------------------------------------
		//|          Gimme the stuff cache!
		//|-------------------------------------------
		

		
		$this->stuff_cache = unserialize(file_get_contents(ROOT_PATH.'cache/stuff_cache.php'));
		
		//|-------------------------------------------
		//|       Is there an upload awaiting?
		//|-------------------------------------------
		
		if (!empty($_FILES)) {
			
			$this->handle_upload();
			
		}
		
	}

	
	
	function init_db() {

		require_once ROOT_PATH.'sources/core/db.php';

		$this->db=new db;
		$this->db->connect();

	}
	

	
	public function handle_request_uri() {
		
		//|-------------------------------------------
		//| If we are directly requested, don't 'rewrite'
		//|-------------------------------------------
		if (strpos($_SERVER['REQUEST_URI'], 'index.php') !== false) {
			return;
		}
		
		$this->template->add_output($uri = $_SERVER['REQUEST_URI']);
		$uri = str_replace('/gfx-v3/', '', $uri);
		$uri = str_replace('/v3/dev/', '', $uri);

		$this->request = explode('/', $uri);

		$this->input['act'] = isset($this->request[0]) ? $this->request[0] : 'home';
		$this->input['cmd'] = isset($this->request[1]) ? $this->request[1] : null;
		
		$this->template->add_output(print_r($this->request, true));

	}
	

	/**
	 * Load Skin
	 *
	 * Loads the skin and populates $skin array
	 */
	public function load_skin($acp = false) {

		if ($acp) {
			$skin = $this->config['acp_default_skin'];
		}
		else {
			$skin = $this->config['default_skin'];
		}

		$result		= $this->db->simple_select('skin_set_id AS set_id,
													name,
													image_dir,
													css_method,
													css,
													cache_macro,
													wrapper,
													emoticon_folder',
		
													'skin_sets',
													
													'skin_set_id = '.$skin);
		$this->skin	= $this->db->fetch();

	}


	/**
	 * Loads the specified template
	 *
	 * @param string $name Template name
	 */
	public function load_template($name) {

		if ($name != 'skin_global')	{
			
			if (!in_array('skin_global', $this->templates))	{
				
				require_once(ROOT_PATH.'cache/skin/skin_global.php');

				$this->templates['skin_global']			= new skin_global();
				$this->templates['skin_global']->core	= $this;
			}

			require_once(ROOT_PATH.'cache/skin/'.$name.'.php');

			$this->templates[$name]			= new $name();
			$this->templates[$name]->core	= $this;
			
		}
		else {
			
			if ($name == 'skin_global') {
				
				require_once(ROOT_PATH.'cache/skin/skin_global.php');

				$this->templates['skin_global']			= new skin_global();
				$this->templates['skin_global']->core	= $this;

				return;
				
			}
			else {
				
				require_once(ROOT_PATH.'cache/skin/'.$name.'.php');
				$this->templates[$name]			= new $name();
				$this->templates[$name]->core	= $this;
				
			}
		}

	}


	/**
	 * Loads the specified language
	 *
	 * @param string $name Language name
	 */
	public function load_language($name, $lang) {
		
		//|-------------------------------------------
		//|          Temporary lang setting
		//|-------------------------------------------
		
		$rx_l = '';
		
		foreach ($this->languages as $v) {
			
			$rx_l .= $v.'|';
			
		}
		
		$rx_l = substr($rx_l, 0, strlen($rx_l)-1);
		$l_matches = array();
		
		if (preg_match('/lang_('.$rx_l.')/', $_SERVER['REQUEST_URI'], $l_matches)) {
			$lang = $l_matches[1];
		}
		
		
		include ROOT_PATH.'lang/'.$lang.'/'.$name.'.php';
		
		$this->lang[$name] = $i_lang;
		
	}

	
	/**
	 * Get Allowed Languages
	 * 
	 * Fetches all allowed (and possible) languages
	 *
	 */
	public function get_allowed_languages() {
		
		$this->db->simple_select('name, dir', 'languages');
		
		while ($row=$this->db->fetch()) {
			
			$allowed_languages[]=$row['dir'];
			
			
		}
		return $allowed_languages;
	}

		

	/**
	 * Returns the browser language
	 *
	 * @param bool $strict_mode
	 * @return string Found language
	 */
	public function get_browser_lang($strict_mode = true) {
	
		$default_language = $this->config['default_lang'];
		$allowed_languages = $this->languages;

		$accepted_languages = preg_split('/,\s*/', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

		$current_lang = $default_language;
		$current_q = 0;

		foreach ($accepted_languages as $accepted_language) {

			$res = preg_match ('/^([a-z]{1,8}(?:-[a-z]{1,8})*)'.
			'(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i', $accepted_language, $matches);

			if (!$res) {
				continue;
			}

			$lang_code = explode('-', $matches[1]);

			if (isset($matches[2])) {
				$lang_quality = (float)$matches[2];
			} else {
				$lang_quality = 1.0;
			}

			while (count($lang_code)) {

				if (in_array (strtolower(join ('-', $lang_code)), $allowed_languages)) {

					if ($lang_quality > $current_q) {

						$current_lang = strtolower(join('-', $lang_code));
						$current_q = $lang_quality;

						break;
					
					}
				}

				if ($strict_mode) {
					break;
				}

				array_pop ($lang_code);
			}
		}

		return $current_lang;
		
	}


	/**
	 * Parse Input
	 *
	 * Parses the GET and POST data and cleans it
	 */
	public function parse_input() {

		$this->magic_quotes = get_magic_quotes_gpc();

		if (is_array($_GET)) {
			while(list($k, $v) = each($_GET)) {
				$this->input[$this->clean_me($k)] = $this->clean_me($v);
			}
		}

		if (is_array($_POST)) {
			while(list($k, $v) = each($_POST)) {
				$this->input[$this->clean_me($k)] = $this->clean_me($v);
			}
		}
		
		//|-------------------------------------------
		//|          Destroy The Others! >:(
		//|-------------------------------------------
		
		unset($_GET, $_POST);

	}


	/**
	 * Clean Me
	 *
	 * Cleans the given data
	 *
	 * @param string $text Data for cleaning
	 * @return string Cleaned data
	 */
	public function clean_me($text) {
		//$text = strip_tags($text);

		if ($this->magic_quotes) {
			$text = stripslashes($text);
		}
		
		$text = htmlentities($text);
		$text = trim($text);

		return $text;
	}

	/**
	 * Get Cookie
	 *
	 * Returns the cleaned content of the specified cookie
	 *
	 * @param string $name Name of cookie
	 * @return string Cleaned content
	 */
	public function get_cookie($name) {
		if (isset($_COOKIE[$name])) {
			return clean_me($_COOKIE[$name]);
		}
		else {
			return false;
		}
	}
	


	/**
	 * Create URL
	 * 
	 * Creates a query string or a mod_rewrite capable url
	 * 
	 * Example:
	 * <code>
	 * $url = array('foo'=>'bar',
     *        'baz'=>'boom',
     *        'cow'=>'milk',
     *        'php'=>'hypertext processor');
     * 
     * create_url($url);
     * 
     * // returns: http://duckinet.info/v3/dev/index.php?foo=bar&baz=boom&cow=milk&php=hypertext+processor
	 * 
	 * </code>
	 *
	 * @param array $request The query string components
	 * @return string The arranged URL
	 */
	public function create_url($request) {
		
		if (MOD_REWRITE) {
			
			$url = 'http://duckinet.info/v3/dev/';
			
			$uri = "";
			foreach ($request as $k) {
				
				$uri.= $k.'/';
				
			}
			
			$settings = $this->url_modifikations;
			foreach($settings AS $k => $v) {
				$uri = str_replace($v,$k,$uri);
			}
			
			$url.=$uri;
			
			return $url;
			
		}
		else {
			$url = 'http://duckinet.info/v3/dev/index.php?';
			$url.= http_build_query($request);

			return $url;
			
		}

	}
	
	

	/**
	 * Handle Upload
	 * 
	 * Handles the upload
	 * 
	 * Error codes:
	 * 1: No upload
	 * 2: Not valid upload type
	 * 3: Upload exceeds $max_file_size
	 * 4: Could not move uploaded file, upload deleted
	 * 5: File pretends to be an image but isn't (poss XSS attack)
	 *
	 * @return void
	 */
	public function handle_upload() {
		
		//|-------------------------------------------
		//|           OK, let's handle it!
		//|-------------------------------------------

		foreach ($_FILES as $k => $v) {
		
			//|-------------------------------------------
			//|          Allowed file extension?
			//|-------------------------------------------
			
			$ext = strtolower(str_replace('.', '', substr($v['name'], strrpos($v['name'], '.'))));
			
			if (!in_array($ext, $this->config['allowed_file_ext'])) {
				
				$this->files[$k]['error'] = 2;
				
				continue;
				
			}
			
			//|-------------------------------------------
			//|        Strip whitespaces off name
			//|-------------------------------------------
			$this->files[$k]['name'] = preg_replace( '/[^\w\.]/', '_', $v['name']);
			
			//|-------------------------------------------
			//|                 Move it!
			//|-------------------------------------------
			
			$local_name = md5($this->files[$k]['name'].microtime()).'.'.$ext;
			
			if(!move_uploaded_file($_FILES[$k]['tmp_name'], 'uploads/'.$local_name)) {
				
				$this->files[$k]['error'] = 4;
				
				continue;

			}
			
			chmod('uploads/'.$local_name, 0777);
			
			//|-------------------------------------------
			//|           Is the image real?
			//|-------------------------------------------
			if (in_array($ext, $this->config['allowed_img_ext'])) {
				
				$img_size = @getimagesize('uploads/'.$local_name);
				
				if (!is_array($img_size) OR !count($img_size) OR !$img_size[2]) {
					
					unlink('uploads/'.$local_name);
					
					$this->files[$k]['error'] = 5;
					
				}
				
			}
			
			//|-------------------------------------------
			//|           Give them the rest
			//|-------------------------------------------
			
			$this->files[$k]['size']		= $_FILES[$k]['size'];
			$this->files[$k]['type']		= $_FILES[$k]['type'];
			$this->files[$k]['local_name']	= $local_name;
			
			
			
		}
		
		unset($_FILES);
		
	}
	
	
	/**
	 * Verify Upload
	 * 
	 * Verifies against given rules
	 * Example:
	 * <code>
	 * $options = array(	'datei_1' => array(	'max_size'	=> 250, // for an image file
	 *											'max_width'	=> 80,
	 *											'max_height'=> 80,
	 *											'extensions'=> array('jpg', 'gif', 'png')
	 *									),
	 *						'datei_2' => array(	'max_size'	=> 6000,
	 *											'extensions'=> array('zip', 'rar'), // for an archive
	 *											'delete'	=> true
	 *									)
	 *					);
	 * </code>
	 *
	 * @param array $options The 'rules'
	 */
	public function verify_upload($options) {
		$return = "";
		if (!is_array($options)) {

			return false;

		}
		
		
		//|-------------------------------------------
		//|             Cycle through!
		//|-------------------------------------------
		foreach ($options as $k => $v) {

			//|-------------------------------------------
			//|         Supposed to check size?
			//|-------------------------------------------
			if (!($this->files[$k]['size'] <= $v['max_size'])) {

				$return[$k] = 'max_size_exceeded';

				continue;

			}

			//|-------------------------------------------
			//|                The width?
			//|-------------------------------------------
			$size = getimagesize('uploads/'.$this->files[$k]['local_name']);

			if (!($size[0] <= $v['max_width'])) {

				$return[$k] = 'max_width_exceeded';

				continue;

			}

			//|-------------------------------------------
			//|                The height?
			//|-------------------------------------------			
			if (!($size[1] <= $v['max_height'])) {

				$return[$k] = 'max_width_exceeded';

				continue;

			}

			//|-------------------------------------------
			//|              The extension?
			//|-------------------------------------------

			$ext = strtolower(str_replace('.', '', substr($this->files[$k]['local_name'], strrpos($this->files[$k]['local_name'], '.'))));

			if (!in_array($ext, $v['extensions'])) {

				$return[$k] = 'forbidden_ext';

				continue;

			}

		}

		return $return;

	}
	
	
	/**
	 * Handle Login Box
	 * 
	 * Well, handles the login-box.
	 * 
	 * @todo PM-Check
	 * @return unknown
	 */
	public function handle_login_box() {
		
		//|-------------------------------------------
		//| So, are we logged in? Then show the user box.
		//|-------------------------------------------
		if ($_SESSION['member_id']) {
			
			return $this->templates['skin_global']->user_box($_SESSION['member_name']);
			
		}
		else {
			$create_url = array("login","login");
			$url = $this->create_url($create_url);
			return $this->templates['skin_global']->login_box($url);
			
		}
		
	}

		
	/**
	 * Check Permission
	 * 
	 * Checks the permission of given action.
	 *
	 * @param int $group_id The group id
	 * @param string $perm_title The permission title
	 * @return mixed
	 */
	public function check_permission($group_id, $perm_title) {
		
		if (isset($this->stuff_cache[$group_id][$perm_title])) {

			return $this->stuff_cache[$group_id][$perm_title];
			
		}
		else {
			
			return false;
			
		}
		
	}


}


?>