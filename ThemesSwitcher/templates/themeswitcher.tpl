<div class="themes-switcher# IF C_VERTICAL # themes-switcher-vertical# ENDIF #">
	<form action="{REWRITED_SCRIPT}" method="get">
		<select name="switchtheme" onchange="document.location = '?switchtheme=' + this.options[this.selectedIndex].value;">
		# START themes #
			<option value="{themes.IDNAME}"{themes.SELECTED}>{themes.NAME}</option>
		# END themes #
		</select>
		<a href="?switchtheme={DEFAULT_THEME}">{@defaut_theme}</a>
	</form>
</div>
