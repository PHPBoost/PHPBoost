<script type="text/javascript">
<!--
	function check_search_mini_form_post()
	{
		var textSearched = document.getElementById('TxTMiniSearched').value;
		if ( (textSearched.length > 3) && (textSearched != escape('{L_SEARCH}...')) )
		{
			textSearched = escape_xmlhttprequest(textSearched);
			return true;
		}
		else
		{
			alert('{WARNING_LENGTH_STRING_SEARCH}');
			return false;
		}
	}
-->
</script>
 
# IF C_VERTICAL #
<form action="{U_FORM_VALID}" onsubmit="return check_search_mini_form_post();" method="post">
	<div class="module_mini_container">
		<div class="module_mini_top"><h5 class="sub_title">{SEARCH}</h5></div>
		<div class="module_mini_contents">
			<label><input type="text" id="TxTMiniSearched" name="q" value="{TEXT_SEARCHED}" class="mini_search_vert" onclick="if(this.value=='{L_SEARCH}...')this.value='';" onblur="if(this.value=='')this.value='{L_SEARCH}...';" /></label>
			<br /><br />
			<input type="hidden" name="token" value="{TOKEN}" />
			<input type="submit" id="search_mini_submit" name="search_submit"  value="{SEARCH}" class="submit" /><br />
			<a href="{U_ADVANCED_SEARCH}" class="small_link">{L_ADVANCED_SEARCH}</a>
		</div>
		<div class="module_mini_bottom"></div>
	</div>
</form>
# ELSE #
<form action="{U_FORM_VALID}" onsubmit="return check_search_mini_form_post();" method="post">
	<div id="search_form">
		<input type="text" id="TxTMiniSearched" name="q" value="{TEXT_SEARCHED}" class="search_entry" onclick="if(this.value=='{L_SEARCH}...')this.value='';" onblur="if(this.value=='')this.value='{L_SEARCH}...';" />
		<input type="hidden" name="search_submit" id="search_submit_mini" value="{SEARCH}" class="submit" />
		<input type="submit" name="search_submit" class="search_submit" value=" " />
	</div>
</form>
# ENDIF #