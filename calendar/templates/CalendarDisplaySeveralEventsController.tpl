# INCLUDE MSG #

<menu class="dynamic_menu right">
	<ul>
		<li><a><i class="icon-cog"></i></a>
			<ul>
				# IF C_ADD #
				<li>
					<a href="${relative_url(CalendarUrlBuilder::add_event())}" title="{@calendar.titles.add_event}">{@calendar.titles.add_event}</a>
				</li>
				# ENDIF #
				# IF C_PENDING_EVENTS #
				<li>
					<a href="${relative_url(CalendarUrlBuilder::display_pending_events())}" title="{@calendar.pending}">{@calendar.pending}</a>
				</li>
				# ENDIF #
			</ul>
		</li>
	</ul>
</menu>

<section>
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('calendar'))}" class="syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
			{@module_title}
		</h1>
	</header>
		
	<div class="content">
		<div id="calendar">
			# INCLUDE CALENDAR #
		</div>
	</div>
	
	<div class="spacer">&nbsp;</div>
	
	<div id="events" class="event_container">
		<div class="event_top_title">
			<strong>{L_EVENTS}</strong>
		</div>
		<div class="event_date">{DATE}</div>
		
		# IF C_EVENT #
			# START event #
			<article itemscope="itemscope" itemtype="http://schema.org/Event">
				<header>
					<h1>
						<a href="{event.U_SYNDICATION}" class="syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
						<a href="{event.U_LINK}"><span id="name" itemprop="name">{event.TITLE}</span></a>
						
						<span class="tools">
							# IF C_COMMENTS_ENABLED #<a href="{event.U_COMMENTS}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/com_mini.png" alt="" class="valign_middle" /> {event.L_COMMENTS}</a># ENDIF #
							# IF event.C_EDIT #
								<a href="{event.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="edit"></a>
							# ENDIF #
							# IF event.C_DELETE #
								<a href="{event.U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="delete"></a>
							# ENDIF #
						</span>
					</h1>
					
					<meta itemprop="url" content="{event.U_LINK}">
					<div itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						<meta itemprop="about" content="{event.CATEGORY_NAME}">
						<meta itemprop="discussionUrl" content="{event.U_COMMENTS}">
						<meta itemprop="interactionCount" content="{event.NUMBER_COMMENTS} UserComments">
					</div>
				</header>
				<div class="content">
					<span itemprop="text">{event.CONTENTS}</span>
					<div class="spacer">&nbsp;</div>
					# IF event.C_LOCATION #
					<div itemprop="location" itemscope itemtype="http://schema.org/Place">
						<span class="text_strong">{@calendar.labels.location}</span> :
						<span itemprop="name">{event.LOCATION}</span>
					</div>
					# ENDIF #
					
					<div class="event_display_author" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						{@calendar.labels.created_by} : # IF event.AUTHOR #<a itemprop="author" href="{event.U_AUTHOR_PROFILE}" class="small_link {event.AUTHOR_LEVEL_CLASS}" # IF event.C_AUTHOR_GROUP_COLOR # style="color:{event.AUTHOR_GROUP_COLOR}" # ENDIF #>{event.AUTHOR}</a># ELSE #${LangLoader::get_message('guest', 'main')}# ENDIF #
					</div>
					<div class="event_display_dates">
						{@calendar.labels.start_date} : <span class="float_right"><time datetime="{event.START_DATE_ISO8601}" itemprop="startDate">{event.START_DATE}</time></span>
						<div class="spacer"></div>
						{@calendar.labels.end_date} : <span class="float_right"><time datetime="{event.END_DATE_ISO8601}" itemprop="endDate">{event.END_DATE}</time></span>
					</div>
				</div>
				<footer></footer>
			</article>
			# END event #
		# ELSE #
			<p class="center">{@calendar.notice.no_current_action}</p>
		# ENDIF #
	</div>
	<footer></footer>
</section>