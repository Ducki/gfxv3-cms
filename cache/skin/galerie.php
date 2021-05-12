<?php
/*
This file has been generated automatically.
Changes in here will be reseted when rebuilding cache.
If you want to change the template go to the acp.
*/

class galerie{
	

//|-------------------------------------------
//| wip_head
//|-------------------------------------------
function wip_head($a_bereiche_alle = '', $a_bereiche_grafik = '', $a_bereiche_3d = '', $a_bereiche_traditional = '', $a_bereiche_fotografie='') {

return <<<TOP
<h1>Galerie - Work in Progress </h1>
<div class="navi_line">
<b>Bereiche:</b> <a href="{$a_bereiche_alle}" title="WIP - Alle" >Alle</a>  <a href="{$a_bereiche_grafik}" title="WIP - Grafik" >Grafik</a>  <a href="{$a_bereiche_3d}" title="WIP - 3d" >3d</a>  <a href="{$a_bereiche_traditional}" title="WIP - Traditional" >Traditional</a> <a href="{$a_bereiche_fotografie}" title="WIP - Fotografie" >Fotografie</a>
</div>
TOP;

}



//|-------------------------------------------
//| wip_item
//|-------------------------------------------
function wip_item($img1 = '', $img2 = '', $img3 = '', $titel = '', $user_name = '', $last_activ = '', $anz_partner = 0,$bgcolor = '#ffffff') {

return <<<TOP
<div class="galerie_wip_listitem" style="background-color: {$bgcolor}";>
<h2>{$titel}</h2>
<p>
Letzte Aktivität: {$last_activ} Vorgänger: {$anz_partner} Autor: {$user_name}
</p>
{$img1} {$img2} {$img3}
<br style="clear:both;"/>
</div>


TOP;

}



//|-------------------------------------------
//| tn_90x65_border
//|-------------------------------------------
function tn_90x65_border($img_link = '#',$img_path = '', $img_titel = '') {

return <<<TOP
<div class="galerie_90x65thumbnail" valign="middle"><a href="{$img_link}"><img src="{$img_path}" alt="{$img_titel}" /></a></div>
TOP;

}


}

?>
