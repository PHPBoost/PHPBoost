# IF C_VERTICAL_BLOCK #
<div class="module-mini-container# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
	<div class="module-mini-top">
		<div class="sub-title">
			<a href="{U_LINK}" class="fa fa-syndication"></a>
			# IF C_NAME #{NAME}# ELSE #{RAW_TITLE}# ENDIF #
		</div>
	</div>
	<div class="module-mini-contents">
		<ul class="feed-list">
			# START item #
			<li><span class="smaller">{item.DATE}</span> <a href="{item.U_LINK} ">{item.RAW_TITLE} </a></li>
			# END item #
		</ul>
	</div>
	<div class="module-mini-bottom">
	</div>
</div>
# ELSE #
<div class="block-container# IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
	<div class="block-contents">
		<div class="sub-title">
			<a href="{U_LINK}" class="fa fa-syndication"></a>
			# IF C_NAME #{NAME}# ELSE #{RAW_TITLE}# ENDIF #
		</div>
		<ul class="feed-list">
			# START item #
			<li><span class="smaller">{item.DATE}</span> <a href="{item.U_LINK} ">{item.RAW_TITLE}</a></li>
			# END item #
		</ul>
	</div>
</div>
# ENDIF #
