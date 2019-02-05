<div class="themes-switcher# IF C_VERTICAL # themes-switcher-vertical# ELSE ## IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF ## ENDIF #">
	<form action="{REWRITED_SCRIPT}" method="get">
		<label for="switchtheme">
			<select id="switchtheme" title="{@switch_theme}" name="switchtheme" onchange="document.location = '?switchtheme=' + this.options[this.selectedIndex].value;">
			# START themes #
				<option value="{themes.IDNAME}"# IF themes.C_SELECTED# selected="selected"# ENDIF #>{themes.NAME}</option>
			# END themes #
			</select>
		</label>
		<a href="?switchtheme={DEFAULT_THEME}">{@defaut_theme}</a>
	</form>
</div>
