<section id="module-articles">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('articles', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #{@articles.pending_articles}# ELSE #{@articles}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF #
		</h1>
		# IF C_CATEGORY_DESCRIPTION #
			<div class="cat-description">
				{CATEGORY_DESCRIPTION}
			</div>
		# ENDIF #
	</header>
	
	# IF C_SUB_CATEGORIES #
	<div class="subcat-container">
		# START sub_categories_list #
		<div class="subcat-element" style="width:{CATS_COLUMNS_WIDTH}%;">
			<div class="subcat-content">
				# IF C_DISPLAY_CATS_ICON #
				# IF sub_categories_list.C_CATEGORY_IMAGE #<a itemprop="about" href="{sub_categories_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" /></a># ENDIF #
				<br />
				# ENDIF #
				<a itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
				<br />
				<span class="small">{sub_categories_list.ARTICLES_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_ARTICLE #${TextHelper::lowercase_first(LangLoader::get_message('articles', 'common', 'articles'))}# ELSE #${TextHelper::lowercase_first(LangLoader::get_message('article', 'common', 'articles'))}# ENDIF #</span>
			</div>
		</div>
		# END sub_categories_list #
		<div class="spacer"></div>
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
		# IF C_ARTICLES_FILTERS #
			# INCLUDE FORM #
		# ENDIF #
		<div class="spacer">&nbsp;</div>
			# START articles #
				<article # IF C_MOSAIC #class="small-block"# ENDIF # itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
					<header>
						<h1>
							<a itemprop="url" href="{articles.U_ARTICLE}"><span itemprop="name">{articles.TITLE}</span></a>
							<span class="actions">
								# IF articles.C_EDIT #
									<a href="{articles.U_EDIT_ARTICLE}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
								# ENDIF #
								# IF articles.C_DELETE #
									<a href="{articles.U_DELETE_ARTICLE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
								# ENDIF #
							</span>
						</h1>
						
						<div class="more">
							${LangLoader::get_message('by', 'common')}
							# IF articles.C_AUTHOR_DISPLAYED #
								# IF articles.C_AUTHOR_EXIST #<a itemprop="author" href="{articles.U_AUTHOR}" class="{articles.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{articles.USER_GROUP_COLOR}"# ENDIF #>{articles.PSEUDO}</a># ELSE #{articles.PSEUDO}# ENDIF #,
							# ENDIF # 
							${TextHelper::lowercase_first(LangLoader::get_message('the', 'common'))} <time datetime="{articles.DATE_ISO8601}" itemprop="datePublished">{articles.DATE}</time> 
							${TextHelper::lowercase_first(LangLoader::get_message('in', 'common'))} <a itemprop="about" href="{articles.U_CATEGORY}">{articles.CATEGORY_NAME}</a>
						</div>
						
						<meta itemprop="url" content="{articles.U_ARTICLE}">
						<meta itemprop="description" content="${escape(articles.DESCRIPTION)}"/>
						<meta itemprop="discussionUrl" content="{articles.U_COMMENTS}">
						<meta itemprop="interactionCount" content="{articles.NUMBER_COMMENTS} UserComments">
						
					</header>

					<div class="content">
						# IF articles.C_HAS_PICTURE #<img itemprop="thumbnailUrl" src="{articles.PICTURE}" alt="{articles.TITLE}" class="left"/># ENDIF #
						<span itemprop="text">{articles.DESCRIPTION}# IF articles.C_READ_MORE #... <a href="{articles.U_ARTICLE}" class="read-more">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #</span>
					</div>

					# IF articles.C_SOURCES #
					<div class="spacer"></div>
					<aside>
					<div id="articles-sources-container">
						<span>${LangLoader::get_message('form.sources', 'common')}</span> :
						# START articles.sources #
						<a itemprop="isBasedOnUrl" href="{articles.sources.URL}" class="small">{articles.sources.NAME}</a># IF articles.sources.C_SEPARATOR #, # ENDIF #
						# END articles.sources #
					</div>
					</aside>
					# ENDIF #
					
					<footer></footer>
				</article>
			# END articles #
	# ENDIF #
		<div class="spacer">&nbsp;</div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>