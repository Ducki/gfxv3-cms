<?php
/*
//|-------------------------------------------|\\
//|               Module loader               |\\
//|-------------------------------------------|\\
*/
/**
 * Module loader
 *
 * @author Ducki`
 * @package V3
 * @version 1.0.0
*/
defined('on_top') or die('You cannot access this file directly. Please go to index.php.<br />Du kannst diese Datei nicht direkt aufrufen. Bitte gehe zu index.php.');

/**
 * Module loading class
 * 
 * Loads the specified module
 *
 * @author Ducki`
 * @version 1.0.0
 */

class mod_loader {

	public $core;

	public $module;

	
	public function run_loader() {

		$this->module = preg_replace('/[^a-zA-Z0-9\-\_]/', '', $this->core->input['module']);

		if ($this->module == '') {
			$this->return_dead();
		}

		if (!file_exists(ROOT_PATH.'modules/mod_'.$this->module.'/mod_'.$this->module.'.php')) {
			$this->return_dead();
		}

		require_once ROOT_PATH.'modules/mod_'.$this->module.'/mod_'.$this->module.'.php';

		$mod_run			= new module();
		$mod_run->core		= $this->core;
		$mod_run->run_module();

		exit();
	}
	
	private function return_dead() {

		header('Location: http://www.gfx-world.net/');
		
		exit();
		
	}

}


?>