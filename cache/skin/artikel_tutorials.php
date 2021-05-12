<?php
/*
This file has been generated automatically.
Changes in here will be reseted when rebuilding cache.
If you want to change the template go to the acp.
*/

class artikel_tutorials{
	

//|-------------------------------------------
//| createform
//|-------------------------------------------
function createform($artikel_titel="", $form_submittitel="Absenden") {

return <<<TOP
<form method="POST" action="" >
<label for="titel" >Titel: </label>
<input type="text" value="{$artikel_titel}" name="artikel_titel" />
<input type="submit" value="{$form_submittitel}" />
</form>
TOP;

}


}

?>
