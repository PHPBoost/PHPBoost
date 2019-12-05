			<section id="module-calendar-events">
				<header>
					<h2 class="align-center"># IF C_PENDING_PAGE #{@calendar.pending}# ELSE #{@calendar.titles.events_of} {DATE}# ENDIF #</h2>
				</header>

				# IF C_EVENTS #
				<div class="elements-container">
					# START event #
					<article itemscope="itemscope" itemtype="http://schema.org/Event" id="article-calendar-{event.ID}" class="article-calendar article-several# IF event.C_NEW_CONTENT # new-content# ENDIF #">
						<header>
							<h2>
								<a href="{event.U_SYNDICATION}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss" aria-hidden="true"></i></a>
								<a href="{event.U_LINK}"><span itemprop="name">{event.TITLE}</span></a>
							</h2>
							<a itemprop="url" href="{event.U_LINK}"></a>
						</header>
						<div class="controls">
							# IF C_COMMENTS_ENABLED #<a href="{event.U_COMMENTS}"><i class="fa fa-comments" aria-hidden="true"></i> {event.L_COMMENTS}</a># ENDIF #
							# IF event.C_EDIT #
								<a href="{event.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
							# ENDIF #
							# IF event.C_DELETE #
								<a href="{event.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}"# IF NOT event.C_BELONGS_TO_A_SERIE # data-confirmation="delete-element"# ENDIF #><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
							# ENDIF #
						</div>
						<div class="more">
							<div class="fa fa-user"></div> # IF event.C_AUTHOR_EXIST #<a itemprop="author" href="{event.U_AUTHOR_PROFILE}" class="{event.AUTHOR_LEVEL_CLASS}" # IF event.C_AUTHOR_GROUP_COLOR # style="color:{event.AUTHOR_GROUP_COLOR}" # ENDIF #>{event.AUTHOR}</a># ELSE #{event.AUTHOR}# ENDIF #
						</div>
						<div class="content# IF event.C_CANCELLED # error# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
							<meta itemprop="about" content="{event.CATEGORY_NAME}">
							# IF C_COMMENTS_ENABLED #
								<meta itemprop="discussionUrl" content="{event.U_COMMENTS}">
								<meta itemprop="interactionCount" content="{event.COMMENTS_NUMBER} UserComments">
							# ENDIF #

							# IF event.C_CANCELLED #
							<span class="message-helper bgc error">{@calendar.cancelled}</span>
							# ENDIF #

							<div class="options infos">
								<p class="event-display-dates">
									<span class="infos-options"><span class="text-strong">{@calendar.labels.start_date}</span> : <time datetime="{event.START_DATE_ISO8601}" itemprop="startDate">{event.START_DATE}</time></span>
									<span class="infos-options"><span class="text-strong">{@calendar.labels.end_date}</span> : <time datetime="{event.END_DATE_ISO8601}" itemprop="endDate">{event.END_DATE}</time></span>
								</p>

								# IF event.C_HAS_PICTURE #
									<img itemprop="thumbnailUrl" src="{event.PICTURE}" alt="{event.TITLE}" />
								# ENDIF #
								# IF event.C_LOCATION #
									<div class="spacer"></div>
									<div itemscope="itemscope" itemtype="http://schema.org/Place">
										<p itemprop="location">
											<span class="text-strong">{@calendar.labels.location}</span> :
											<span itemprop="name">{event.LOCATION}</span>
										</p>
									</div>
								# ENDIF #
								# IF event.C_PARTICIPATION_ENABLED #
									<div class="spacer"></div>
									# IF event.C_DISPLAY_PARTICIPANTS #
										<div>
											<span class="text-strong">{@calendar.labels.participants}</span> :
											<span>
												# IF event.C_PARTICIPANTS #
													# START event.participant #
														<a href="{event.participant.U_PROFILE}" class="{event.participant.LEVEL_CLASS}" # IF event.participant.C_GROUP_COLOR # style="color:{event.participant.GROUP_COLOR}" # ENDIF #>{event.participant.DISPLAY_NAME}</a># IF NOT event.participant.C_LAST_PARTICIPANT #,# ENDIF #
													# END event.participant #
												# ELSE #
													{@calendar.labels.no_one}
												# ENDIF #
											</span>
										</div>
									# ENDIF #
									# IF event.C_PARTICIPATE #
									<a href="{event.U_SUSCRIBE}" class="button alt-button">{@calendar.labels.suscribe}</a>
										# IF event.C_MISSING_PARTICIPANTS #
										<span class="small text-italic">({event.L_MISSING_PARTICIPANTS})</span>
										# ENDIF #
										# IF event.C_REGISTRATION_DAYS_LEFT #
										<div class="spacer"></div>
										<span class="small text-italic">{event.L_REGISTRATION_DAYS_LEFT}</span>
										# ENDIF #
									# ENDIF #
									# IF event.C_IS_PARTICIPANT #
									<a href="{event.U_UNSUSCRIBE}" class="button alt-button">{@calendar.labels.unsuscribe}</a>
									# ELSE #
										# IF event.C_MAX_PARTICIPANTS_REACHED #<span class="small text-italic">{@calendar.labels.max_participants_reached}</span># ENDIF #
									# ENDIF #
									# IF event.C_REGISTRATION_CLOSED #<span class="small text-italic">{@calendar.labels.registration_closed}</span># ENDIF #
								# ENDIF #
							</div>

							<div itemprop="text">{event.CONTENTS}</div>

						</div>
						<footer></footer>
					</article>
					# END event #
				</div>
				# ELSE #
					<div class="align-center">
						# IF C_PENDING_PAGE #{@calendar.notice.no_pending_event}# ELSE #{@calendar.notice.no_current_action}# ENDIF #
					</div>
				# ENDIF #
			</section>
