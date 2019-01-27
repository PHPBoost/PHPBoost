<section id="module-news">
	<header>
		<div class="cat-actions">
			<a href="{U_SYNDICATION}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication" aria-hidden="true" title="${LangLoader::get_message('syndication', 'common')}"></i></a> {@news}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit small" aria-hidden="true" title="${LangLoader::get_message('edit', 'common')}"></i></a># ENDIF #
		</div>
		<h1><span itemprop="name">{NAME}</span></h1>
	</header>
	# IF NOT C_VISIBLE #
		<div class="content">
			# INCLUDE NOT_VISIBLE_MESSAGE #
		</div>
	# ENDIF #
	<div itemscope="itemscope" itemtype="http://schema.org/CreativeWork" id="article-news-{ID}" class="article-news# IF C_NEW_CONTENT # new-content# ENDIF #">
		<div class="actions">
			# IF C_EDIT #
			<a href="{U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" title="${LangLoader::get_message('edit', 'common')}"></i></a>
			# ENDIF #
			# IF C_DELETE #
			<a href="{U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-delete" title="${LangLoader::get_message('delete', 'common')}"></i></a>
			# ENDIF #
		</div>
		<div class="more">
			# IF C_AUTHOR_DISPLAYED #
			<span class="more-element more-element-author">
				<i class="fa fa-user-o" aria-hidden="true"></i>
				# IF C_AUTHOR_CUSTOM_NAME #
					{AUTHOR_CUSTOM_NAME}
				# ELSE #
					# IF C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="{USER_LEVEL_CLASS}" href="{U_AUTHOR_PROFILE}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{PSEUDO}</a># ELSE #{PSEUDO}# ENDIF #
				# ENDIF #
				 |
			</span>
			# ENDIF #
			<span class="more-element more-element-date">
				<i class="fa fa-calendar" aria-hidden="true"></i> <time datetime="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT C_DIFFERED #{DATE}# ELSE #{DIFFERED_START_DATE}# ENDIF #</time>
			</span> |
			<span class="more-element more-element-category">
				<i class="fa fa-folder-o" aria-hidden="true"></i> <a itemprop="about" href="{U_CATEGORY}">{CATEGORY_NAME}</a>
			</span>
			# IF C_COMMENTS_ENABLED # | <span class="more-element more-element-comments"><i class="fa fa-comments-o" aria-hidden="true"></i> <a href="#comments-list" title="${LangLoader::get_message('comments.link','comments-common')}"># IF C_COMMENTS #{NUMBER_COMMENTS}# ENDIF # {L_COMMENTS}</a></span># ENDIF #
			# IF C_NB_VIEW_ENABLED # | <span title="{NUMBER_VIEW} {@news.view}" class="more-element more-element-view"><i class="fa fa-eye" aria-hidden="true"></i> {NUMBER_VIEW}</span># ENDIF #

		</div>

		<meta itemprop="url" content="{U_LINK}">
		<meta itemprop="description" content="${escape(DESCRIPTION)}" />
		# IF C_COMMENTS_ENABLED #
		<meta itemprop="discussionUrl" content="{U_COMMENTS}">
		<meta itemprop="interactionCount" content="{NUMBER_COMMENTS} UserComments">
		# ENDIF #

		<div class="content">
			# IF C_PICTURE #<img itemprop="thumbnailUrl" src="{U_PICTURE}" alt="{NAME}" title="{NAME}" class="thumbnail-item" /># ENDIF #

			<div itemprop="text">{CONTENTS}</div>
		</div>

		<aside>
			${ContentSharingActionsMenuService::display()}

			# IF C_SOURCES #
				<div id="news-sources-container">
					<span class="news-sources-title"><i class="fa fa-map-signs" aria-hidden="true"></i> ${LangLoader::get_message('form.sources', 'common')}</span> :
					# START sources #
					<a itemprop="isBasedOnUrl" href="{sources.URL}" class="small news-sources-item" rel="nofollow">{sources.NAME}</a># IF sources.C_SEPARATOR #, # ENDIF #
					# END sources #
				</div>
			# ENDIF #

			# IF C_KEYWORDS #
				<div id="news-tags-container">
					<span class="news-tags-title"><i class="fa fa-tags" aria-hidden="true"></i> ${LangLoader::get_message('form.keywords', 'common')}</span> :
						# START keywords #
							<a itemprop="keywords" rel="tag" href="{keywords.URL}" class="news-tags-item">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
						# END keywords #
				</div>
			# ENDIF #

			# IF C_SUGGESTED_NEWS #
				<div id="news-suggested-container">
					<span class="news-suggested-title"><i class="fa fa-lightbulb-o"></i> ${LangLoader::get_message('suggestions', 'common')} :</span>
					<ul>
						# START suggested #
						<li><a href="{suggested.URL}" class="news-suggested-item">{suggested.NAME}</a></li>
						# END suggested #
					</ul>
				</div>
			# ENDIF #

			<hr class="news-separator">

			# IF C_NEWS_NAVIGATION_LINKS #
				<div class="navigation-link">
					# IF C_PREVIOUS_NEWS #
					<span class="navigation-link-previous">
						<a href="{U_PREVIOUS_NEWS}"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>{PREVIOUS_NEWS}</a>
					</span>
					# ENDIF #
					# IF C_NEXT_NEWS #
					<span class="navigation-link-next">
						<a href="{U_NEXT_NEWS}">{NEXT_NEWS}<i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
					</span>
					# ENDIF #
					<div class="spacer"></div>
				</div>
			# ENDIF #

			# INCLUDE COMMENTS #
		</aside>
	</div>
	<footer></footer>
</section>
