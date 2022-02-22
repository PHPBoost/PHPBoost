<section id="module-articles" class="category-{CATEGORY_ID} single-item">

	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="{U_SYNDICATION}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			{MODULE_NAME}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"> <i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1><span itemprop="name">{TITLE}</span></h1>
	</header>

	<div class="sub-section">
		<div class="content-container">
			# IF NOT C_PUBLISHED #
				<div class="content"># INCLUDE NOT_PUBLISHED_MESSAGE #</div>
			# ENDIF #
			<article id="article-articles-{ID}" class="articles-item# IF C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="https://schema.org/Article">
				<div class="flex-between">
					<div class="more">
						# IF C_AUTHOR_DISPLAYED #
							<span class="pinned item-author">
								# IF C_AUTHOR_CUSTOM_NAME #
									<i class="fa fa-user" aria-hidden="true"></i> <span class="custom-author">{AUTHOR_CUSTOM_NAME}</span>
								# ELSE #
									# IF NOT C_ID_CARD #
										<i class="fa fa-user" aria-hidden="true"></i> # IF C_AUTHOR_EXISTS #<a itemprop="author" href="{U_AUTHOR_PROFILE}" class="{USER_LEVEL_CLASS} offload" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}"# ENDIF #>{AUTHOR_DISPLAY_NAME}</a># ELSE #<span class="visitor">{AUTHOR_DISPLAY_NAME}</span># ENDIF #
									# ENDIF #
								# ENDIF #
							</span>
						# ENDIF #
						<span class="pinned item-creation-date">
							<i class="far fa-calendar-alt" aria-hidden="true"></i> <time datetime="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{PUBLISHING_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT C_DIFFERED #{DATE}# ELSE #{PUBLISHING_START_DATE}# ENDIF #</time>
						</span>
						# IF NOT C_ROOT_CATEGORY #
							<span class="pinned item-category">
								<i class="far fa-folder" aria-hidden="true"></i> <a class="offload" itemprop="about" href="{U_CATEGORY}">{CATEGORY_NAME}</a>
							</span>
						# ENDIF #
						<span class="pinned item-views-number">
							<i class="far fa-eye" aria-hidden="true"></i> <span role="contentinfo" aria-label="{VIEWS_NUMBER} {@common.views}">{VIEWS_NUMBER}</span>
						</span>
						# IF C_ENABLED_COMMENTS #
							<span class="pinned item-comments">
								<a href="#comments-list"><i class="fa fa-comments" aria-hidden="true"></i> {COMMENTS_LABEL}</a>
							</span>
						# ENDIF #
						# IF C_ENABLED_NOTATION #
							<div class="pinned item-notation">
								{NOTATION}
							</div>
						# ENDIF #
					</div>
					<div class="controls align-right">
						# IF C_CONTROLS #
							<a class="offload item-edit" href="{U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
							<a class="item-delete" href="{U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
						# ENDIF #
					</div>
				</div>
				# IF C_HAS_UPDATE_DATE #
					<span class="pinned notice small text-italic item-modified-date"><i>{@common.last.update} : <time datetime="{UPDATE_DATE_ISO8601}" itemprop="datePublished">{UPDATE_DATE}</time></i></span>
				# ENDIF #

				# IF C_PAGINATION #
					# INCLUDE FORM #
					<div class="spacer"></div>
				# ENDIF #

				# IF PAGE_NAME #
					<h2 class="page-title-name">{PAGE_NAME}</h2>
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
								<a class="offload" href="{U_PREVIOUS_PAGE}"><i class="fa fa-angle-double-left"></i> {L_PREVIOUS_TITLE}</a>
							# ENDIF #
						</div>
						<div class="pages-pagination align-center"># INCLUDE PAGINATION_ARTICLES #</div>
						<div class="pages-pagination align-right">
							# IF C_NEXT_PAGE #
								<a class="offload" href="{U_NEXT_PAGE}">{L_NEXT_TITLE} <i class="fa fa-angle-double-right"></i></a>
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
						<span class="text-strong"><i class="fa fa-map-signs" aria-hidden="true"></i> {@common.sources}</span> :
						# START sources #
							<a class="pinned question offload" itemprop="isBasedOnUrl" href="{sources.URL}" rel="nofollow">{sources.NAME}</a>
							# IF sources.C_SEPARATOR ## ENDIF #
						# END sources #
					</aside>
				# ENDIF #
				# IF C_KEYWORDS #
					<aside class="tags-container">
						<span class="text-strong"><i class="fa fa-tags" aria-hidden="true"></i> {@common.keywords}</span> :
						# START keywords #
							<a class="pinned link-color offload" itemprop="keywords" href="{keywords.URL}">{keywords.NAME}</a>
							# IF keywords.C_SEPARATOR ## ENDIF #
						# END keywords #
					</aside>
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
			</article>
		</div>
	</div>
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
