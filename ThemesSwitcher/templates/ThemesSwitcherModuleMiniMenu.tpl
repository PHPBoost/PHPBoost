# IF C_HORIZONTAL #
<div class="cell-mini">
	<div class="cell">
# ENDIF #
		<form action="{REWRITED_SCRIPT}" method="get" class="cell-form# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
			<div class="# IF C_HORIZONTAL #grouped-inputs grouped-auto grouped-left# ENDIF #">
				<label for="switchtheme"><span class="sr-only">${LangLoader::get_message('select', 'common')}</span></label>
				# IF C_HORIZONTAL #<span class="grouped-element">{@switch.theme}</span># ENDIF #
				<select id="switchtheme" class="grouped-element" name="switchtheme" onchange="document.location = '{U_ITEM}' + this.options[this.selectedIndex].value;">
					# START items #
						<option value="{items.ITEM_ID}"# IF items.C_SELECTED# selected="selected"# ENDIF #>{items.ITEM_NAME}</option>
					# END items #
				</select>
				# IF C_HORIZONTAL #<a class="grouped-element" href="{U_ITEM}{DEFAULT_ITEM}">{@defaut.theme}</a># ENDIF #
			</div>
		</form>
# IF C_HORIZONTAL #
	</div>
</div>
# ENDIF #
# IF C_VERTICAL #
	<div class="cell-body">
		<div class="cell-content align-center"><a class="button small" href="{U_ITEM}{DEFAULT_ITEM}">{@defaut.theme}</a></div>
	</div>
# ENDIF #
