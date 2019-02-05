<div class="langs-switcher# IF C_HORIZONTAL ## IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF ## ENDIF #">
	<form action="{REWRITED_SCRIPT}" method="get">
		<div class="selected-lang# IF C_VERTICAL # langs-switcher-vertical# ENDIF #">
			<label for="switchlang">
				<select id="switchlang" title="{@switch_lang}" name="switchlang" onchange="document.location = '?switchlang=' + this.options[this.selectedIndex].value;">
				# START langs #
					<option value="{langs.IDNAME}"# IF langs.C_SELECTED # selected="selected"# ENDIF #>{langs.NAME}</option>
				# END langs #
				</select>
			</label>
			# IF C_HAS_PICTURE #<img src="{LANG_PICTURE_URL}" alt="{LANG_NAME}" title="{LANG_NAME}" class="valign-middle" /># ENDIF #
		</div>

		<a href="?switchlang={DEFAULT_LANG}">{@default_lang}</a>
	</form>
</div>
