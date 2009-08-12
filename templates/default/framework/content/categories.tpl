# IF C_NO_CATEGORY #
<div class="notice">
	{L_NO_EXISTING_CATEGORY}
</div>
# ENDIF #

# IF C_AJAX_MODE #
<script type="text/javascript">
<!--

// Moving a category with AJAX technology
function ajax_move_cat(id, direction)
{
	direction = (direction == 'up' ? 'up' : 'down');
	var xhr_object = xmlhttprequest_init('{CONFIG_XMLHTTPREQUEST_FILE}?token={TOKEN}&id_' + direction + '=' + id);
	
	document.getElementById('l' + id).innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
	
	xhr_object.onreadystatechange = function() 
	{
		//Transfert finished and successful
		if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
			document.getElementById("cat_administration").innerHTML = xhr_object.responseText;
		else if(  xhr_object.readyState == 4 && xhr_object.responseText == '' ) //Error
		{
			document.getElementById('l' + id).innerHTML = "";
			alert("{L_COULD_NOT_BE_MOVED}");
		}
	}
	xmlhttprequest_sender(xhr_object, null);
}

// Showing/Hiding a category with AJAX technology
function ajax_change_cat_visibility(id, status)
{
	status = (status == 'show' ? 'show' : 'hide');
	var xhr_object = xmlhttprequest_init('{CONFIG_XMLHTTPREQUEST_FILE}?token={TOKEN}&' + status + '=' + id);
	
	document.getElementById('l' + id).innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
	
	xhr_object.onreadystatechange = function() 
	{
		//Transfert finished and successful
		if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
			document.getElementById("cat_administration").innerHTML = xhr_object.responseText;
		else if(  xhr_object.readyState == 4 && xhr_object.responseText == '' ) //Error
		{
			document.getElementById('l' + id).innerHTML = "";
			alert("{L_VISIBILITY_COULD_NOT_BE_CHANGED}");
		}
	}
	xmlhttprequest_sender(xhr_object, null);
}
-->
</script>
<div id="cat_administration">
# ENDIF #

{NESTED_CATEGORIES}

# IF C_AJAX_MODE #
</div>
# ENDIF #