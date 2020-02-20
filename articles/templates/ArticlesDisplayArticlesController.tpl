<section id="module-articles" class="category-{CATEGORY_ID}">

	<header>
		<div class="align-right controls">
			<a href="{U_SYNDICATION}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			{MODULE_NAME}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"> <i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1><span itemprop="name">{TITLE}</span></h1>
	</header>

	# INCLUDE NOT_VISIBLE_MESSAGE #

	<article id="article-articles-{ID}" class="articles-item single-item# IF C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/Article">
		<div class="flex-between">
			<div class="more">
				# IF C_AUTHOR_DISPLAYED #
					<span class="pinned">
						# IF C_AUTHOR_CUSTOM_NAME #
							<i class="fa fa-user" aria-hidden="true"></i> <span class="custom-author">{AUTHOR_CUSTOM_NAME}</span>
						# ELSE #
							# IF NOT C_ID_CARD #
								<i class="fa fa-user" aria-hidden="true"></i> # IF C_AUTHOR_EXIST #<a itemprop="author" href="{U_AUTHOR}" class="{USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}"# ENDIF #>{PSEUDO}</a># ELSE #<span class="visitor">{PSEUDO}</span># ENDIF #
							# ENDIF #
						# ENDIF #
					</span>
				# ENDIF #
				<span class="pinned">
					<i class="far fa-calendar-alt" aria-hidden="true"></i> <time datetime="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{PUBLISHING_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT C_DIFFERED #{DATE}# ELSE #{PUBLISHING_START_DATE}# ENDIF #</time>
				</span>
				<span class="pinned">
					<i class="far fa-eye" aria-hidden="true"></i> <span role="contentinfo" aria-label="{VIEWS_NUMBER} ${LangLoader::get_message('views', 'main')}">{VIEWS_NUMBER}</span>
				</span>
				# IF C_ENABLED_COMMENTS #
					<span class="pinned">
						<i class="far fa-comments" aria-hidden="true"></i> <a itemprop="discussionUrl" class="small" href="{U_COMMENTS}"> {L_COMMENTS}</a>
					</span>
				# ENDIF #
				<span class="pinned">
					<i class="far fa-folder" aria-hidden="true"></i> <a itemprop="about" class="small" href="{U_CATEGORY}">{CATEGORY_NAME}</a>
				</span>
			</div>
			<div class="controls align-right">
				# IF C_CONTROLS #
					<a href="{U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
					<a href="{U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
				# ENDIF #
			</div>
		</div>

		# IF C_PAGINATION #
			# INCLUDE FORM #
			<div class="spacer"></div>
		# ENDIF #

		# IF PAGE_NAME #
			<h2 class="title page_name">{PAGE_NAME}</h2>
		# ENDIF #

		<div class="content" itemprop="text">
			# IF C_FIRST_PAGE #
				# IF C_HAS_THUMBNAIL #<img src="{U_THUMBNAIL}" alt="{TITLE}" class="item-thumbnail" itemprop="thumbnailUrl" /># ENDIF #
			# ENDIF #
			{CONTENT}
		</div>
		# IF C_PAGINATION #
			<aside class="flex-between">
				<div class="pages-pagination">
					# IF C_PREVIOUS_PAGE #
						<a href="{U_PREVIOUS_PAGE}"><i class="fa fa-angle-double-left"></i> {L_PREVIOUS_TITLE}</a>
					# ENDIF #
				</div>
				<div class="pages-pagination align-center"># INCLUDE PAGINATION_ARTICLES #</div>
				<div class="pages-pagination align-right">
					# IF C_NEXT_PAGE #
						<a href="{U_NEXT_PAGE}">{L_NEXT_TITLE} <i class="fa fa-angle-double-right"></i></a>
					# ENDIF #
				</div>
			</aside>
		# ENDIF #
		# IF C_AUTHOR_DISPLAYED #
			# IF NOT C_AUTHOR_CUSTOM_NAME #
				# IF C_ID_CARD #
					<aside>
						{ID_CARD}
					</aside>
				# ENDIF #
			# ENDIF #
		# ENDIF #

		<aside>
			${ContentSharingActionsMenuService::display()}
		</aside>

		# IF C_SOURCES #
			<aside class="sources-container">
				<span class="text-strong"><i class="fa fa-map-signs" aria-hidden="true"></i> ${LangLoader::get_message('form.sources', 'common')}</span> :
				# START sources #
					<a class="pinned question" itemprop="isBasedOnUrl" href="{sources.URL}" rel="nofollow">{sources.NAME}</a>
					# IF sources.C_SEPARATOR ## ENDIF #
				# END sources #
			</aside>
		# ENDIF #
		# IF C_KEYWORDS #
			<aside class="tags-container">
				<span class="text-strong"><i class="fa fa-tags" aria-hidden="true"></i> ${LangLoader::get_message('form.keywords', 'common')}</span> :
				# START keywords #
					<a class="pinned link-color" itemprop="keywords" href="{keywords.URL}">{keywords.NAME}</a>
					# IF keywords.C_SEPARATOR ## ENDIF #
				# END keywords #
			</aside>
		# ENDIF #
		# IF C_UPDATE_DATE #
			<aside><i>${LangLoader::get_message('form.date.update', 'common')} : <time datetime="{UPDATE_DATE_ISO8601}" itemprop="datePublished">{UPDATE_DATE}</time></i></aside>
		# ENDIF #
		# IF C_ENABLED_NOTATION #
			<aside>
				{KERNEL_NOTATION}
			</aside>
		# ENDIF #
		# IF C_ENABLED_COMMENTS #
			<aside>
				# INCLUDE COMMENTS #
			</aside>
		# ENDIF #
		<footer></footer>
	</article>
	<footer>
		<meta itemprop="url" content="{U_ITEM}">
		<meta itemprop="description" content="${escape(SUMMARY)}">
		<meta itemprop="datePublished" content="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{PUBLISHING_START_DATE_ISO8601}# ENDIF #">
		# IF C_HAS_PICTURE #<meta itemprop="thumbnailUrl" content="{PICTURE}"># ENDIF #
		# IF C_ENABLED_COMMENTS #
			<meta itemprop="discussionUrl" content="{U_COMMENTS}">
			<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
		# ENDIF #
	</footer>
</section>
