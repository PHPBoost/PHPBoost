<section id="module-calendar-events">
	<header>
		<h2 class="align-center"># IF NOT C_PENDING_ITEMS ## IF NOT C_MEMBER_ITEMS #{@calendar.events.of} {DATE}# ENDIF ## ENDIF #</h2>
		# IF C_ITEMS #<div class="more align-center"><span class="pinned notice">{L_ITEMS_NUMBER}</span></div># ENDIF #
	</header>

	# IF C_ITEMS #
		<div class="cell-row">
			# START items #
				<article itemscope="itemscope" itemtype="https://schema.org/Event" id="article-calendar-{items.ID}" class="calendar-item several-items category-{items.CATEGORY_ID} cell# IF items.C_NEW_CONTENT # new-content# ENDIF #">
					<header class="cell-header">
						<h2 class="cell-name" itemprop="name">
							<a href="{items.U_ITEM}" itemprop="url">{items.TITLE}</a>
						</h2>
						<a href="{items.U_SYNDICATION}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
					</header>
					<div class="cell-infos">
						<div class="more">
							# IF items.C_AUTHOR_EXIST #
								<i class="fa fa-user"></i> <a itemprop="author" href="{items.U_AUTHOR_PROFILE}" class="pinned {items.AUTHOR_LEVEL_CLASS}" # IF items.C_AUTHOR_GROUP_COLOR # style="color:{items.AUTHOR_GROUP_COLOR}" # ENDIF #>{items.AUTHOR}</a>
							# ELSE #
								<span class="pinned notice"><i class="fa fa-user"></i> {items.AUTHOR}</span>
							# ENDIF #
							# IF C_COMMENTS_ENABLED #<a class="pinned" href="{items.U_COMMENTS}"><i class="fa fa-comments" aria-hidden="true"></i> {items.L_COMMENTS}</a># ENDIF #
							# IF items.C_LOCATION #
								<span class="pinned" itemscope="itemscope" itemtype="https://schema.org/Place">
									<i class="fa fa-fw fa-map-marker-alt"></i>
									<span class="text-strong" itemprop="name">{items.LOCATION}</span>
								</span>
							# ENDIF #
						</div>
						# IF items.C_CONTROLS #
							<div class="controls align-right">
								# IF items.C_EDIT #
									<a href="{items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
								# ENDIF #
								# IF items.C_DELETE #
									<a href="{items.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}"# IF NOT items.C_BELONGS_TO_A_SERIE # data-confirmation="delete-element"# ENDIF #><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
								# ENDIF #
							</div>
						# ENDIF #
					</div>
					<div class="cell-body# IF items.C_CANCELLED # error# ENDIF #">
						# IF items.C_HAS_THUMBNAIL #
							<div class="cell-thumbnail">
								<img src="{items.U_THUMBNAIL}" alt="{items.TITLE}" />
								<a class="cell-thumbnail-caption" href="{items.U_ITEM}" itemprop="thumbnailUrl" aria-label="{items.TITLE}"><i class="far fa-eye" aria-hidden="true"></i></a>
							</div>
						# ENDIF #
						<div class="cell-content" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							# IF items.C_CANCELLED #
							<span class="message-helper bgc error">{@calendar.cancelled.event}</span>
							# ENDIF #

							<div class="more">
								<span class="pinned">
									<i class="far fa-fw fa-calendar"></i>
									<time class="text-strong" datetime="{items.START_DATE_ISO8601}" itemprop="startDate">{items.START_DATE}</time>
									- <time class="text-strong" datetime="{items.END_DATE_ISO8601}" itemprop="endDate">{items.END_DATE}</time>
								</span>
								<span class="pinned" data-color-surround="{items.CATEGORY_COLOR}"><i class="far fa-fw fa-folder"></i> {items.CATEGORY_NAME}</span>
								# IF items.C_PARTICIPATION_ENABLED #
									# IF items.C_DISPLAY_PARTICIPANTS #
										<span class="pinned">
											<i class="fa fa-fw fa-users"></i>
											<span class="text-strong">
												# IF items.C_PARTICIPANTS #
													# START items.participant #
														<a href="{items.participant.U_PROFILE}" class="{items.participant.LEVEL_CLASS}" # IF items.participant.C_GROUP_COLOR # style="color:{items.participant.GROUP_COLOR}" # ENDIF #>{items.participant.DISPLAY_NAME}</a># IF NOT items.participant.C_LAST_PARTICIPANT #,# ENDIF #
													# END items.participant #
												# ELSE #
													{@calendar.labels.no.one}
												# ENDIF #
											</span>
										</span>
									# ENDIF #
									# IF items.C_PARTICIPATE #
										<a href="{items.U_SUSCRIBE}" class="button submit small">{@calendar.labels.suscribe}</a>
										# IF items.C_MISSING_PARTICIPANTS #
											<span class="small text-italic">({items.L_MISSING_PARTICIPANTS})</span>
										# ENDIF #
										# IF items.C_REGISTRATION_DAYS_LEFT #
											<span class="small text-italic">{items.L_REGISTRATION_DAYS_LEFT}</span>
										# ENDIF #
									# ENDIF #
									# IF items.C_IS_PARTICIPANT #
										# IF items.C_UNSUBSCRIBE #
											<a href="{items.U_UNSUSCRIBE}" class="button alt-button">{@calendar.labels.unsuscribe}</a>
										# ENDIF #
									# ELSE #
										# IF items.C_MAX_PARTICIPANTS_REACHED #<span class="small text-italic">{@calendar.labels.max.participants.reached}</span># ENDIF #
									# ENDIF #
									# IF items.C_REGISTRATION_CLOSED #<span class="small text-italic">{@calendar.labels.registration.closed}</span># ENDIF #
								# ENDIF #
							</div>

							<div itemprop="text">{items.CONTENTS}</div>

						</div>
					</div>
					<footer>
						<meta itemprop="about" content="{items.CATEGORY_NAME}">
						# IF items.C_HAS_THUMBNAIL #<meta itemprop="thumbnailUrl" content="{items.U_THUMBNAIL}"># ENDIF #
						# IF C_COMMENTS_ENABLED #
							<meta itemprop="discussionUrl" content="{items.U_COMMENTS}">
							<meta itemprop="interactionCount" content="{items.COMMENTS_NUMBER} UserComments">
						# ENDIF #
					</footer>
				</article>
			# END items #
		</div>
	# ELSE #
		<div class="content">
			<div class="message-helper bgc notice align-center"># IF C_PENDING_ITEMS #{@calendar.notice.no.pending.event}# ELSE #{@calendar.notice.no.event}# ENDIF #</div>
		</div>
	# ENDIF #
</section>
