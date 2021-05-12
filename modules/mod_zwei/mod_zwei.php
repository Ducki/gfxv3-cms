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
 * @package V3
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
			
			default:			
			// Default action
			break;
			
		}
		
		
		//-------------------------------------------
		//            Output and finish
		//-------------------------------------------
		$this->core->template->add_output($this->result);
		$this->core->template->output('Zwei', 'author' => 1);

	}
	

	//-------------------------------------------
	//              Module methods
	//-------------------------------------------
	

	/**
	 * Do Something
	 * 
	 * A test function for demonstrating the modules.
	 *
	 */
	private function do_something()	{

		
		
	}


}

?>