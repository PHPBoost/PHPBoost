# INCLUDE MSG #
<section>
	<header>
		<h1>{@bugs.module_title} - {TITLE}
		</h1>
	</header>
	<div class="content">
		# IF NOT C_HIDE #
		<menu class="dynamic-menu group center">
			<ul>
				<li class="{CLASS_BUG_UNSOLVED}">
					<a href="{LINK_SYNDICATION_UNSOLVED}" title="${LangLoader::get_message('syndication', 'main')}" class="fa fa-syndication" style="display:inline-block;padding:0 0 3px 12px;"></a> <a href="{LINK_BUG_UNSOLVED}" style="display:inline-block;">{@bugs.titles.unsolved}</a> 
				</li>
				<li class="{CLASS_BUG_SOLVED}">
					<a href="{LINK_SYNDICATION_SOLVED}" title="${LangLoader::get_message('syndication', 'main')}" class="fa fa-syndication" style="display:inline-block;padding:0 0 3px 12px;"></a> <a href="{LINK_BUG_SOLVED}" style="display:inline-block;">{@bugs.titles.solved}</a>
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
			</ul>
		</menu>
		# ENDIF #
		
		# INCLUDE TEMPLATE #
	</div>
	<footer></footer>
</section>