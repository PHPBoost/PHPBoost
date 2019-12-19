# INCLUDE MSG #
<section id="module-bugtracker">
	<header>
		<h1> {TITLE} </h1>
	</header>
	<div class="content">
		# IF C_DISPLAY_MENU #
			<nav id="cssmenu-bugtrackerpageslist" class="cssmenu cssmenu-group">
				<ul>
					<li# IF C_UNSOLVED # class="current"# ENDIF #>
						<a href="${relative_url(BugtrackerUrlBuilder::unsolved())}" class="cssmenu-title"><i class="fa fa-fw fa-clipboard-list"></i><span>{@titles.unsolved}</span></a>
					</li>
					# IF C_SYNDICATION #
						# IF C_UNSOLVED #
							<li# IF C_UNSOLVED # class="current"# ENDIF #>
								<a class="cssmenu-title cssmenu-title-icon" href="{U_SYNDICATION_UNSOLVED}" aria-label="${LangLoader::get_message('syndication', 'common')}">
									<i class="fa fa-rss warning" aria-hidden="true"></i>
								</a>
							</li>
						# ENDIF #
					# ENDIF #
						<li# IF C_SOLVED # class="current"# ENDIF #>
							<a href="${relative_url(BugtrackerUrlBuilder::solved())}" class="cssmenu-title"><i class="fa fa-fw fa-clipboard-check"></i><span>{@titles.solved}</span></a>
						</li>
					# IF C_SYNDICATION #
						# IF C_SOLVED #
							<li# IF C_SOLVED # class="current"# ENDIF #>
								<a class="cssmenu-title cssmenu-title-icon" href="{U_SYNDICATION_SOLVED}" aria-label="${LangLoader::get_message('syndication', 'common')}">
									<i class="fa fa-rss warning" aria-hidden="true"></i>
								</a>
							</li>
						# ENDIF #
					# ENDIF #
					# IF C_ROADMAP_ENABLED #
						<li# IF C_ROADMAP # class="current"# ENDIF #>
							<a href="${relative_url(BugtrackerUrlBuilder::roadmap())}" class="cssmenu-title"><i class="fa fa-fw fa-road"></i><span>{@titles.roadmap}</span></a>
						</li>
					# ENDIF #
					# IF C_STATS_ENABLED #
						<li# IF C_STATS # class="current"# ENDIF #>
							<a href="${relative_url(BugtrackerUrlBuilder::stats())}" class="cssmenu-title"><i class="fa fa-fw fa-poll-h"></i><span>{@titles.stats}</span></a>
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
