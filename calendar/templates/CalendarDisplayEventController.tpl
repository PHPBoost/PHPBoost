<section id="module-calendar">
	<header>
		<h1>
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF #
		</h1>
	</header>
	<div class="content">
		# IF NOT C_APPROVED #
			# INCLUDE NOT_VISIBLE_MESSAGE #
		# ENDIF #
		<article itemscope="itemscope" itemtype="http://schema.org/Event" id="article-calendar-{ID}" class="article-calendar">
			<header>
				<h2>
					<span itemprop="name">{TITLE}</span>
					<span class="actions">
						# IF C_EDIT #
							<a href="{U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						# ENDIF #
						# IF C_DELETE #
							<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'common')}"# IF NOT C_BELONGS_TO_A_SERIE # data-confirmation="delete-element"# ENDIF #><i class="fa fa-delete"></i></a>
						# ENDIF #
					</span>
				</h2>
				
				<a itemprop="url" href="{U_LINK}"></a>
			</header>
			<div class="content">
				<div itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
					<meta itemprop="about" content="{CATEGORY_NAME}">
					# IF C_COMMENTS_ENABLED #
					<meta itemprop="discussionUrl" content="{U_COMMENTS}">
					<meta itemprop="interactionCount" content="{NUMBER_COMMENTS} UserComments">
					# ENDIF #
					# IF C_HAS_PICTURE #<img itemprop="thumbnailUrl" src="{PICTURE}" alt="{TITLE}" /># ENDIF #
					<div itemprop="text">{CONTENTS}</div>
					
					# IF C_LOCATION #
					<div class="spacer"></div>
					<div itemprop="location" itemscope itemtype="http://schema.org/Place">
						<span class="text-strong">{@calendar.labels.location}</span> :
						<span itemprop="name">{LOCATION}</span>
					</div>
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
						<a href="{U_SUSCRIBE}" class="basic-button">{@calendar.labels.suscribe}</a>
							# IF C_MISSING_PARTICIPANTS #
							<span class="small text-italic">({L_MISSING_PARTICIPANTS})</span>
							# ENDIF #
							# IF C_REGISTRATION_DAYS_LEFT #
							<div class="spacer"></div>
							<span class="small text-italic">{L_REGISTRATION_DAYS_LEFT}</span>
							# ENDIF #
						# ENDIF #
						# IF C_IS_PARTICIPANT #
						<a href="{U_UNSUSCRIBE}" class="basic-button">{@calendar.labels.unsuscribe}</a>
						# ELSE #
							# IF C_MAX_PARTICIPANTS_REACHED #<span class="small text-italic">{@calendar.labels.max_participants_reached}</span># ENDIF #
						# ENDIF #
						# IF C_REGISTRATION_CLOSED #<span class="small text-italic">{@calendar.labels.registration_closed}</span># ENDIF #
					# ENDIF #
					
					<div class="spacer"></div>
					<div class="event-display-author">
						{@calendar.labels.created_by} : # IF C_AUTHOR_EXIST #<a itemprop="author" href="{U_AUTHOR_PROFILE}" class="{AUTHOR_LEVEL_CLASS}" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR}</a># ELSE #{AUTHOR}# ENDIF #
					</div>
				</div>
				<div class="event-display-dates">
					{@calendar.labels.start_date} : <span class="float-right"><time datetime="{START_DATE_ISO8601}" itemprop="startDate">{START_DATE}</time></span>
					<div class="spacer"></div>
					{@calendar.labels.end_date} : <span class="float-right"><time datetime="{END_DATE_ISO8601}" itemprop="endDate">{END_DATE}</time></span>
				</div>
				
				<hr>
				
				# INCLUDE COMMENTS #
			</div>
			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>
