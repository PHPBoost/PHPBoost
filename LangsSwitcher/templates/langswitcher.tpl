# IF C_HORIZONTAL #
<div class="cell-mini">
	<div class="cell">
# ENDIF #
		<div class="cell-form# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
			<form action="{REWRITED_SCRIPT}" method="get">
				<div class="# IF C_HORIZONTAL #grouped-inputs grouped-auto grouped-left# ELSE #cell-input# ENDIF #">
					# IF C_HORIZONTAL #<span class="grouped-element">{@switch.lang}</span># ENDIF #
					<div class="grouped-element">
						<label for="switchlang"><span class="sr-only">${LangLoader::get_message('select', 'common')}</span></label>
						<select
							id="switchlang"
							class="flag-selector select-to-list"
							name="switchlang"
							onchange="document.location = '{URL}' + this.options[this.selectedIndex].value;">
							# START langs #
								<option data-option-img="# IF C_HAS_PICTURE #{langs.LANG_PICTURE_URL}# ENDIF #" value="{langs.IDNAME}"# IF langs.C_SELECTED # selected="selected"# ENDIF #>{langs.NAME}</option>
							# END langs #
						</select>
					</div>

					# IF C_HORIZONTAL #<a class="grouped-element" href="{URL}{DEFAULT_LANG}">{@default.lang}</a># ENDIF #
				</div>
			</form>
		</div>
# IF C_HORIZONTAL #
	</div>
</div>
# ENDIF #

# IF C_VERTICAL #
	<div class="cell-body">
		<div class="cell-content align-center">
			<a class="button small" href="{URL}{DEFAULT_LANG}">{@default.lang}</a>
		</div>
	</div>
# ENDIF #
