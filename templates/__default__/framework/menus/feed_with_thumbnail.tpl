<div id="feed-menu-{ID}" class="cell-tile cell-mini# IF C_VERTICAL_BLOCK # cell-mini-vertical# ENDIF ## IF C_HIDDEN_WITH_SMALL_SCREENS # hidden-small-screens# ENDIF #">
	<div class="cell">
		<div class="cell-header">
			<h6 class="cell-name"># IF C_NAME #{NAME}# ELSE #{RAW_TITLE}# ENDIF #</h6>
			<a class="offload" href="{U_LINK}" aria-label="${LangLoader::get_message('common.syndication', 'common-lang')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
		</div>
		<div class="cell-list">
			<ul>
				# START item #
					<li><span class="pinned question smaller">{item.DATE}</span> # IF item.C_IMG #<img src="{item.U_IMG}" alt="{item.RAW_TITLE}"/># ENDIF #<a class="offload" href="{item.U_LINK}">{item.RAW_TITLE}</a></li>
				# END item #
			</ul>
		</div>
	</div>
</div>
