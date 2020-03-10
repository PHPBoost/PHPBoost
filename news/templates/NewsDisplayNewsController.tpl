<section id="module-news" class="category-{CATEGORY_ID}">
	<header>
		<div class="align-right controls">
			<a href="{U_SYNDICATION}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a> {@news}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1 itemprop="name">{TITLE}</h1>
	</header>
	# IF NOT C_VISIBLE #
		<article class="content">
			# INCLUDE NOT_VISIBLE_MESSAGE #
		</article>
	# ENDIF #
	<article id="news-item-{ID}" class="news-item# IF C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
		<div class="flex-between">
			<div class="more">
				# IF C_AUTHOR_DISPLAYED #
					# IF C_AUTHOR_CUSTOM_NAME #
						<span class="pinned"><i class="far fa-user" aria-hidden="true"></i> <span class="custom-author">{AUTHOR_CUSTOM_NAME}</span></span>
					# ELSE #
						# IF NOT C_ID_CARD #
							<span class="pinned {USER_LEVEL_CLASS}"# IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}; border-color:{USER_GROUP_COLOR}" # ENDIF #>
								<i class="far fa-user" aria-hidden="true"></i> # IF C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="{USER_LEVEL_CLASS}" href="{U_AUTHOR_PROFILE}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{PSEUDO}</a># ELSE #<span class="visitor">{PSEUDO}</span># ENDIF #
							</span>
						# ENDIF #
					# ENDIF #
				# ENDIF #
				<span class="pinned">
					<i class="far fa-calendar-alt" aria-hidden="true"></i> <time datetime="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT C_DIFFERED #{DATE}# ELSE #{DIFFERED_START_DATE}# ENDIF #</time>
				</span>
				<span class="pinned">
					<a itemprop="about" href="{U_CATEGORY}"><i class="far fa-folder" aria-hidden="true"></i> {CATEGORY_NAME}</a>
				</span>
				# IF C_COMMENTS_ENABLED #<span class="pinned"><a href="#comments-list"><i class="fa fa-comments" aria-hidden="true"></i> # IF C_COMMENTS #{COMMENTS_NUMBER}# ENDIF # {L_COMMENTS}</a></span># ENDIF #
				# IF C_VIEWS_NUMBER #<span class="pinned" role="contentinfo" aria-label="{VIEWS_NUMBER} {@news.view}"><i class="fa fa-eye" aria-hidden="true"></i> {VIEWS_NUMBER}</span># ENDIF #
			</div>
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
			# IF C_HAS_THUMBNAIL #<img src="{U_THUMBNAIL}" alt="{TITLE}" class="item-thumbnail" itemprop="thumbnailUrl" /># ENDIF #

			<div itemprop="text">{CONTENTS}</div>
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

		# IF C_SOURCES #
			<aside class="sources-container">
				<span class="text-strong"><i class="fa fa-map-signs" aria-hidden="true"></i> ${LangLoader::get_message('form.sources', 'common')}</span> :
				# START sources #
					<a class="pinned question" href="{sources.URL}" itemprop="isBasedOnUrl" rel="nofollow">{sources.NAME}</a>
					# IF sources.C_SEPARATOR ## ENDIF #
				# END sources #
			</aside>
		# ENDIF #

		# IF C_KEYWORDS #
			<aside class="tags-container">
				<span class="text-strong"><i class="fa fa-tags" aria-hidden="true"></i> ${LangLoader::get_message('form.keywords', 'common')}</span> :
				# START keywords #
					<a class="pinned question" href="{keywords.URL}" itemprop="keywords" rel="tag">{keywords.NAME}</a>
				# END keywords #
			</aside>
		# ENDIF #

		# IF C_SUGGESTED_NEWS #
			<aside class="suggested-links">
				<span class="text-strong"><i class="fa fa-lightbulb"></i> ${LangLoader::get_message('suggestions', 'common')} :</span>
				<div class="cell-row">
					# START suggested #
						<div class="cell">
							<div class="cell-body">
								<div class="cell-thumbnail cell-landscape cell-center">
									<img src="{suggested.U_THUMBNAIL}" alt="{suggested.TITLE}">
								</div>
								<div class="cell-content">
									<a href="{suggested.U_CATEGORY}" class="small">{suggested.CATEGORY_NAME}</a>
									<a href="{suggested.U_ITEM}" class="suggested-item">
									 	<h6>{suggested.TITLE}</h6>
									</a>
								</div>
							</div>
						</div>
					# END suggested #
				</div>
			</aside>
		# ENDIF #

		# IF C_RELATED_LINKS #
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

		# IF C_COMMENTS_ENABLED #
			<aside>
				# INCLUDE COMMENTS #
			</aside>
		# ENDIF #
	</article>

	<footer>
		<meta itemprop="url" content="{U_ITEM}">
		<meta itemprop="description" content="${escape(DESCRIPTION)}" />
		# IF C_HAS_THUMBNAIL #<meta itemprop="thumbnailUrl" content="{U_THUMBNAIL}"># ENDIF #
		# IF C_COMMENTS_ENABLED #
			<meta itemprop="discussionUrl" content="{U_COMMENTS}">
			<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
		# ENDIF #
	</footer>

</section>
