<section id="module-{MODULE_ID}" class="category-{CATEGORY_ID} single-item">
	<header class="section-header">
		<div class="controls align-right">
			# IF C_SYNDICATION #<a href="{U_SYNDICATION}" class="offload" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a># ENDIF #
			{MODULE_NAME}
			# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" class="offload" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1 itemprop="name">{TITLE}</h1>
		# IF C_COMPLETED #<div class="message-helper bgc success">{@completed.item}</div># ENDIF #
	</header>
	<div class="sub-section">
		<div class="content-container">
			# IF NOT C_PUBLISHED #
				<div class="content">
					# INCLUDE NOT_PUBLISHED_MESSAGE #
				</div>
			# ENDIF #
			<article id="{MODULE_ID}-item-{ID}" class="{MODULE_ID}-item# IF C_PRIME_ITEM # prime-item# ENDIF ## IF C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
				<div class="flex-between">
					# IF C_MORE_OPTIONS #
						<div class="more">
							# IF C_AUTHOR_DISPLAYED #
								<span class="pinned item-author">
									# IF C_AUTHOR_CUSTOM_NAME #
										<span aria-label="{@common.author}"><i class="far fa-user" aria-hidden="true"></i> <span class="custom-author">{AUTHOR_CUSTOM_NAME}</span></span>
									# ELSE #
										<span class="{AUTHOR_LEVEL_CLASS}" aria-label="{@common.author}"# IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}; border-color:{AUTHOR_GROUP_COLOR}" # ENDIF #>
											<i class="far fa-user" aria-hidden="true"></i> # IF C_AUTHOR_EXISTS #<a itemprop="author" rel="author" class="{AUTHOR_LEVEL_CLASS} offload" href="{U_AUTHOR_PROFILE}" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR_DISPLAY_NAME}</a># ELSE #<span class="visitor">{AUTHOR_DISPLAY_NAME}</span># ENDIF #
										</span>
									# ENDIF #
								</span>
							# ENDIF #
							# IF C_ENABLED_DATE #
								<span class="pinned item-creation-date" aria-label="{@common.creation.date}">
									<i class="far fa-calendar-alt" aria-hidden="true"></i>
									<time datetime="# IF C_DIFFERED #{DIFFERED_START_DATE_ISO8601}# ELSE #{DATE_ISO8601}# ENDIF #" itemprop="datePublished">
										# IF C_DIFFERED #{DIFFERED_START_DATE}# ELSE #{DATE}# ENDIF #
									</time>
								</span>
							# ENDIF #
							# IF C_ENABLED_CATEGORY #
								<span class="pinned item-category" aria-label="{@common.category}">
									<a class="offload" itemprop="about" href="{U_CATEGORY}"><i class="far fa-folder" aria-hidden="true"></i> {CATEGORY_NAME}</a>
								</span>
							# ENDIF #
							# IF C_ENABLED_VIEWS #
								<span class="pinned item-views-number" role="contentinfo" aria-label="{@common.views.number}">
									<i class="fa fa-eye" aria-hidden="true"></i> {VIEWS_NUMBER} # IF C_SEVERAL_VIEWS #{@views}# ELSE #{@view}# ENDIF #
								</span>
							# ENDIF #
							# IF C_ENABLED_COMMENTS #
								<span class="pinned item-comments" aria-label="{@common.comments}">
									<a href="#comments-list"><i class="fa fa-comments" aria-hidden="true"></i> {COMMENTS_LABEL}</a>
								</span>
							# ENDIF #
							# IF C_ENABLED_NOTATION #
								<li class="align-center d-inline-block item-notation">
									{NOTATION}
								</li>
							# ENDIF #
						</div>
					# ELSE #
						<span></span>
					# ENDIF #

					# IF C_CONTROLS #
						<div class="controls align-right">
							<a href="{U_EDIT}" class="offload item-edit" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
							<a class="item-delete" href="{U_DELETE}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
						</div>
					# ENDIF #
				</div>
				# IF C_HAS_UPDATE_DATE #<span class="pinned notice small text-italic item-modified-date">{@common.last.update} <time datetime="{UPDATE_DATE_ISO8601}" itemprop="dateModified">{UPDATE_DATE_FULL}</time></span># ENDIF #

				<div class="content">
					# IF C_ENABLED_COUNTDOWN #
						# INCLUDE COUNTDOWN #
					# ENDIF #
					# INCLUDE VOTE_FORM #
					# INCLUDE VOTES_RESULT #

					# IF C_HAS_THUMBNAIL #<img src="{U_THUMBNAIL}" alt="{TITLE}" class="item-thumbnail" itemprop="thumbnailUrl" /># ENDIF #

					<div itemprop="text">{CONTENT}</div>
				</div>

				<aside class="sharing-container">
					${ContentSharingActionsMenuService::display()}
				</aside>

				# IF C_KEYWORDS #
					<aside class="tags-container">
						<span class="text-strong"><i class="fa fa-tags" aria-hidden="true"></i> {@common.keywords}</span> :
						# START keywords #
							<a class="pinned question offload" href="{keywords.URL}" itemprop="keywords" rel="tag">{keywords.NAME}</a>
						# END keywords #
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
		<meta itemprop="description" content="${escape(SUMMARY)}" />
		# IF C_HAS_THUMBNAIL #<meta itemprop="thumbnailUrl" content="{U_THUMBNAIL}"># ENDIF #
		# IF C_ENABLED_COMMENTS #
			<meta itemprop="discussionUrl" content="{U_COMMENTS}">
			<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
		# ENDIF #
	</footer>

</section>
