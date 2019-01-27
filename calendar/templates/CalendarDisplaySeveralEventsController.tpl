# INCLUDE MSG #

<section id="module-calendar">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('calendar'))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication" aria-hidden="true" title="${LangLoader::get_message('syndication', 'common')}"></i></a>
			{@module_title}
		</h1>
	</header>

	<div class="elements-container">
		# IF NOT C_PENDING_PAGE #
			<div id="calendar">
				# INCLUDE CALENDAR #
			</div>

			<div class="spacer"></div>
		# ENDIF #

		<div id="events">
			# INCLUDE EVENTS #
		</div>
	</div>

	<footer></footer>
</section>
