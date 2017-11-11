<div class="langs-switcher">
	<form action="{REWRITED_SCRIPT}" method="get">
		<div class="selected-lang# IF C_VERTICAL # langs-switcher-vertical# ENDIF #">
			<label for="switchlang">
				<select id="switchlang" title="{@switch_lang}" name="switchlang" onchange="document.location = '?switchlang=' + this.options[this.selectedIndex].value;">
				# START langs #
					<option value="{langs.IDNAME}"# IF langs.C_SELECTED # selected="selected"# ENDIF #>{langs.NAME}</option>
				# END langs #
				</select>
			</label>
			<img src="{PATH_TO_ROOT}/images/stats/countries/{LANG_IDENTIFIER}.png" alt="{LANG_NAME}" />
		</div>

		<a href="?switchlang={DEFAULT_LANG}">{@default_lang}</a>
	</form>
</div>
