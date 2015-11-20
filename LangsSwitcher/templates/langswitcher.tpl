<div class="langs-switcher# IF C_VERTICAL # langs-switcher-vertical# ENDIF #">
	<form action="{REWRITED_SCRIPT}" method="get">
			<p>
				<select name="switchlang" onchange="document.location = '?switchlang=' + this.options[this.selectedIndex].value;">
				# START langs #
					<option value="{langs.IDNAME}"{langs.SELECTED}>{langs.NAME}</option>
				# END langs #
				</select>
				<img src="{IMG_LANG_IDENTIFIER}" alt="{DEFAULT_LANG}" />
			</p>
			<a href="?switchlang={DEFAULT_LANG}">{@default_lang}</a>
	</form>
</div>
