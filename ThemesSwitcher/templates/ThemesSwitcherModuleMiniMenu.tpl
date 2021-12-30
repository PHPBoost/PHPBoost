# IF C_HORIZONTAL #
<div id="module-mini-themesswitcher" class="cell-mini">
	<div class="cell">
# ENDIF #
		<form action="{REWRITED_SCRIPT}" method="get" class="cell-form# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
			<div class="# IF C_HORIZONTAL #grouped-inputs grouped-auto grouped-left# ELSE #cell-input# ENDIF #">
				<label for="switchtheme" class="grouped-element">
					<span class="sr-only">{@common.select}</span>
					# IF C_HORIZONTAL #<span>{@ts.switch.theme}</span># ENDIF #
				</label>
				<select id="switchtheme" class="grouped-element" name="switchtheme" onchange="document.location = '{U_ITEM}' + this.options[this.selectedIndex].value;">
					# START items #
						<option value="{items.ITEM_ID}"# IF items.C_SELECTED# selected="selected"# ENDIF #>{items.ITEM_NAME}</option>
					# END items #
				</select>
				# IF C_HORIZONTAL #<a class="grouped-element offload" href="{U_ITEM}{DEFAULT_ITEM}">{@ts.default.theme}</a># ENDIF #
			</div>
		</form>
# IF C_HORIZONTAL #
	</div>
</div>
# ENDIF #
# IF C_VERTICAL #
	<div class="cell-body">
		<div class="cell-content align-center"><a class="button small offload" href="{U_ITEM}{DEFAULT_ITEM}">{@ts.default.theme}</a></div>
	</div>
# ENDIF #
