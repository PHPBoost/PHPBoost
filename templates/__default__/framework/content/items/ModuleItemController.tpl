<section id="module-{MODULE_ID}" class="category-{CATEGORY_ID}">
	<header class="section-header">
		<div class="controls align-right">
			# IF C_SYNDICATION #<a href="{U_SYNDICATION}" aria-label="{@syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a># ENDIF #
			{MODULE_NAME}
			# IF C_HAS_CATEGORIES #
				# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
				# IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="{@edit}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
			# ENDIF #
		</div>
		<h1 itemprop="name">{TITLE}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			# IF NOT C_PUBLISHED #
				<div class="content"># INCLUDE NOT_PUBLISHED_MESSAGE #</div>
			# ENDIF #
			<article id="{MODULE_ID}-item-{ID}" class="{MODULE_ID}-item single-item# IF C_PRIME_ITEM # prime-item# ENDIF ## IF C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
				<div class="flex-between">
					# IF C_MORE_OPTIONS #
						<div class="more">
							# IF C_AUTHOR_DISPLAYED #
								# IF C_AUTHOR_CUSTOM_NAME #
									<span class="pinned"><i class="far fa-user" aria-hidden="true"></i> <span class="custom-author">{AUTHOR_CUSTOM_NAME}</span></span>
								# ELSE #
									# IF NOT C_ID_CARD #
										<span class="pinned {AUTHOR_LEVEL_CLASS}"# IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}; border-color:{AUTHOR_GROUP_COLOR}" # ENDIF #>
											<i class="far fa-user" aria-hidden="true"></i> # IF C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="{AUTHOR_LEVEL_CLASS}" href="{U_AUTHOR_PROFILE}" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR_DISPLAY_NAME}</a># ELSE #<span class="visitor">{AUTHOR_DISPLAY_NAME}</span># ENDIF #
										</span>
									# ENDIF #
								# ENDIF #
							# ENDIF #
							# IF C_ENABLED_DATE #
								<span class="pinned" aria-label="{@form.date.creation}">
									<i class="far fa-calendar-alt" aria-hidden="true"></i>
									<time datetime="# IF C_DEFFERED_PUBLISHING #{DEFFERED_PUBLISHING_START_DATE_ISO8601}# ELSE #{DATE_ISO8601}# ENDIF #" itemprop="datePublished">
										# IF C_DEFFERED_PUBLISHING #{DEFFERED_PUBLISHING_START_DATE}# ELSE #{DATE}# ENDIF #
									</time>
								</span>
							# ENDIF #
							# IF C_HAS_CATEGORY #
								# IF NOT C_ROOT_CATEGORY #
								<span class="pinned">
									<a itemprop="about" href="{U_CATEGORY}"><i class="far fa-folder" aria-hidden="true"></i> {CATEGORY_NAME}</a>
								</span>
								# ENDIF #
							# ENDIF #
							# IF C_PUBLISHED #
								# IF C_ENABLED_VIEWS #
									<span class="pinned" role="contentinfo" aria-label="{VIEWS_NUMBER} # IF C_SEVERAL_VIEWS #{@views}# ELSE #{@view}# ENDIF #">
										<i class="fa fa-eye" aria-hidden="true"></i> {VIEWS_NUMBER} # IF C_SEVERAL_VIEWS #{@views}# ELSE #{@view}# ENDIF #
									</span>
								# ENDIF #
								# IF C_ENABLED_NOTATION #
									<li class="align-center">
										{NOTATION}
									</li>
								# ENDIF #
								# IF C_ENABLED_COMMENTS #
									<span class="pinned">
										<a href="#comments-list"><i class="fa fa-comments" aria-hidden="true"></i> {COMMENTS_LABEL}</a>
									</span>
								# ENDIF #
							# ENDIF #
						</div>
					# ELSE #
						<div></div>
					# ENDIF #

					# IF C_CONTROLS #
						<div class="controls align-right">
							# IF C_EDIT #<a href="{U_EDIT}" aria-label="{@edit}"><i class="far fa-fw fa-edit"></i></a># ENDIF #
							# IF C_DELETE #<a href="{U_DELETE}" data-confirmation="delete-element" aria-label="{@delete}"><i class="far fa-fw fa-trash-alt"></i></a># ENDIF #
						</div>
					# ENDIF #
				</div>
				# IF C_HAS_UPDATE_DATE #<span class="pinned notice small text-italic modified-date">{@status.last.update} <time datetime="{UPDATE_DATE_ISO8601}" itemprop="dateModified">{UPDATE_DATE_FULL}</time></span># ENDIF #

				<div class="content cell-tile">
					# IF C_CELL_OPTIONS #
						<div class="cell cell-options">
							<div class="cell-header">
								<h6 class="cell-name">{@item.infos}</h6>
							</div>
							# IF C_HAS_THUMBNAIL #
								<div class="cell-body">
									<div class="cell-thumbnail">
										<img src="{U_THUMBNAIL}" alt="{TITLE}" class="item-thumbnail" itemprop="thumbnailUrl" />
									</div>
								</div>
							# ENDIF #
							<div class="cell-list small">
								<ul>
									# IF C_ENABLED_VISIT #
										<li class="li-stretch">
											<a href="{U_VISIT}" class="button submit">
												<i class="fa fa-globe" aria-hidden="true"></i> {@go.visit}
											</a>
											# IF IS_USER_CONNECTED #
												<a href="{U_DEADLINK}" data-confirmation="{@deadlink.confirmation}" class="button bgc-full warning" aria-label="{@deadlink}">
													<i class="fa fa-unlink" aria-hidden="true"></i>
												</a>
											# ENDIF #
										</li>
										<li class="li-stretch">
											<span class="text-strong">{@visits.number} : </span>
											<span>{VISITS_NUMBER}</span>
										</li>
									# ENDIF #
									# IF C_ENABLED_DOWNLOAD #
										<li class="li-stretch">
											<a href="{U_DOWNLOAD}" class="button submit">
												<i class="fa fa-globe" aria-hidden="true"></i> {@go.download}
											</a>
											# IF IS_USER_CONNECTED #
												<a href="{U_DEADLINK}" data-confirmation="{@deadlink.confirmation}" class="button bgc-full warning" aria-label="{@deadlink}">
													<i class="fa fa-unlink" aria-hidden="true"></i>
												</a>
											# ENDIF #
										</li>
										<li class="li-stretch">
											<span class="text-strong">{@downloads.number} : </span>
											<span>{DOWNLOADS_NUMBER}</span>
										</li>
									# ENDIF #
									# IF C_AUTHOR_DISPLAYED #
										# IF NOT C_ID_CARD #
											<li class="li-stretch">
												<span class="text-strong">${TextHelper::ucfirst(@author)} : </span>
												# IF C_AUTHOR_CUSTOM_NAME #
													<span class="pinned"><i class="far fa-user" aria-hidden="true"></i> <span class="custom-author">{AUTHOR_CUSTOM_NAME}</span></span>
												# ELSE #
													<span class="pinned {AUTHOR_LEVEL_CLASS}"# IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}; border-color:{AUTHOR_GROUP_COLOR}" # ENDIF #>
														<i class="far fa-user" aria-hidden="true"></i> # IF C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="{AUTHOR_LEVEL_CLASS}" href="{U_AUTHOR_PROFILE}" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR_DISPLAY_NAME}</a># ELSE #<span class="visitor">{AUTHOR_DISPLAY_NAME}</span># ENDIF #
													</span>
												# ENDIF #
											</li>
										# ENDIF #
									# ENDIF #
									# IF C_ENABLED_DATE #
										<li class="li-stretch">
											<span class="text-strong">{@form.date.creation} : </span>
											<time datetime="# IF C_DEFFERED_PUBLISHING #{DEFFERED_PUBLISHING_START_DATE_ISO8601}# ELSE #{DATE_ISO8601}# ENDIF #" itemprop="datePublished">
												# IF C_DEFFERED_PUBLISHING #{DEFFERED_PUBLISHING_START_DATE}# ELSE #{DATE}# ENDIF #
											</time>
										</li>
									# ENDIF #
									# IF C_ENABLED_UPDATE_DATE #
										<li class="li-stretch">
											<span class="text-strong">{@form.date.update} : </span>
											<time datetime="{UPDATE_DATE_ISO8601}" itemprop="dateModified">
												{UPDATE_DATE}
											</time>
										</li>
									# ENDIF #
									# IF C_HAS_CATEGORY #
										# IF NOT C_ROOT_CATEGORY #
										<li class="li-stretch">
											<span class="text-strong">${LangLoader::get_message('category', 'categories-common')} : </span>
											<span><a itemprop="about" href="{U_CATEGORY}">{CATEGORY_NAME}</a></span>
										</li>
										# ENDIF #
									# ENDIF #
									# IF C_PUBLISHED #
										# IF C_ENABLED_VIEWS #
											<li class="li-stretch">
												<span><i class="fa fa-eye" aria-hidden="true"></i>${LangLoader::get_message('sort_by.views.number', 'common')} </span>
												<span>{VIEWS_NUMBER}</span>
											</li>
										# ENDIF #
										# IF C_ENABLED_COMMENTS #
											<li>
												<span><a href="#comments-list"><i class="fa fa-comments" aria-hidden="true"></i> {COMMENTS_LABEL}</a></span>
											</li>
										# ENDIF #
										# IF C_ENABLED_NOTATION #
											<li class="align-center">
												{NOTATION}
											</li>
										# ENDIF #
									# ENDIF #
								</ul>
							</div>
						</div>
					# ELSE #
						# IF C_HAS_THUMBNAIL #<img src="{U_THUMBNAIL}" alt="{TITLE}" class="item-thumbnail" itemprop="thumbnailUrl" /># ENDIF #
					# ENDIF #

					<div itemprop="text">{CONTENT}</div>
				</div>

				# IF C_AUTHOR_DISPLAYED #
					# IF NOT C_AUTHOR_CUSTOM_NAME #
						# IF C_ID_CARD #
							<aside>
								{ID_CARD}
							</aside>
						# ENDIF #
					# ENDIF #
				# ENDIF #

				<aside class="sharing-container">
					${ContentSharingActionsMenuService::display()}
				</aside>

				# IF C_KEYWORDS #
					<aside class="tags-container">
						<span class="text-strong"><i class="fa fa-tags" aria-hidden="true"></i> {@form.keywords}</span> :
						# START keywords #
							<a class="pinned question" href="{keywords.URL}" itemprop="keywords" rel="tag">{keywords.NAME}</a>
						# END keywords #
					</aside>
				# ENDIF #

				# IF C_SOURCES #
					<aside class="sources-container">
						<span class="text-strong"><i class="fa fa-map-signs" aria-hidden="true"></i> {@form.sources}</span> :
						# START sources #
							<a class="pinned question" href="{sources.URL}" itemprop="isBasedOnUrl" rel="nofollow">{sources.NAME}</a>
							# IF sources.C_SEPARATOR ## ENDIF #
						# END sources #
					</aside>
				# ENDIF #

				# INCLUDE ADDITIONAL_CONTENT #

				# IF C_PUBLISHED #
					# IF C_ENABLED_COMMENTS #
						<aside>
							# INCLUDE COMMENTS #
						</aside>
					# ENDIF #
				# ENDIF #
			</article>
		</div>
	</div>
	<footer>
		<meta itemprop="url" content="{U_ITEM}">
		<meta itemprop="description" content="${escape(SUMMARY)}" />
		# IF C_HAS_THUMBNAIL #<meta itemprop="thumbnailUrl" content="{U_THUMBNAIL}"># ENDIF #
		# IF C_ENABLED_COMMENTS #
			<meta itemprop="discussionUrl" content="{U_COMMENTS}">
			<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
		# ENDIF #
	</footer>

</section>
