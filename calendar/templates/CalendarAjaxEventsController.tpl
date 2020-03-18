<section id="module-calendar-events">
	<header>
		<h2 class="align-center"># IF C_PENDING_PAGE #{@calendar.pending.events}# ELSE #{@calendar.events.of} {DATE}# ENDIF #</h2>
	</header>

	# IF C_EVENTS #
		<div class="cell-row">
			# START event #
				<article itemscope="itemscope" itemtype="http://schema.org/Event" id="article-calendar-{event.ID}" class="calendar-item several-items category-{event.CATEGORY_ID} cell# IF event.C_NEW_CONTENT # new-content# ENDIF #">
					<header class="cell-header">
						<h2 class="cell-name" itemprop="name">
							<a href="{event.U_ITEM}" itemprop="url">{event.TITLE}</a>
						</h2>
						<a href="{event.U_SYNDICATION}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
					</header>
					<div class="cell-infos">
						<div class="more">
							# IF event.C_AUTHOR_EXIST #
								<i class="fa fa-user"></i> <a itemprop="author" href="{event.U_AUTHOR_PROFILE}" class="pinned {event.AUTHOR_LEVEL_CLASS}" # IF event.C_AUTHOR_GROUP_COLOR # style="color:{event.AUTHOR_GROUP_COLOR}" # ENDIF #>{event.AUTHOR}</a>
							# ELSE #
								<span class="pinned notice"><i class="fa fa-user"></i> {event.AUTHOR}</span>
							# ENDIF #
							# IF C_COMMENTS_ENABLED #<a class="pinned" href="{event.U_COMMENTS}"><i class="fa fa-comments" aria-hidden="true"></i> {event.L_COMMENTS}</a># ENDIF #
							# IF event.C_LOCATION #
								<span class="pinned" itemscope="itemscope" itemtype="http://schema.org/Place">
									<i class="fa fa-fw fa-map-marker-alt"></i>
									<span class="text-strong" itemprop="name">{event.LOCATION}</span>
								</span>
							# ENDIF #
						</div>
						# IF event.C_CONTROLS #
							<div class="controls align-right">
								# IF event.C_EDIT #
									<a href="{event.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
								# ENDIF #
								# IF event.C_DELETE #
									<a href="{event.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}"# IF NOT event.C_BELONGS_TO_A_SERIE # data-confirmation="delete-element"# ENDIF #><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
								# ENDIF #
							</div>
						# ENDIF #
					</div>
					<div class="cell-body# IF event.C_CANCELLED # error# ENDIF #">
						# IF event.C_HAS_THUMBNAIL #
							<div class="cell-thumbnail">
								<img src="{event.U_THUMBNAIL}" alt="{event.TITLE}" />
								<a class="cell-thumbnail-caption" href="{event.U_ITEM}" itemprop="thumbnailUrl" aria-label="{event.TITLE}"><i class="far fa-eye" aria-hidden="true"></i></a>
							</div>
						# ENDIF #
						<div class="cell-content" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
							# IF event.C_CANCELLED #
							<span class="message-helper bgc error">{@calendar.cancelled.event}</span>
							# ENDIF #

							<div class="more">
								<span class="pinned">
									<i class="far fa-fw fa-calendar"></i>
									<time class="text-strong" datetime="{event.START_DATE_ISO8601}" itemprop="startDate">{event.START_DATE}</time>
									- <time class="text-strong" datetime="{event.END_DATE_ISO8601}" itemprop="endDate">{event.END_DATE}</time>
								</span>
								# IF event.C_PARTICIPATION_ENABLED #
									# IF event.C_DISPLAY_PARTICIPANTS #
										<span class="pinned">
											<i class="fa fa-fw fa-users"></i>
											<span class="text-strong">
												# IF event.C_PARTICIPANTS #
													# START event.participant #
														<a href="{event.participant.U_PROFILE}" class="{event.participant.LEVEL_CLASS}" # IF event.participant.C_GROUP_COLOR # style="color:{event.participant.GROUP_COLOR}" # ENDIF #>{event.participant.DISPLAY_NAME}</a># IF NOT event.participant.C_LAST_PARTICIPANT #,# ENDIF #
													# END event.participant #
												# ELSE #
													{@calendar.labels.no.one}
												# ENDIF #
											</span>
										</span>
									# ENDIF #
									# IF event.C_PARTICIPATE #
										<a href="{event.U_SUSCRIBE}" class="button submit small">{@calendar.labels.suscribe}</a>
										# IF event.C_MISSING_PARTICIPANTS #
											<span class="small text-italic">({event.L_MISSING_PARTICIPANTS})</span>
										# ENDIF #
										# IF event.C_REGISTRATION_DAYS_LEFT #
											<span class="small text-italic">{event.L_REGISTRATION_DAYS_LEFT}</span>
										# ENDIF #
									# ENDIF #
									# IF event.C_IS_PARTICIPANT #
										# IF event.C_UNSUBSCRIBE #
											<a href="{event.U_UNSUSCRIBE}" class="button alt-button">{@calendar.labels.unsuscribe}</a>
										# ENDIF #
									# ELSE #
										# IF event.C_MAX_PARTICIPANTS_REACHED #<span class="small text-italic">{@calendar.labels.max_participants_reached}</span># ENDIF #
									# ENDIF #
									# IF event.C_REGISTRATION_CLOSED #<span class="small text-italic">{@calendar.labels.registration_closed}</span># ENDIF #
								# ENDIF #
							</div>

							<div itemprop="text">{event.CONTENTS}</div>
						</div>
					</div>
					<footer>
						<meta itemprop="about" content="{event.CATEGORY_NAME}">
						# IF event.C_HAS_THUMBNAIL #<meta itemprop="thumbnailUrl" content="{event.U_THUMBNAIL}"># ENDIF #
						# IF C_COMMENTS_ENABLED #
							<meta itemprop="discussionUrl" content="{event.U_COMMENTS}">
							<meta itemprop="interactionCount" content="{event.COMMENTS_NUMBER} UserComments">
						# ENDIF #
					</footer>
				</article>
			# END event #
		</div>
	# ELSE #
		<div class="align-center">
			<span class="message-helper bgc notice"># IF C_PENDING_PAGE #{@calendar.notice.no.pending.event}# ELSE #{@calendar.notice.no.event}# ENDIF #</span>
		</div>
	# ENDIF #
</section>
