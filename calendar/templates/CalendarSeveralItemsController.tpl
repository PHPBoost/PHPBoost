# INCLUDE MESSAGE_HELPER #

<section id="module-calendar">
	<header class="section-header">
		<div class="controls align-right">
			<a href="${relative_url(SyndicationUrlBuilder::rss('calendar'))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
		</div>
		<h1>
			# IF C_PENDING_ITEMS #
				{@calendar.pending.events}
			# ELSE #
				# IF C_MEMBER_ITEMS #
					# IF C_MY_ITEMS #{@my.items}# ELSE #{@member.items} : {MEMBER_NAME}# ENDIF #
				# ELSE #
					{@module.title}
				# ENDIF #
			# ENDIF #
		</h1>
	</header>
	# IF NOT C_PENDING_ITEMS #
		# IF NOT C_MEMBER_ITEMS #
			<div id="calendar" class="sub-section">
				<div class="content">
					# INCLUDE CALENDAR #
				</div>
			</div>
		# ENDIF #
	# ENDIF #
	<div id="events" class="sub-section">
		# INCLUDE EVENTS #
	</div>
	<footer></footer>
</section>
