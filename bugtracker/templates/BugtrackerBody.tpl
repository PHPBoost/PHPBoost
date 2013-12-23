# INCLUDE MSG #
<section>
	<header>
		<h1>{@bugs.module_title} - {TITLE}
		</h1>
	</header>
	<div class="content">
		<menu class="dynamic-menu group center">
			<ul>
				<li class="{CLASS_BUG_UNSOLVED}">
					<a href="{LINK_BUG_UNSOLVED}">{@bugs.titles.unsolved}</a> 
				</li>
				<li class="{CLASS_BUG_SOLVED}">
					<a href="{LINK_BUG_SOLVED}">{@bugs.titles.solved}</a>
				</li>
				# IF C_ROADMAP_ACTIVATED #
				<li class="{CLASS_BUG_ROADMAP}">
					<a href="{LINK_BUG_ROADMAP}">{@bugs.titles.roadmap}</a>
				</li>
				# ENDIF #
				# IF C_STATS_ACTIVATED #
				<li class="{CLASS_BUG_STATS}">
					<a href="{LINK_BUG_STATS}">{@bugs.titles.stats}</a>
				</li>
				# ENDIF #
				# IF C_EDIT_PAGE #
				<li class="current">
					<a href="{LINK_BUG_EDIT}">{@bugs.titles.edit} \#{BUG_ID}</a>
				</li>
				# ENDIF #
				# IF C_DETAIL_PAGE #
				<li class="current">
					<a href="{LINK_BUG_DETAIL}">{@bugs.titles.detail} \#{BUG_ID}</a>
				</li>
				# ENDIF #
				# IF C_HISTORY_PAGE #
				<li class="current">
					<a href="{LINK_BUG_HISTORY}">{@bugs.titles.history} \#{BUG_ID}</a>
				</li>
				# ENDIF #
				# IF C_ADD_PAGE #
				<li class="current">
					<a href="{LINK_BUG_ADD}"><i class="fa fa-plus"></i> {@bugs.titles.add}</a>
				</li>
				# ENDIF #
			</ul>
		</menu>
		
		# INCLUDE TEMPLATE #
	</div>
	<footer></footer>
</section>