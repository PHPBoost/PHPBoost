# IF C_HORIZONTAL #
<div id="module-mini-langsswitcher" class="cell-mini">
	<div class="cell">
# ENDIF #
		<div class="cell-form# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
			<form action="{REWRITED_SCRIPT}" method="get">
				<div class="# IF C_HORIZONTAL #grouped-inputs grouped-auto grouped-left# ENDIF #">
					<label for="switchlang" class="grouped-element# IF C_VERTICAL # sr-only# ENDIF #">
						<span>{@ls.switch.lang}</span>
					</label>
					<div class="grouped-element">
						<select
							id="switchlang"
							class="flag-selector select-to-list"
							name="switchlang"
							onchange="document.location = '{U_ITEM}' + this.options[this.selectedIndex].value;">
							# START items #
								<option data-option-img="# IF C_HAS_PICTURE #{items.U_ITEM_PICTURE}# ENDIF #" value="{items.ITEM_ID}"# IF items.C_SELECTED # selected="selected"# ENDIF #>{items.ITEM_NAME}</option>
							# END items #
						</select>
					</div>
					# IF C_HORIZONTAL #<a class="grouped-element offload" href="{U_ITEM}{DEFAULT_ITEM}">{@ls.default.lang}</a># ENDIF #
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
			<a class="button small offload" href="{URL}{DEFAULT_LANG}">{@ls.default.lang}</a>
		</div>
	</div>
# ENDIF #
