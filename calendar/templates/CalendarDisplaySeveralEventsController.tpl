# INCLUDE MSG #

<section id="module-calendar">
	<header>
		<div class="align-right controls">
			<a href="${relative_url(SyndicationUrlBuilder::rss('calendar'))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
		</div>
		<h1>
			# IF C_PENDING_PAGE #
				{@calendar.pending.events}
			# ELSE #
				# IF C_MEMBER_ITEMS #
					{@my.items}
				# ELSE #
					{@module.title}
				# ENDIF #
			# ENDIF #
		</h1>
	</header>
	<div class="content">
		<div id="calendar">
			# INCLUDE CALENDAR #
		</div>

		<div id="events">
			# INCLUDE EVENTS #
		</div>
	</div>

	<footer></footer>
</section>
