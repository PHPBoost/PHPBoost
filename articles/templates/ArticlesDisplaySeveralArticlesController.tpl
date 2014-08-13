<section>
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('articles', ID_CAT))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'common')}"></a>
			${i18n('articles')}
		</h1>
		# IF C_ARTICLES_CAT #
		<div class="cat">
			<div class="subcat">
				${i18n('articles.sub_categories')} :
				<br /><br />
				# IF C_DISPLAY_CATS_ICON #
					# START cat_list #
					<div style="float:left;text-align:center;width:{COLUMN_WIDTH_CAT}%;margin-bottom:20px;">
					<a itemprop="about" href="{cat_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="../{cat_list.CATEGORY_IMAGE}" alt="{cat_list.CATEGORY_NAME}" /></a><br />
					<a itemprop="about" href="{cat_list.U_CATEGORY}">{cat_list.CATEGORY_NAME} ({cat_list.NBR_ARTICLES})</a>
					<br />
					<span class="small">{cat_list.CATEGORY_DESCRIPTION}</span>
					</div>
					# END cat_list #
				# ELSE #
				<ul>
					# START cat_list #
					<li><a itemprop="about" class="button_cat" href="{cat_list.U_CATEGORY}" title="{cat_list.CATEGORY_DESCRIPTION}">{cat_list.CATEGORY_NAME} ({cat_list.NBR_ARTICLES})</a></li>
					# END cat_list #
				</ul>
				# ENDIF #
			</div>
		</div>
		<div class="spacer">&nbsp;</div>
		<hr />
		# ENDIF #
	</header>
	# IF C_NO_ARTICLE_AVAILABLE #
	<div class="center">
		${LangLoader::get_message('no_item_now', 'main')}
	</div>
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
									<a href="{articles.U_EDIT_ARTICLE}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
								# ENDIF #
								# IF articles.C_DELETE #
									<a href="{articles.U_DELETE_ARTICLE}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
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
						<span itemprop="text">{articles.DESCRIPTION}</span>
					</div>

					<footer></footer>
				</article>
			# END articles #
	# ENDIF #
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>