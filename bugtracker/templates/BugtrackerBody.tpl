# INCLUDE MSG #
<div class="module_position">
	<div class="module_top_l"></div>
	<div class="module_top_r"></div>
	<div class="module_top">
		<ul>
			<li class="{CLASS_BUG_UNSOLVED}">
				<a href="{LINK_BUG_UNSOLVED}">{@bugs.titles.unsolved_bugs}</a> 
			</li>
			<li class="{CLASS_BUG_SOLVED}">
				<a href="{LINK_BUG_SOLVED}">{@bugs.titles.solved_bugs}</a>
			</li>
			# IF C_ROADMAP_ACTIVATED #
			<li class="{CLASS_BUG_ROADMAP}">
				<a href="{LINK_BUG_ROADMAP}">{@bugs.titles.roadmap}</a>
			</li>
			# ENDIF #
			# IF C_STATS_ACTIVATED #
			<li class="{CLASS_BUG_STATS}">
				<a href="{LINK_BUG_STATS}">{@bugs.titles.bugs_stats}</a>
			</li>
			# ENDIF #
			# IF C_ADD_PAGE #
			<li class="bt_current">
				<a href="{LINK_BUG_ADD}">{@bugs.titles.add_bug}</a>
			</li>
			# ENDIF #
			# IF C_EDIT_PAGE #
			<li class="bt_current">
				<a href="{LINK_BUG_EDIT}">{@bugs.titles.edit_bug} \#{BUG_ID}</a>
			</li>
			# ENDIF #
			# IF C_DETAIL_PAGE #
			<li class="bt_current">
				<a href="{LINK_BUG_DETAIL}">{@bugs.titles.view_bug} \#{BUG_ID}</a>
			</li>
			# ENDIF #
			# IF C_HISTORY_PAGE #
			<li class="bt_current">
				<a href="{LINK_BUG_HISTORY}">{@bugs.titles.history_bug} \#{BUG_ID}</a>
			</li>
			# ENDIF #
			# IF C_ADD #
			<li class="bt_add">
				<a href="{LINK_BUG_ADD}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" alt="{@bugs.titles.add_bug}" title="{@bugs.titles.add_bug}" class="valign_middle" /></a>
			</li>
			# ENDIF #
		</ul>
	</div>
	<div class="module_contents">
		# INCLUDE TEMPLATE #
	</div>
	<div class="module_bottom_l"></div>
	<div class="module_bottom_r"></div>
	<div class="module_bottom"></div>
</div>