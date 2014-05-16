<script>
<!--
	function check_search_mini_form_post()
	{
		var textSearched = document.getElementById('TxTMiniSearched').value;
		if (textSearched.length > 3)
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
	<div class="module-mini-container">
		<div class="module-mini-top"><h5 class="sub-title">{SEARCH}</h5></div>
		<div class="module-mini-contents">
			<div id="mini-search-form" class="input-element-button">
				<input type="text" id="TxTMiniSearched" name="q" value="{TEXT_SEARCHED}" placeholder="{L_SEARCH}...">
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" name="search_submit"><i class="fa fa-search"></i></button>
			</div>
			<a href="{U_ADVANCED_SEARCH}" class="small">{L_ADVANCED_SEARCH}</a>
		</div>
		<div class="module-mini-bottom"></div>
	</div>
</form>
# ELSE #
<form action="{U_FORM_VALID}" onsubmit="return check_search_mini_form_post();" method="post">
	<div id="mini-search-form" class="input-element-button">
		<input type="text" id="TxTMiniSearched" name="q" value="{TEXT_SEARCHED}" placeholder="{L_SEARCH}...">
		<input type="hidden" name="search_submit" value="{SEARCH}">
		<button type="submit" name="search_submit"><i class="fa fa-search"></i></button>
	</div>
</form>
# ENDIF #