<div role="search" id="module-mini-search"# IF C_HORIZONTAL # class="float-right# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #"# ENDIF #>
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

		jQuery(document).ready(function() {
			jQuery('#search-token').val(TOKEN);
		});
	-->
	</script>

	<form role="search" action="{U_FORM_VALID}" onsubmit="return check_search_mini_form_post();" method="post">
		<div id="mini-search-form" class="input-element-button">
			<input type="search" id="TxTMiniSearched" name="q" value="{TEXT_SEARCHED}" placeholder="{L_SEARCH_TITLE}..." aria-labelledby="SearchButton">
			<input type="hidden" id="search-token" name="token" value="{TOKEN}">
			<button id="SearchButton" class="submit" type="submit" name="search_submit" aria-label="{L_YOUR_SEARCH}"><i class="fa fa-search" aria-hidden="true"></i><span class="sr-only">{L_SEARCH}</span></button>
		</div>
		# IF C_VERTICAL #<a href="{U_ADVANCED_SEARCH}" class="small">{L_ADVANCED_SEARCH}</a># ENDIF #
	</form>
</div>
