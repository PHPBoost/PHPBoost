<div id="feed-menu-{ID}" class=" cell-tile cell-mini# IF C_VERTICAL_BLOCK # cell-mini-vertical# ENDIF ## IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
	<div class="cell">
		<div class="cell-header">
			<h6 class="cell-name"># IF C_NAME #{NAME}# ELSE #{RAW_TITLE}# ENDIF #</h6>
			<a href="{U_LINK}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
		</div>
		<div class="cell-list">
			<ul>
				# START item #
					<li><span class="pinned question smaller">{item.DATE}</span> <a href="{item.U_LINK} ">{item.RAW_TITLE}</a></li>
				# END item #
			</ul>
		</div>
	</div>
</div>
