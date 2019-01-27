# INCLUDE MSG #
<section id="module-bugtracker">
	<header>
		<div class="cat-actions">
			# IF C_SYNDICATION #<a href="# IF C_UNSOLVED #{U_SYNDICATION_UNSOLVED}# ELSE #{U_SYNDICATION_SOLVED}# ENDIF #" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa-pbt fa-syndication" aria-hidden="true" title="${LangLoader::get_message('syndication', 'common')}"></i></a># ENDIF #
		</div>
		<h1>
			{TITLE}
		</h1>
	</header>
	<div class="content">
		# IF C_DISPLAY_MENU #
			<nav id="cssmenu-bugtrackerpageslist" class="cssmenu cssmenu-group">
				<ul>
					<li# IF C_UNSOLVED # class="current"# ENDIF #>
						<a href="${relative_url(BugtrackerUrlBuilder::unsolved())}" class="cssmenu-title">{@titles.unsolved}</a>
					</li>
					<li# IF C_SOLVED # class="current"# ENDIF #>
						<a href="${relative_url(BugtrackerUrlBuilder::solved())}" class="cssmenu-title">{@titles.solved}</a>
					</li>
					# IF C_ROADMAP_ENABLED #
					<li# IF C_ROADMAP # class="current"# ENDIF #>
						<a href="${relative_url(BugtrackerUrlBuilder::roadmap())}" class="cssmenu-title">{@titles.roadmap}</a>
					</li>
					# ENDIF #
					# IF C_STATS_ENABLED #
					<li# IF C_STATS # class="current"# ENDIF #>
						<a href="${relative_url(BugtrackerUrlBuilder::stats())}" class="cssmenu-title">{@titles.stats}</a>
					</li>
					# ENDIF #
				</ul>
			</nav>
			<script>
				jQuery("#cssmenu-bugtrackerpageslist").menumaker({
					title: "${LangLoader::get_message('form.options', 'common')}",
					format: "multitoggle",
					breakpoint: 768
				});
			</script>
		# ENDIF #

		# INCLUDE TEMPLATE #
	</div>
	<footer></footer>
</section>
