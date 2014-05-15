<script>
<!--
	function ${escapejscharacters(NAME)}select_all()
	{
		var select = $(${escapejs(HTML_ID)});
		for(i = 0; i < select.length; i++)
		{	
			if (select[i])
				select[i].selected = true;
		}
	}
	function ${escapejscharacters(NAME)}unselect_all()
	{
		var select = $(${escapejs(HTML_ID)});
		for(i = 0; i < select.length; i++)
		{	
			if (select[i])
				select[i].selected = false;
		}
	}
-->		
</script>
<select multiple="multiple" name="${escape(NAME)}[]" id="${escape(HTML_ID)}" size="{SIZE}" class="${escape(CSS_CLASS)}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_HIDDEN # style="display:none;" # ENDIF #>
	# START options # # INCLUDE options.OPTION # # END options # 
</select>
<br />
<a href="javascript:${escapejscharacters(NAME)}select_all()" class="small">{L_SELECT_ALL}</a> / <a href="javascript:${escapejscharacters(NAME)}unselect_all()" class="small">{L_UNSELECT_ALL}</a>
<br />
<span class="smaller">{L_SELECT_EXPLAIN}</span>