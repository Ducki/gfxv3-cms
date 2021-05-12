<?php

class error_handler {

	/**
	 * The core object
	 *
	 * @var core $core
	 */
	public $core;

	private $errfile;
	private $errno;
	private $errstr;
	private $errline;
	private $out;

	private $error_msg;

	function __construct($errno, $errstr, $errfile, $errline) {

		$this->errfile = $errfile;
		$this->errno = $errno;
		$this->errstr = $errstr;
		$this->errline = $errline;

	}

	function handle() {

		switch ($this->errno) {
			case E_ERROR:
				$this->error_msg = '<div style="width: 80%; margin: 0 auto; padding: 10px; background: #ffdddd; border: 1px solid #f00;"><h1 style="color: #c00;">V3 DEVELOPMENT ERROR</h1> ['.$this->errno.'] '.$this->errstr.' (Line: '.$this->errline.' of '.$this->errfile.')</div><br />';
				//echo '<div style="width: 50%; margin: 0 auto; padding: 10px; background: #ffdddd; border: 1px solid #f00;"><h1 style="color: #c00;">V3 DEVELOPMENT ERROR</h1> ['.$errno.'] '.$errstr.' (Line: '.$errline.' of '.$errfile.')</div><br />';
				exit(1);
				break;

			case E_WARNING:
				$this->error_msg = '<div style="width: 80%; margin: 0 auto; padding: 10px; background: #ffdddd; border: 1px solid #f00;"><h1 style="color: #c00;">V3 DEVELOPMENT WARNING</h1><p>['.$this->errno.'] '.$this->errstr.'</p>Line: <b>'.$this->errline.'</b><br />File: <b>'.$this->errfile.'</b></div><br />';
				//	echo '<div style="width: 50%; margin: 0 auto; padding: 10px; background: #ffdddd; border: 1px solid #f00;"><h1 style="color: #c00;">V3 DEVELOPMENT WARNING</h1><p>['.$errno.'] '.$errstr.'</p>Line: <b>'.$errline.'</b><br />File: <b>'.$errfile.'</b></div><br />';
				break;

			case E_NOTICE:

				if ((strpos($this->errstr, 'Undefined index') === false) AND (strpos($this->errstr, 'could not be converted to int') === false)) {
					$this->error_msg = '<div style="width: 80%; margin: 0 auto; padding: 10px; background: #ffdddd; border: 1px solid #f00;"><h1 style="color: #c00;">V3 DEVELOPMENT NOTICE</h1><p>['.$this->errno.'] '.$this->errstr.'</p>Line: <b>'.$this->errline.'</b><br />File: <b>'.$this->errfile.'</b></div><br />';
					//	echo '<div style="width: 50%; margin: 0 auto; padding: 10px; background: #ffdddd; border: 1px solid #f00;"><h1 style="color: #c00;">V3 DEVELOPMENT NOTICE</h1><p>['.$errno.'] '.$errstr.'</p>Line: <b>'.$errline.'</b><br />File: <b>'.$errfile.'</b></div><br />';
				}
				break;

			case E_USER_ERROR:
				$this->error_msg = '<div style="width: 80%; margin: 0 auto; padding: 10px; background: #ffdddd; border: 1px solid #f00;"><h1 style="color: #c00;">V3 TRIGGERED ERROR</h1><p>['.$this->errno.'] '.$this->errstr.'</p>Line: <b>'.$this->errline.'</b><br />File: <b>'.$this->errfile.'</b></div><br />';
				//echo '<div style="width: 50%; margin: 0 auto; padding: 10px; background: #ffdddd; border: 1px solid #f00;"><h1 style="color: #c00;">V3 TRIGGERED ERROR</h1><p>['.$errno.'] '.$errstr.'</p>Line: <b>'.$errline.'</b><br />File: <b>'.$errfile.'</b></div><br />';
				break;

			default:
				$this->error_msg = '<div style="width: 80%; margin: 0 auto; padding: 10px; background: #ffdddd; border: 1px solid #f00;"><b>Unkown error type:</b><p>['.$this->errno.'] '.$this->errstr.'</p>Line: <b>'.$this->errline.'</b><br />File: <b>'.$this->errfile.'</b></div><br />';
				//echo '<div style="width: 50%; margin: 0 auto; padding: 10px; background: #ffdddd; border: 1px solid #f00;"><b>Unkown error type:</b><p>['.$errno.'] '.$errstr.'</p>Line: <b>'.$errline.'</b><br />File: <b>'.$errfile.'</b></div><br />';
				break;
		}

		if ($this->error_msg) {

			$this->out .= '<div style="padding: 10px; background: #eee; border: 1px solid #999"><p><strong>Some errors appeared:</strong></p>';
			$this->out .= $this->error_msg;
			$this->out .= '</div>';

			$this->core->template->add_error($this->out);

		}



	}

}

?>