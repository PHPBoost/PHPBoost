<section id="module-calendar">
	<header>
		<div class="align-right">
			<a href="{U_SYNDICATION}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss" aria-hidden="true"></i></a>
			{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit small" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1>
			<span itemprop="name">{TITLE}</span>
		</h1>
	</header>
	<div itemscope="itemscope" itemtype="http://schema.org/Event" id="article-calendar-{ID}" class="article-calendar# IF C_NEW_CONTENT # new-content# ENDIF #">

		# IF NOT C_APPROVED #
			# INCLUDE NOT_VISIBLE_MESSAGE #
		# ENDIF #
		<div class="controls">
			# IF C_EDIT #
				<a href="{U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
			# ENDIF #
			# IF C_DELETE #
				<a href="{U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}"# IF NOT C_BELONGS_TO_A_SERIE # data-confirmation="delete-element"# ENDIF #><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
			# ENDIF #
		</div>
		<a itemprop="url" href="{U_LINK}"></a>
		<div class="more">
				<div class="fa fa-user"></div> # IF C_AUTHOR_EXIST #<a itemprop="author" href="{U_AUTHOR_PROFILE}" class="{AUTHOR_LEVEL_CLASS}" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR}</a># ELSE #{AUTHOR}# ENDIF #
		</div>
		<div class="content# IF C_CANCELLED # error# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			<meta itemprop="about" content="{CATEGORY_NAME}">
			# IF C_COMMENTS_ENABLED #
				<meta itemprop="discussionUrl" content="{U_COMMENTS}">
				<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
			# ENDIF #

			# IF C_CANCELLED #
			<span class="message-helper bgc error">{@calendar.cancelled}</span>
			# ENDIF #

			<div class="options infos">
				<p class="event-display-dates">
					<span class="infos-options"><span class="text-strong">{@calendar.labels.start_date}</span> : <time datetime="{START_DATE_ISO8601}" itemprop="startDate">{START_DATE}</time></span>
					<span class="infos-options"><span class="text-strong">{@calendar.labels.end_date}</span> : <time datetime="{END_DATE_ISO8601}" itemprop="endDate">{END_DATE}</time></span>
				</p>
				# IF C_HAS_PICTURE #
					<img itemprop="thumbnailUrl" src="{PICTURE}" alt="{TITLE}" />
				# ENDIF #
				# IF C_LOCATION #
				<p itemprop="location" itemscope itemtype="http://schema.org/Place">
					<span class="text-strong">{@calendar.labels.location}</span> :
					<span itemprop="name">{LOCATION}</span>
				</p>
				# ENDIF #
				# IF C_PARTICIPATION_ENABLED #
					<div class="spacer"></div>
					# IF C_DISPLAY_PARTICIPANTS #
					<div>
						<span class="text-strong">{@calendar.labels.participants}</span> :
						<span>
							# IF C_PARTICIPANTS #
								# START participant #
									<a href="{participant.U_PROFILE}" class="{participant.LEVEL_CLASS}" # IF participant.C_GROUP_COLOR # style="color:{participant.GROUP_COLOR}" # ENDIF #>{participant.DISPLAY_NAME}</a># IF NOT participant.C_LAST_PARTICIPANT #,# ENDIF #
								# END participant #
							# ELSE #
								{@calendar.labels.no_one}
							# ENDIF #
						</span>
					</div>
					# ENDIF #
					# IF C_PARTICIPATE #
					<a href="{U_SUSCRIBE}" class="button alt-button">{@calendar.labels.suscribe}</a>
						# IF C_MISSING_PARTICIPANTS #
						<span class="small text-italic">({L_MISSING_PARTICIPANTS})</span>
						# ENDIF #
						# IF C_REGISTRATION_DAYS_LEFT #
						<div class="spacer"></div>
						<span class="small text-italic">{L_REGISTRATION_DAYS_LEFT}</span>
						# ENDIF #
					# ENDIF #
					# IF C_IS_PARTICIPANT #
					<a href="{U_UNSUSCRIBE}" class="button alt-button">{@calendar.labels.unsuscribe}</a>
					# ELSE #
						# IF C_MAX_PARTICIPANTS_REACHED #<span class="small text-italic">{@calendar.labels.max_participants_reached}</span># ENDIF #
					# ENDIF #
					# IF C_REGISTRATION_CLOSED #<span class="small text-italic">{@calendar.labels.registration_closed}</span># ENDIF #
				# ENDIF #
			</div>

			<div itemprop="text">{CONTENTS}</div>

			# IF C_LOCATION #
				<div class="location-map" itemprop="location" itemscope itemtype="http://schema.org/Place">
					# IF C_LOCATION_MAP #<div class="location-map">{LOCATION_MAP}</div># ENDIF #
				</div>
			# ENDIF #
		</div>
		<hr>

		# INCLUDE COMMENTS #
		<footer></footer>
	</div>
	<footer></footer>
</section>
