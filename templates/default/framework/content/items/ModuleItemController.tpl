<section id="module-{MODULE_ID}">
	<header>
		<div class="align-right controls">
			# IF C_SYNDICATION #<a href="{U_SYNDICATION}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden></i></a># ENDIF # {MODULE_ID}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden></i></a>
		</div>
		<h1 itemprop="name">{TITLE}</h1>
	</header>
	# IF NOT C_VISIBLE #
		<article class="content">
			# INCLUDE NOT_VISIBLE_MESSAGE #
		</article>
	# ENDIF #
	<article id="{MODULE_ID}-item-{ID}" class="{MODULE_ID}-item single-item# IF C_PRIME_ITEM # prime-item# ENDIF ## IF C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
		<div class="flex-between">
			# IF C_MORE_OPTIONS #
				<div class="more">
					# IF C_ENABLED_AUTHOR #
						# IF C_AUTHOR_DISPLAYED #
							# IF C_AUTHOR_CUSTOM_NAME #
								<span class="pinned"><i class="far fa-user" aria-hidden></i> {AUTHOR_CUSTOM_NAME}</span>
							# ELSE #
								# IF NOT C_ID_CARD #
									<span class="pinned {USER_LEVEL_CLASS}"# IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}; border-color:{USER_GROUP_COLOR}" # ENDIF #>
										<i class="far fa-user" aria-hidden></i> # IF C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="{USER_LEVEL_CLASS}" href="{U_AUTHOR_PROFILE}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{PSEUDO}</a># ELSE #{PSEUDO}# ENDIF #
									</span>
								# ENDIF #
							# ENDIF #
						# ENDIF #
					# ENDIF #
					# IF C_ENABLED_DATE #
						<span class="pinned">
							<i class="far fa-calendar-alt" aria-hidden></i>
							<time datetime="# IF C_DIFFERED #{DIFFERED_START_DATE_ISO8601}# ELSE #{DATE_ISO8601}# ENDIF #" itemprop="datePublished">
								# IF C_DIFFERED #{DIFFERED_START_DATE}# ELSE #{DATE}# ENDIF #
							</time>
						</span>
					# ENDIF #
					# IF C_ENABLED_CATEGORY #
						<span class="pinned">
							<a itemprop="about" href="{U_CATEGORY}"><i class="far fa-folder" aria-hidden></i> {CATEGORY_NAME}</a>
						</span>
					# ENDIF #
					# IF C_ENABLED_VIEWS #
						<span class="pinned" aria-label="{VIEWS_NUMBER} {@views}">
							<i class="fa fa-eye" aria-hidden></i> {VIEWS_NUMBER} # IF C_SEVERAL_VIEWS #{@views}# ELSE #{@view}# ENDIF #
						</span>
					# ENDIF #
					# IF C_ENABLED_NOTATION #
						<li class="align-center">
							{NOTATION}
						</li>
					# ENDIF #
					# IF C_ENABLED_COMMENTS #
						<span class="pinned">
							<a href="#comments-list"><i class="fa fa-comments" aria-hidden></i> {COMMENTS_NUMBER} # IF C_SEVERAL_COMMENTS #{@comments}# ELSE #{@comment}# ENDIF #</a>
						</span>
					# ENDIF #
				</div>
			# ENDIF #

			# IF C_CONTROLS #
				<div class="controls align-right">
					# IF C_EDIT #
						<a href="{U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit"></i></a>
					# ENDIF #
					# IF C_DELETE #
						<a href="{U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="far fa-fw fa-trash-alt"></i></a>
					# ENDIF #
				</div>
			# ENDIF #
		</div>

		<div class="content">
			# IF C_CELL_OPTIONS #
				<div class="cell cell-options">
					<div class="cell-header">
						<h6 class="cell-name">{@item.infos}</h6>
					</div>
					# IF C_HAS_THUMBNAIL #
						<div class="cell-body">
							<div class="cell-thumbnail">
								<img src="{U_PARTNER_THUMBNAIL}" alt="{TITLE}" itemprop="image" />
							</div>
						</div>
					# ENDIF #
					<div class="cell-list">
						<ul>
							# IF C_ENABLED_VISIT #
								<li class="li-stretch">
									<a href="{U_VISIT}" class="button submit">
										<i class="fa fa-globe" aria-hidden="true"></i> {@go.visit}
									</a>
									# IF IS_USER_CONNECTED #
										<a href="{U_DEADLINK}" data-confirmation="${LangLoader::get_message('deadlink.confirmation', 'common')}" class="button bgc-full warning" aria-label="${LangLoader::get_message('deadlink', 'common')}">
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
										<a href="{U_DEADLINK}" data-confirmation="${LangLoader::get_message('deadlink.confirmation', 'common')}" class="button bgc-full warning" aria-label="${LangLoader::get_message('deadlink', 'common')}">
											<i class="fa fa-unlink" aria-hidden="true"></i>
										</a>
									# ENDIF #
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@downloads.number} : </span>
									<span>{DOWNLOADS_NUMBER}</span>
								</li>
							# ENDIF #
							# IF C_ENABLED_AUTHOR #
								# IF C_AUTHOR_DISPLAYED #
									<li class="li-stretch">
										<span class="text-strong">{@Author} : </span>
										# IF C_AUTHOR_CUSTOM_NAME #
											<span class="pinned"><i class="far fa-user" aria-hidden></i> {AUTHOR_CUSTOM_NAME}</span>
										# ELSE #
											# IF NOT C_ID_CARD #
												<span class="pinned {USER_LEVEL_CLASS}"# IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}; border-color:{USER_GROUP_COLOR}" # ENDIF #>
													<i class="far fa-user" aria-hidden></i> # IF C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="{USER_LEVEL_CLASS}" href="{U_AUTHOR_PROFILE}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{PSEUDO}</a># ELSE #{PSEUDO}# ENDIF #
												</span>
											# ENDIF #
										# ENDIF #
									</li>
								# ENDIF #
							# ENDIF #
							# IF C_ENABLED_DATE #
								<li class="li-stretch">
									<span class="text-strong">{@Date} : </span>
									<time datetime="# IF C_DIFFERED #{DIFFERED_START_DATE_ISO8601}# ELSE #{DATE_ISO8601}# ENDIF #" itemprop="datePublished">
										# IF C_DIFFERED #{DIFFERED_START_DATE}# ELSE #{DATE}# ENDIF #
									</time>
								</li>
							# ENDIF #
							# IF C_ENABLED_CATEGORY #
								<li class="li-stretch">
									<span class="text-strong">{@Category} : </span>
									<span><a itemprop="about" href="{U_CATEGORY}">{CATEGORY_NAME}</a></span>
								</li>
							# ENDIF #
							# IF C_ENABLED_COMMENTS #
								<li>
									<span># IF C_COMMENTS # {COMMENTS_NUMBER} # ENDIF # {L_COMMENTS}</span>
								</li>
							# ENDIF #
							# IF C_ENABLED_NOTATION #
								<li class="align-center">
									{NOTATION}
								</li>
							# ENDIF #
						</ul>
					</div>
				</div>
			# ELSE #
				# IF C_HAS_THUMBNAIL #<img src="{U_THUMBNAIL}" alt="{TITLE}" class="item-thumbnail" itemprop="thumbnailUrl" /># ENDIF #
			# ENDIF #

			<div itemprop="text">{CONTENTS}</div>
		</div>

		# IF C_ENABLED_AUTHOR #
			# IF C_AUTHOR_DISPLAYED #
				# IF NOT C_AUTHOR_CUSTOM_NAME #
					# IF C_ID_CARD #
						<aside>
					 		{ID_CARD}
						</aside>
					# ENDIF #
				# ENDIF #
			# ENDIF #
		# ENDIF #

		<aside>
			${ContentSharingActionsMenuService::display()}
		</aside>

		# IF C_SOURCES #
			<aside class="sources-container">
				<span class="text-strong"><i class="fa fa-map-signs" aria-hidden></i> ${LangLoader::get_message('form.sources', 'common')}</span> :
				# START sources #
					<a class="pinned question" href="{sources.URL}" itemprop="isBasedOnUrl" rel="nofollow">{sources.NAME}</a>
					# IF sources.C_SEPARATOR ## ENDIF #
				# END sources #
			</aside>
		# ENDIF #

		# IF C_KEYWORDS #
			<aside class="tags-container">
				<span class="text-strong"><i class="fa fa-tags" aria-hidden></i> ${LangLoader::get_message('form.keywords', 'common')}</span> :
				# START keywords #
					<a class="pinned question" href="{keywords.URL}" itemprop="keywords" rel="tag">{keywords.NAME}</a>
				# END keywords #
			</aside>
		# ENDIF #

		# IF C_SUGGESTED_ITEMS #
			<aside class="suggested-links">
				<span class="text-strong"><i class="fa fa-lightbulb"></i> ${LangLoader::get_message('suggestions', 'common')} :</span>
				<ul>
					# START suggested #
						<li>
							<a href="{suggested.U_ITEM}" class="suggested-item">
								<img src="{suggested.U_THUMBNAIL}" alt="{suggested.NAME}"> {suggested.NAME}
							</a>
						</li>
					# END suggested #
				</ul>
			</aside>
		# ENDIF #

		# IF C_RELATED_ITEMS #
			<aside class="related-links">
				# IF C_PREVIOUS_ITEM #
					<a class="related-item previous-item" href="{U_PREVIOUS_ITEM}">
						<i class="fa fa-chevron-left"></i>
						<img src="{U_PREVIOUS_THUMBNAIL}" alt="{PREVIOUS_ITEM}">
						{PREVIOUS_ITEM}
					</a>
				# ELSE #
					<span></span>
				# ENDIF #
				# IF C_NEXT_ITEM #
					<a class="related-item next-item" href="{U_NEXT_ITEM}">
						{NEXT_ITEM}
						<img src="{U_NEXT_THUMBNAIL}" alt="{NEXT_ITEM}">
						<i class="fa fa-chevron-right"></i>
					</a>
				# ENDIF #
			</aside>
		# ENDIF #

		# IF C_ENABLED_COMMENTS #
			<aside>
				# INCLUDE COMMENTS #
			</aside>
		# ENDIF #
	</article>

	<footer>
		<meta itemprop="url" content="{U_ITEM}">
		<meta itemprop="description" content="${escape(DESCRIPTION)}" />
		# IF C_HAS_THUMBNAIL #<meta itemprop="thumbnailUrl" content="{U_THUMBNAIL}"># ENDIF #
		# IF C_ENABLED_COMMENTS #
			<meta itemprop="discussionUrl" content="{U_COMMENTS}">
			<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
		# ENDIF #
	</footer>

</section>