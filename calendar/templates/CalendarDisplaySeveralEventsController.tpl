# INCLUDE MSG #

<section id="module-calendar">
	<header>
		<div class="align-right controls">
			<a href="${relative_url(SyndicationUrlBuilder::rss('calendar'))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
		</div>
		<h1>
			{@calendar.module.title}
		</h1>
	</header>
	<div class="content">
		# IF NOT C_PENDING_PAGE #
			<div id="calendar">
				# INCLUDE CALENDAR #
			</div>
		# ENDIF #

		<div id="events">
			# INCLUDE EVENTS #
		</div>
	</div>

	<footer></footer>
</section>
