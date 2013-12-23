# IF C_VERTICAL_BLOCK #
<div class="module-mini-container">
	<div class="module-mini-top">
		<h5 class="sub-title">
			<a href="{U_LINK}" class="fa fa-syndication"></a>
			# IF C_NAME #{NAME}# ELSE #{TITLE}# ENDIF #
		</h5>
	</div>
	<div class="module-mini-contents">
		<ul class="list">
			# START item #
			<li><span class="smaller">{item.DATE}</span> <a href="{item.U_LINK} ">{item.TITLE} </a></li>
			# END item #
		</ul>
	</div>
	<div class="module-mini-bottom">
	</div>
</div>
# ELSE #
<div class="block_container">
	<div class="block_contents">
		<h5 class="sub-title">
			<a href="{U_LINK}" class="fa fa-syndication"></a>
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
