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
	url = '{CONFIG_XMLHTTPREQUEST_FILE}?token={TOKEN}&id_' + direction + '=' + id;
	
	document.getElementById('l' + id).innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
	
	new Ajax.Request(url,
		{
			method:'get',
			onSuccess: function(transport)
			{
				var response = transport.responseText || "no response text";
				document.getElementById("cat_administration").innerHTML = response;
			},
			onFailure: function()
			{
				document.getElementById('l' + id).innerHTML = "";
				alert("{L_COULD_NOT_BE_MOVED}");
			}
		});

}

// Showing/Hiding a category with AJAX technology
function ajax_change_cat_visibility(id, status)
{
	status = (status == 'show' ? 'show' : 'hide');
	url = '{CONFIG_XMLHTTPREQUEST_FILE}?token={TOKEN}&' + status + '=' + id;
	
	document.getElementById('l' + id).innerHTML = '<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';

	new Ajax.Request(url,
		{
			method:'get',
			onSuccess: function(transport)
			{
				var response = transport.responseText || "no response text";
				document.getElementById("cat_administration").innerHTML = response;
			},
			onFailure: function()
			{
				document.getElementById('l' + id).innerHTML = "";
				alert("{L_VISIBILITY_COULD_NOT_BE_CHANGED}");
			}
		});
}
-->
</script>
<div id="cat_administration">
# ENDIF #

{NESTED_CATEGORIES}

# IF C_AJAX_MODE #
</div>
# ENDIF #