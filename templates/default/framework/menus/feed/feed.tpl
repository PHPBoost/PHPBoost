# IF C_VERTICAL_BLOCK #
<div class="module_mini_container">
	<div class="module_mini_top">
		<h5 class="sub-title">
			<a href="{U_LINK}" class="icon-syndication"></a>
			# IF C_NAME #{NAME}# ELSE #{TITLE}# ENDIF #
		</h5>
	</div>
	<div class="module_mini_contents">
		<ul class="list">
			# START item #
			<li><span class="smaller">{item.DATE}</span> <a href="{item.U_LINK} ">{item.TITLE} </a></li>
			# END item #
		</ul>
	</div>
	<div class="module_mini_bottom">
	</div>
</div>
# ELSE #
<div class="block_container">
	<div class="block_contents">
		<h5 class="sub-title">
			<a href="{U_LINK}" class="icon-syndication"></a>
			# IF C_NAME #{NAME}# ELSE #{TITLE}# ENDIF #
		</h5>
		<ul class="list" style="margin-top:8px;">
			# START item #
			<li><span class="smaller">{item.DATE}</span> <a href="{item.U_LINK} ">{item.TITLE} </a></li>
			# END item #
		</ul>
		&nbsp;
	</div>
</div>
# ENDIF #
