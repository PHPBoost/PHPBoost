<script>
	function check_search_mini_form_post()
	{
		var textSearched = document.getElementById('TxTMiniSearched').value;
		if (textSearched.length >= 3)
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
</script>

# IF C_VERTICAL #
	<form role="search" action="{U_FORM_VALID}" onsubmit="return check_search_mini_form_post();" method="post">
		<fieldset>
			<legend class="sr-only">{L_SEARCH_TITLE</legend>
			<div id="mini-search-form" class="cell-form grouped-inputs">
				<input class="grouped-element" type="search" id="searched-text" name="q" value="{TEXT_SEARCHED}" placeholder="{L_SEARCH_TITLE}..." aria-labelledby="search-button">
				<input type="hidden" id="search-token" name="token" value="{TOKEN}">
				<button id="search-button" class="button submit" type="submit" name="search_submit" aria-label="{L_YOUR_SEARCH}"><i class="fa fa-search" aria-hidden="true"></i></button>
			</div>
		</fieldset>
	</form>
	<div class="cell-body">
		<div class="cell-content align-center"><a href="{U_ADVANCED_SEARCH}" class="button small">{L_ADVANCED_SEARCH}</a></div>
	</div>
# ELSE #
	<div role="search" id="module-mini-search" class="cell-mini# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
		<div class="cell">
			<form role="search" action="{U_FORM_VALID}" onsubmit="return check_search_mini_form_post();" method="post">
				<fieldset>
					<legend class="sr-only">{L_SEARCH_TITLE</legend>
					<div class="cell-form grouped-inputs grouped-auto grouped-right">
						<input class="grouped-element" type="search" id="searched-text" name="q" value="{TEXT_SEARCHED}" placeholder="{L_SEARCH_TITLE}..." aria-labelledby="search-button">
						<input type="hidden" id="search-token" name="token" value="{TOKEN}">
						<button id="search-button" class="button submit" type="submit" name="search_submit" aria-label="{L_YOUR_SEARCH}"><i class="fa fa-search" aria-hidden="true"></i></button>
					</div>					
				</fieldset>
			</form>
		</div>
	</div>
# ENDIF #
