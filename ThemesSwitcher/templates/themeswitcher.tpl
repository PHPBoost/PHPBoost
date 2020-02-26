# IF C_HORIZONTAL #
<div class="cell-mini">
	<div class="cell">
# ENDIF #
		<div class="cell-form# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
			<form action="{REWRITED_SCRIPT}" method="get">
				<div class="# IF C_HORIZONTAL #grouped-inputs grouped-auto grouped-left# ELSE #cell-input# ENDIF #">
					<label for="switchtheme"><span class="sr-only">${LangLoader::get_message('select', 'common')}</span></label>
					# IF C_HORIZONTAL #<span class="grouped-element">{@switch.theme}</span># ENDIF #
					<select id="switchtheme" name="switchtheme" onchange="document.location = '{URL}' + this.options[this.selectedIndex].value;">
					# START themes #
						<option value="{themes.IDNAME}"# IF themes.C_SELECTED# selected="selected"# ENDIF #>{themes.NAME}</option>
					# END themes #
					</select>
					# IF C_HORIZONTAL #<a class="grouped-element" href="{URL}{DEFAULT_THEME}">{@defaut.theme}</a># ENDIF #
				</div>
			</form>
		</div>
# IF C_HORIZONTAL #
	</div>
</div>
# ENDIF #
# IF C_VERTICAL #
	<div class="cell-body">
		<div class="cell-content align-center"><a class="button small" href="{URL}{DEFAULT_THEME}">{@defaut.theme}</a></div>
	</div>
# ENDIF #
