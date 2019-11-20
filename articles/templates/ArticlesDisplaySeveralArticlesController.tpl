<section id="module-articles">
	<header>
		<div class="cat-actions">
			<a href="${relative_url(SyndicationUrlBuilder::rss('articles', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication" aria-hidden="true"></i></a>
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"> <i class="fa fa-edit small" aria-hidden="true"></i> </a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING #{@articles.pending.items}# ELSE #{@articles.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF #
		</h1>
	</header>
	# IF C_CATEGORY_DESCRIPTION #
	<div class="cat-description">
		{CATEGORY_DESCRIPTION}
	</div>
	# ENDIF #

	# IF C_SUB_CATEGORIES #
	<div class="subcat-container elements-container no-style# IF C_SEVERAL_CATS_COLUMNS # columns-{NUMBER_CATS_COLUMNS}# ENDIF #">
		# START sub_categories_list #
		<div class="subcat-element block">
			<div class="subcat-content">
				# IF C_DISPLAY_CATS_ICON #
					# IF sub_categories_list.C_CATEGORY_IMAGE #
						<a class="subcat-thumbnail" itemprop="about" href="{sub_categories_list.U_CATEGORY}">
							<img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" />
						</a>
					# ENDIF #
				# ENDIF #
				<a class="subcat-title" itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
				<span class="subcat-options">
					{sub_categories_list.ARTICLES_NUMBER}
					# IF sub_categories_list.C_MORE_THAN_ONE_ARTICLE #
						{@articles.items}
					# ELSE #
						{@articles.item}
					# ENDIF #
				</span>
			</div>
		</div>
		# END sub_categories_list #
	</div>
	# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
	# ENDIF #


	# IF C_NO_ARTICLE_AVAILABLE #
		# IF NOT C_HIDE_NO_ITEM_MESSAGE #
		<div class="center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
		# ENDIF #
	# ELSE #
		# IF C_MORE_THAN_ONE_ARTICLE #
			# IF C_ARTICLES_FILTERS #
			# INCLUDE FORM #
			<div class="spacer"></div>
			# ENDIF #
		# ENDIF #
		<div class="elements-container# IF C_SEVERAL_COLUMNS # columns-{NUMBER_COLUMNS}# ENDIF#">
			# START articles #
				<article id="article-articles-{articles.ID}" class="article-articles article-several# IF C_MOSAIC # block# ENDIF ## IF articles.C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
					<header>
						<h2><a itemprop="url" href="{articles.U_ARTICLE}"><span itemprop="name">{articles.TITLE}</span></a></h2>
					</header>

					<div class="actions">
						# IF articles.C_EDIT #
						<a href="{articles.U_EDIT_ARTICLE}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
						# ENDIF #
						# IF articles.C_DELETE #
						<a href="{articles.U_DELETE_ARTICLE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete" aria-hidden="true"></i></a>
						# ENDIF #
					</div>
					<div class="more">
						# IF articles.C_AUTHOR_DISPLAYED #
							<i class="fa fa-user"></i>
							# IF articles.C_AUTHOR_CUSTOM_NAME #
								{articles.AUTHOR_CUSTOM_NAME}
							# ELSE #
								# IF articles.C_AUTHOR_EXIST #<a itemprop="author" href="{articles.U_AUTHOR}" class="{articles.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{articles.USER_GROUP_COLOR}"# ENDIF #>{articles.PSEUDO}</a> | # ELSE #{articles.PSEUDO} | # ENDIF #
							# ENDIF #
						# ENDIF #
						<i class="fa fa-calendar-alt"></i> <time datetime="# IF NOT articles.C_DIFFERED #{articles.DATE_ISO8601}# ELSE #{articles.PUBLISHING_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT articles.C_DIFFERED #{articles.DATE} | # ELSE #{articles.PUBLISHING_START_DATE} | # ENDIF #</time>
						<i class="fa fa-folder"></i> <a itemprop="about" href="{articles.U_CATEGORY}">{articles.CATEGORY_NAME}</a>
					</div>

					<meta itemprop="url" content="{articles.U_ARTICLE}">
					<meta itemprop="description" content="${escape(articles.DESCRIPTION)}"/>
					<meta itemprop="discussionUrl" content="{articles.U_COMMENTS}">
					<meta itemprop="interactionCount" content="{articles.NUMBER_COMMENTS} UserComments">

					<div class="content">
						# IF articles.C_HAS_PICTURE #<a href="{articles.U_ARTICLE}" class="item-thumbnail"><img itemprop="thumbnailUrl" src="{articles.PICTURE}" alt="{articles.TITLE}" /></a># ENDIF #
						<div itemprop="text">{articles.DESCRIPTION}# IF articles.C_READ_MORE #... <a href="{articles.U_ARTICLE}" class="read-more">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #</div>
					</div>

					# IF articles.C_SOURCES #
					<div class="spacer"></div>
					<aside>
					<div id="articles-sources-container">
						<span>${LangLoader::get_message('form.sources', 'common')}</span> :
						# START articles.sources #
						<a itemprop="isBasedOnUrl" href="{articles.sources.URL}" class="small" rel="nofollow">{articles.sources.NAME}</a># IF articles.sources.C_SEPARATOR #, # ENDIF #
						# END articles.sources #
					</div>
					</aside>
					# ENDIF #

					<footer></footer>
				</article>
			# END articles #
		</div>
	# ENDIF #
		<div class="spacer"></div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
