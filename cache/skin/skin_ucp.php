<?php
/*
This file has been generated automatically.
Changes in here will be reseted when rebuilding cache.
If you want to change the template go to the acp.
*/

class skin_ucp{
	

//|-------------------------------------------
//| left_navigation
//|-------------------------------------------
function left_navigation() {

return <<<TOP
<div id="left_navigation">

				<h4>Suche</h4>

				<form action="#" method="post">

					<fieldset>
						<input type="text" name="search_term" id="search_term"/>

						<button type="submit">Suchen</button>
					</fieldset>

				</form>

				<h3>
					UCP
					<span>Dein Kontrollcenter</span>
				</h3>

				<ul>

					<li>
						<a href="#">Profil bearbeiten</a>
					</li>

					<li>
						<a href="#">Blog verwalten</a>

					</li>

					<li>
						<a href="#">Eigene Beiträge</a>
					</li>

					<li>
						<a href="#">Dateimanager</a>
					</li>

					<li>
						<a href="#">Favoriten verwalten</a>
					</li>

					<li>
						<a href="#">Buddys verwalten</a>
					</li>

				</ul>

			</div>
TOP;

}



//|-------------------------------------------
//| ucp_home
//|-------------------------------------------
function ucp_home($notes = '', $items_1 = '', $items_2 = '') {

return <<<TOP
<h2>Notizen</h2>
<form id="note-pad" action="ucp/save_note" method="post">

<p>
<textarea cols="50" rows="5" name="notes">{$notes}</textarea>
<button type="submit" accesskey="s">Speichern</button>
</p>

</form>
<h2>Neue Kommentare auf</h2>

<ul class="simple_listing">
{$items_1}
</ul>

<h2>Zuletzt betrachtete Dinge</h2>

<ul class="simple_listing">
{$items_2}
</ul>
TOP;

}


}

?>
