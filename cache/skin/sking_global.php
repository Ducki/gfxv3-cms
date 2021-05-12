<?php
/*
This file has been generated automatically.
Changes in here will be reseted when rebuilding cache.
If you want to change the template go to the acp.
*/

class sking_global{
	

//|-------------------------------------------
//| header_and_left_navi
//|-------------------------------------------
function header_and_left_navi($a_home = '', $a_galery = '', $a_workshops = '', $a_board = '') {

return <<<TOP
<div id="header">
				<img src="images2/header.gif" alt="Kopfgrafik" width="931" height="93"/>
			</div>

			<div id="middle">

				<div id="left_navi">

					<p class="upper_ribbon">

						upper ribbon

					</p>

					<form action="#" method="post">

						<p>
							<input type="text" name="search_query" id="search_query"/>

						</p>

					</form>

					<h3>Navigation</h3>
					<ol id="left_navi">
						<li>
							<a href="#"{$a_home}>Startseite</a>
						</li>

						<li>
							<a href="#"{$a_galery}>Galerie</a>
						</li>

						<li>
							<a href="#"{$a_workshops}>Workshops</a>
						</li>

						<li>
							<a href="#"{$a_board}>Forum</a>
						</li>
					</ol>

					<br style="clear: both;"/>
				</div>
TOP;

}


}

?>
