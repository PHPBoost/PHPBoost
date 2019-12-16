<section id="module-articles">
	<header>
		<div class="align-right controls">
			<a href="${relative_url(SyndicationUrlBuilder::rss('articles', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-fw fa-rss warning" aria-hidden="true"></i></a>
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"> <i class="fa fa-fw fa-edit" aria-hidden="true"></i> </a># ENDIF ## ENDIF #
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
		<div class="cell-flex cell-columns-{COLUMNS_NUMBER} no-style">
			# START sub_categories_list #
				<div class="cell">
					<div class="cell-header">
						<div class="cell-name align-center"><a class="subcat-title" itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a></div>
					</div>
					<div class="cell-body">
						# IF C_DISPLAY_CATS_ICON #
							<div class="cell-thumbnail cell-landscape">
								# IF sub_categories_list.C_CATEGORY_IMAGE #
									<a class="subcat-thumbnail" itemprop="about" href="{sub_categories_list.U_CATEGORY}">
										<img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" />
									</a>
								# ENDIF #
							</div>
						# ENDIF #
						<div class="cell-content align-center">
							<span>
								{sub_categories_list.ARTICLES_NUMBER}
								# IF sub_categories_list.C_MORE_THAN_ONE_ARTICLE #
									{@articles.items}
								# ELSE #
									{@articles.item}
								# ENDIF #
							</span>
						</div>
					</div>
				</div>
			# END sub_categories_list #
		</div>
		# IF C_SUBCATEGORIES_PAGINATION #<div class="align-center"># INCLUDE SUBCATEGORIES_PAGINATION #</div># ENDIF #
	# ENDIF #


	# IF C_NO_ARTICLE_AVAILABLE #
		# IF NOT C_HIDE_NO_ITEM_MESSAGE #
			<div class="align-center">
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
		<div class="# IF C_DISPLAY_GRID_VIEW #cell-flex cell-columns-{COLUMNS_NUMBER}# ELSE #cell-row# ENDIF #">
			# START articles #
				<article
					id="articles-item-{articles.ID}"
					class="articles-item several-items cell# IF articles.C_NEW_CONTENT # new-content# ENDIF #"
					itemscope="itemscope"
					itemtype="http://schema.org/CreativeWork">

					<header class="cell-header">
						<h2 class="cell-name" itemprop="name"><a href="{articles.U_ARTICLE}" itemprop="url">{articles.TITLE}</a></h2>
					</header>
					<div class="cell-body">
						<div class="cell-infos">
							<div class="more">
								# IF articles.C_AUTHOR_DISPLAYED #
									<i class="far fa-user"></i>
									# IF articles.C_AUTHOR_CUSTOM_NAME #
										<span class="pinned"></span>{articles.AUTHOR_CUSTOM_NAME}
									# ELSE #
										# IF articles.C_AUTHOR_EXIST #<a itemprop="author" href="{articles.U_AUTHOR}" class="pinned {articles.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{articles.USER_GROUP_COLOR}"# ENDIF #>{articles.PSEUDO}</a># ELSE #<span class="pinned">{articles.PSEUDO}</span># ENDIF #
									# ENDIF #
								# ENDIF #
								<span class="pinned notice"></span><i class="far fa-calendar-alt"></i> <time datetime="# IF NOT articles.C_DIFFERED #{articles.DATE_ISO8601}# ELSE #{articles.PUBLISHING_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT articles.C_DIFFERED #{articles.DATE}# ELSE #{articles.PUBLISHING_START_DATE}# ENDIF #</time>
								<span class="pinned question"><i class="far fa-folder"></i> <a itemprop="about" href="{articles.U_CATEGORY}">{articles.CATEGORY_NAME}</a></span>
							</div>
							# IF articles.C_CONTROLS #
								<div class="controls align-right">
									# IF articles.C_EDIT #
										<a href="{articles.U_EDIT_ARTICLE}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-fw fa-edit" aria-hidden="true"></i></a>
									# ENDIF #
									# IF articles.C_DELETE #
										<a href="{articles.U_DELETE_ARTICLE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-fw fa-trash-alt" aria-hidden="true"></i></a>
									# ENDIF #
								</div>
							# ENDIF #
						</div>
						# IF articles.C_HAS_PICTURE #
							<div class="cell-thumbnail cell-landscape">
								<img itemprop="thumbnailUrl" src="{articles.U_PICTURE}" alt="{articles.TITLE}" />
								<a href="{articles.U_ARTICLE}" class="cell-thumbnail-caption">
									# IF articles.C_READ_MORE #[${LangLoader::get_message('read-more', 'common')}]# ELSE #<i class="fa fa-eye"></i># ENDIF #
								</a>
							</div>
						# ENDIF #
						<div class="cell-content" itemprop="text">
							{articles.DESCRIPTION}
							# IF articles.C_READ_MORE #<a href="{articles.U_ARTICLE}" class="read-more">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
						</div>
					</div>

					<footer>
						<meta itemprop="url" content="{articles.U_ARTICLE}">
						<meta itemprop="description" content="${escape(articles.DESCRIPTION)}"/>
						# IF C_COMMENTS_ENABLED #
							<meta itemprop="discussionUrl" content="{articles.U_COMMENTS}">
							<meta itemprop="interactionCount" content="{articles.COMMENTS_NUMBER} UserComments">
						# ENDIF #
					</footer>
				</article>
			# END articles #
		</div>
	# ENDIF #
		<div class="spacer"></div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
