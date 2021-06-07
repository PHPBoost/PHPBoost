<script>
	function ${escapejscharacters(NAME)}_select_all()
	{
		var select = jQuery('#' + ${escapejs(HTML_ID)})[0];
		for(i = 0; i < select.length; i++)
		{
			if (select[i])
				select[i].selected = true;
		}
	}
	function ${escapejscharacters(NAME)}_unselect_all()
	{
		var select = jQuery('#' + ${escapejs(HTML_ID)})[0];
		for(i = 0; i < select.length; i++)
		{
			if (select[i])
				select[i].selected = false;
		}
	}

	jQuery(document).ready(function() {
		jQuery("#${escapejscharacters(NAME)}_select_all").on('click',function() {
			${escapejscharacters(NAME)}_select_all();
			# IF C_REQUIRED #
				HTMLForms.get("${escape(FORM_ID)}").getField("${escape(ID)}").enableValidationMessage();
				HTMLForms.get("${escape(FORM_ID)}").getField("${escape(ID)}").liveValidate();
			# ENDIF #
		});
		jQuery("#${escapejscharacters(NAME)}_unselect_all").on('click',function() {
			${escapejscharacters(NAME)}_unselect_all();
			# IF C_REQUIRED #
				HTMLForms.get("${escape(FORM_ID)}").getField("${escape(ID)}").enableValidationMessage();
				HTMLForms.get("${escape(FORM_ID)}").getField("${escape(ID)}").liveValidate();
			# ENDIF #
		});
	});
</script>
<div class="multiple-selector">
	<select multiple="multiple" name="${escape(NAME)}[]" id="${escape(HTML_ID)}" size="{SIZE}" class="# IF C_MULTIPLE_SELECT_TO_LIST #multiple-select-to-list # ENDIF #${escape(CSS_CLASS)}"# IF C_DISABLED # disabled="disabled"# ENDIF ## IF C_HIDDEN # style="display: none;"# ENDIF #>
		# START options # # INCLUDE options.OPTION # # END options #
	</select>
	<div class="spacer"></div>
	<a href="#" id="${escapejscharacters(NAME)}_select_all" onclick="return false;" class="small select-all">{@form.select.all}</a> /
	<a href="#" id="${escapejscharacters(NAME)}_unselect_all" onclick="return false;" class="small deselect-all">{@form.select.none}</a>
	<span class="field-description general-selector">{@form.select.multiple.clue}</span>
</div>
