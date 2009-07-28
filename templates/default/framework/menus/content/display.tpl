# IF C_VERTICAL_BLOCK #
<div class="module_mini_container">
	<div class="module_mini_top">
		# IF C_DISPLAY_TITLE #<h5 class="sub_title">{TITLE}</h5># ENDIF #
	</div>
	<div class="module_mini_contents">
		{CONTENT}
	</div>
	<div class="module_mini_bottom">
	</div>
</div>
# ELSE #
<div class="block_container">
	<div class="block_contents">
		# IF C_DISPLAY_TITLE #<h5 class="sub_title">{TITLE}</h5># ENDIF #
		<div style="text-align:justify; padding-top:5px;">{CONTENT}</div>
		&nbsp;
	</div>
</div>
# ENDIF #
