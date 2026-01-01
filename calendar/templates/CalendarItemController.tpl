<section id="module-calendar" class="category-{CATEGORY_ID} single-item">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="{U_SYNDICATION}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			{@calendar.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1>
			<span itemprop="name">{TITLE}</span>
		</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<article itemscope="itemscope" itemtype="https://schema.org/Event" id="calendar-item-{ID}" class="calendar-item# IF C_NEW_CONTENT # new-content# ENDIF #">

				<div class="flex-between">
					<div class="more">
						<span class="pinned item-author"><i class="fa fa-user"></i> # IF C_AUTHOR_EXISTS #<a itemprop="author" href="{U_AUTHOR_PROFILE}" class="{AUTHOR_LEVEL_CLASS} offload" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR}</a># ELSE #<span class="visitor">{AUTHOR}</span># ENDIF #</span>
						<span class="pinned item-creation-date" aria-label="{@common.creation.date}"><i class="far fa-calendar-alt" aria-hidden="true"></i> <time datetime="{DATE_ISO8601}" itemprop="datePublished">{DATE}</time> </span>
						# IF NOT C_ROOT_CATEGORY #<span class="pinned-category item-category" data-color-surround="{CATEGORY_COLOR}"><a class="offload" aria-label="{@common.category}" href="{U_CATEGORY}">{CATEGORY_NAME}</a></span># ENDIF #
					</div>
					# IF C_CONTROLS #
						<div class="controls align-right">
							# IF C_EDIT #
								<a class="offload" href="{U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
							# ENDIF #
							# IF C_DELETE #
								<a href="{U_DELETE}" aria-label="{@common.delete}"# IF NOT C_BELONGS_TO_A_SERIE # data-confirmation="delete-element"# ENDIF #><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
							# ENDIF #
						</div>
					# ENDIF #
				</div>
				# IF C_HAS_UPDATE_DATE #
					<span class="pinned notice small text-italic item-modified-date"><i>{@common.last.update} : <time datetime="{UPDATE_DATE_ISO8601}" itemprop="datePublished">{UPDATE_DATE}</time></i></span>
				# ENDIF #

				# IF NOT C_APPROVED #
					# INCLUDE NOT_VISIBLE_MESSAGE #
				# ENDIF #

				<div class="content# IF C_CANCELLED # error# ENDIF #" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">

					# IF C_CANCELLED #
					<span class="message-helper bgc error">{@calendar.cancelled.item}</span>
					# ENDIF #

					<div class="cell-tile cell-options">
						<div class="cell">
							# IF C_HAS_THUMBNAIL #
								<div class="cell-body">
									<div class="cell-thumbnail">
										<img itemprop="thumbnailUrl" src="{U_THUMBNAIL}" alt="{TITLE}" />
									</div>
								</div>
							# ENDIF #
							<div class="cell-list">
								<ul>
									<li class="li-stretch">
										<span class="text-strong">{@calendar.start.date}</span>
										<time datetime="{START_DATE_ISO8601}" itemprop="startDate">{START_DATE_FULL}</time>
									</li>
									<li class="li-stretch">
										<span class="text-strong">{@calendar.end.date}</span>
										<time datetime="{END_DATE_ISO8601}" itemprop="endDate">{END_DATE_FULL}</time>
									</li>
									# IF C_LOCATION #
										<li itemprop="location" itemscope itemtype="https://schema.org/Place">
											<span class="text-strong">{@calendar.location}: </span>
											<span itemprop="name">{LOCATION}</span>
										</li>
									# ENDIF #
									# IF C_PARTICIPATION_ENABLED #
										# IF C_DISPLAY_PARTICIPANTS #
											<li>
												<span class="text-strong">{@calendar.participants}</span> :
												# IF C_PARTICIPANTS #
													# START participant #
														<a href="{participant.U_PROFILE}" class="{participant.LEVEL_CLASS} offload" # IF participant.C_GROUP_COLOR # style="color:{participant.GROUP_COLOR}" # ENDIF #>{participant.DISPLAY_NAME}</a># IF NOT participant.C_LAST_PARTICIPANT #,# ENDIF #
													# END participant #
												# ELSE #
													{@calendar.no.one}
												# ENDIF #
											</li>
										# ENDIF #
										# IF C_REGISTRATION_CLOSED #
											<li>
												<span class="small text-italic">{@calendar.registration.closed}</span>
											</li>
										# ELSE #
											# IF C_MAX_PARTICIPANTS_REACHED #
												<li>
													<span class="small text-italic">{@calendar.max.participants.reached}</span>
												</li>
											# ELSE #
												# IF C_PARTICIPATE #
													<li>
														<a href="{U_SUSCRIBE}" class="button submit offload">{@calendar.suscribe}</a>
														# IF C_MISSING_PARTICIPANTS #
															<span class="small text-italic">({L_MISSING_PARTICIPANTS})</span>
														# ENDIF #
														# IF C_REGISTRATION_DAYS_LEFT #
															<span class="small text-italic">{L_REGISTRATION_DAYS_LEFT}</span>
														# ENDIF #
													</li>
												# ENDIF #
											# ENDIF #
										# ENDIF #
										# IF C_IS_PARTICIPANT #
											<li>
												# IF C_UNSUBSCRIBE #
													<a href="{U_UNSUSCRIBE}" class="button alt-button offload">{@calendar.unsuscribe}</a>
												# ELSE #
													<span>{@calendar.unsuscribe.notice.expired.event.date}</span>
												# ENDIF #
											</li>
										# ENDIF #
									# ENDIF #
								</ul>
							</div>
						</div>
					</div>

					<div itemprop="text">{CONTENT}</div>
				</div>

				<aside>
					${ContentSharingActionsMenuService::display()}
				</aside>

				# IF C_LOCATION #
					<aside class="location-map" itemprop="location" itemscope itemtype="https://schema.org/Place">
						# IF C_LOCATION_MAP #<div class="location-map">{LOCATION_MAP}</div># ENDIF #
					</aside>
				# ENDIF #

				# IF C_COMMENTS_ENABLED #
					<aside># INCLUDE COMMENTS #</aside>
				# ENDIF #
			</article>
		</div>
	</div>
	<footer>
		<meta itemprop="url" href="{U_ITEM}">
		<meta itemprop="about" content="{CATEGORY_NAME}">
		# IF C_COMMENTS_ENABLED #
			<meta itemprop="discussionUrl" content="{U_COMMENTS}">
			<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
		# ENDIF #
	</footer>
</section>
