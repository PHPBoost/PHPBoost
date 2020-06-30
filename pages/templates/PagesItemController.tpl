<section id="module-pages" class="category-{CATEGORY_ID}">
	<header>
		<div class="controls align-right">
			<a href="{U_SYNDICATION}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			{@module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1><span id="name" itemprop="name">{TITLE}</span></h1>
	</header>
	# IF NOT C_VISIBLE #
		# INCLUDE NOT_VISIBLE_MESSAGE #
	# ENDIF #
	<article itemscope="itemscope" itemtype="http://schema.org/CreativeWork" id="pages-item-{ID}" class="pages-item single-item# IF C_NEW_CONTENT # new-content# ENDIF #">
		<div class="flex-between">
			<div class="more">
				# IF C_AUTHOR_DISPLAYED #
					<span class="pinned" aria-label="${LangLoader::get_message('author', 'common')}">
						<i class="far fa-fw fa-user" aria-hidden="true"></i>
						# IF C_AUTHOR_CUSTOM_NAME #
							{AUTHOR_CUSTOM_NAME}
						# ELSE #
							# IF C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="{USER_LEVEL_CLASS}" href="{U_AUTHOR_PROFILE}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{PSEUDO}</a># ELSE #<span class="visitor">{PSEUDO}</span># ENDIF #
						# ENDIF #
					</span>
				# ENDIF #
				<span class="pinned" aria-label="${LangLoader::get_message('form.date.creation', 'common')}">
					<i class="far fa-fw fa-calendar-alt" aria-hidden="true"></i>
					<time datetime="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT C_DIFFERED #{DATE}# ELSE #{DIFFERED_START_DATE}# ENDIF #</time>
				</span>
				<span class="pinned" aria-label="${LangLoader::get_message('category', 'categories-common')}"><i class="far fa-fw fa-folder"></i> <a itemprop="about" href="{U_CATEGORY}">{CATEGORY_NAME}</a></span>
				<span class="pinned" aria-label="{VIEWS_NUMBER} # IF C_SEVERAL_VIEWS #{@pages.views}# ELSE #{@pages.view}# ENDIF #"><i class="far fa-fw fa-eye"></i> {VIEWS_NUMBER}</span>
				# IF C_ENABLED_COMMENTS #
					<span"pinned" aria-label="${LangLoader::get_message('sort_by.comments.number', 'common')}"><i class="far fa-fw fa-comments"></i> # IF C_COMMENTS # {COMMENTS_NUMBER} # ENDIF # {L_COMMENTS}</span>
				# ENDIF #
			</div>
			# IF C_CONTROLS #
				<div class="controls align-right">
					# IF C_EDIT #<a href="{U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
					# IF C_DELETE #<a href="{U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
				</div>
			# ENDIF #
		</div>

		<div class="content">
			# IF C_HAS_THUMBNAIL #
					<img class="item-thumbnail" src="{U_THUMBNAIL}" alt="{NAME}" itemprop="thumbnailUrl" />
			# ENDIF #

			<div itemprop="text">{CONTENT}</div>
		</div>

		<aside>${ContentSharingActionsMenuService::display()}</aside>

		# IF C_UPDATED_DATE #
			<aside class="updated-date">
				<span class="text-strong">${LangLoader::get_message('form.date.update', 'common')} : </span>
				<time datetime="{UPDATED_DATE_ISO8601}" itemprop="dateModified">{UPDATED_DATE_FULL}</time>
			</aside>
		# ENDIF #

		# IF C_SOURCES #
			<aside class="sources-container">
				<span class="text-strong"><i class="fa fa-map-signs" aria-hidden="true"></i> ${LangLoader::get_message('form.sources', 'common')}</span> :
				# START sources #
					<a itemprop="isBasedOnUrl" href="{sources.URL}" class="pinned link-color" rel="nofollow">{sources.NAME}</a># IF sources.C_SEPARATOR ## ENDIF #
				# END sources #
			</aside>
		# ENDIF #
		# IF C_KEYWORDS #
			<aside class="tags-container">
				<span class="text-strong"><i class="fa fa-tags" aria-hidden="true"></i> ${LangLoader::get_message('form.keywords', 'common')} : </span>
				# START keywords #
					<a itemprop="pinned link-color" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
				# END keywords #
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
		<meta itemprop="description" content="${escape(SUMMARY)}" />
		# IF C_ENABLED_COMMENTS #
		<meta itemprop="discussionUrl" content="{U_COMMENTS}">
		<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
		# ENDIF #
	</footer>
</section>
