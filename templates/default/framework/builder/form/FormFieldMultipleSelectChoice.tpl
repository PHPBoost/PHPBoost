<script type="text/javascript">
<!--
	function ${escape(NAME)}select_all()
	{
		var select = $(${escapejs(ID)});
		for(i = 0; i < select.length; i++)
		{	
			if (select[i])
				select[i].selected = true;
		}
	}
	function ${escape(NAME)}unselect_all()
	{
		var select = $(${escapejs(ID)});
		for(i = 0; i < select.length; i++)
		{	
			if (select[i])
				select[i].selected = false;
		}
	}
-->		
</script>
<select multiple="multiple" name="${escape(NAME)}[]" id="${escape(ID)}" class="${escape(CSS_CLASS)}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_HIDDEN # style="display:none;" # ENDIF #>
	# START options # # INCLUDE options.OPTION # # END options # 
</select>
<br />
<a href="javascript:${escape(NAME)}select_all()" class="small_link">{L_SELECT_ALL}</a> / <a href="javascript:${escape(NAME)}unselect_all()" class="small_link">{L_UNSELECT_ALL}</a>
<br />
<span class="text_small">{L_SELECT_EXPLAIN}</span>