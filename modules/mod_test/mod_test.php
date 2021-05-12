<?php
/*
//|-------------------------------------------|\\
//|                Test module                |\\
//|-------------------------------------------|\\
*/
/**
 * Test module
 *
 * @author Ducki`
 * @package V3 Gaze
 * @version 1.0.0
*/
defined('on_top') or die('You cannot access this file directly. Please go to index.php.<br />Du kannst diese Datei nicht direkt aufrufen. Bitte gehe zu index.php.');

/**
 * Test module class
 * 
 * Just for testing purposes
 *
 * @author Ducki`
 * @version 1.0.0
 */

class module {

	public $core;

	public $result;


	public function run_module() {

		//-------------------------------------------
		//            Load the template
		//-------------------------------------------

		//$this->core->load_template('skin_miep');

		
		//-------------------------------------------
		//             Set up structure
		//-------------------------------------------

		switch(@$this->core->input['cmd']) {
			case 'dosomething':
			$this->do_something();
			break;

			default: // Standard-Dingens eben..
			$this->core->template->add_output('No command! >:(');
			break;
		}

		
		//-------------------------------------------
		//           Output and finish :)
		//-------------------------------------------

		$this->core->template->add_output($this->result);
		$this->core->template->output();

	}


	private function do_something()	{

		if ($_SESSION['group'] == $this->core->config['admin_group']) {
			$this->result = "You're an admin!";
		}
		else {
			$this->result = 'You\'re not an admin!<br />';
			$this->result .= 'Du bist in Gruppe: ';
			$this->result .= $this->core->config['group_'.$_SESSION['group']];
		}
		
		$this->result .= $this->core->templates['skin_global']->miep_test('moep määp miep :)');
	}


}

?>