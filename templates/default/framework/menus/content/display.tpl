# IF C_VERTICAL_BLOCK #
<div class="module_mini_container">
	<div class="module_mini_top">
		<h5 class="sub_title">
			# IF C_DISPLAY_TITLE #<h5 class="sub_title">{TITLE}</h5># ENDIF #
		</h5>
	</div>
	<div class="module_mini_contents">
		{CONTENT}
	</div>
	<div class="module_mini_bottom">
	</div>
</div>
# ELSE #
<div class="block_content">
	# IF C_DISPLAY_TITLE #<h5 class="sub_title">{TITLE}</h5># ENDIF #
    <div style="text-align:justify; padding-top:5px;">{CONTENT}</div>
	&nbsp;
</div>
# ENDIF #
