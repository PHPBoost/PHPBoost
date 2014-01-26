# INCLUDE MSG #
<section>
	<header>
		<h1>
			# IF C_SYNDICATION #<a href="# IF C_UNSOLVED #{U_SYNDICATION_UNSOLVED}# ELSE #{U_SYNDICATION_SOLVED}# ENDIF #" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'main')}"></a># ENDIF #
			{TITLE}
		</h1>
	</header>
	<div class="content">
		# IF C_DISPLAY #
		<menu class="dynamic-menu group center">
			<ul>
				<li# IF C_UNSOLVED # class="current"# ENDIF #>
					<a href="${relative_url(BugtrackerUrlBuilder::unsolved())}" style="display:inline-block;">{@bugs.titles.unsolved}</a> 
				</li>
				<li# IF C_SOLVED # class="current"# ENDIF #>
					<a href="${relative_url(BugtrackerUrlBuilder::solved())}" style="display:inline-block;">{@bugs.titles.solved}</a>
				</li>
				# IF C_ROADMAP_ENABLED #
				<li# IF C_ROADMAP # class="current"# ENDIF #>
					<a href="${relative_url(BugtrackerUrlBuilder::roadmap())}">{@bugs.titles.roadmap}</a>
				</li>
				# ENDIF #
				# IF C_STATS_ENABLED #
				<li# IF C_STATS # class="current"# ENDIF #>
					<a href="${relative_url(BugtrackerUrlBuilder::stats())}">{@bugs.titles.stats}</a>
				</li>
				# ENDIF #
			</ul>
		</menu>
		# ENDIF #
		
		# INCLUDE TEMPLATE #
	</div>
	<footer></footer>
</section>