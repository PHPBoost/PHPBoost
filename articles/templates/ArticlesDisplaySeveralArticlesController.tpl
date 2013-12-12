<section>	
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('articles', ID_CAT))}" class="icon-syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
			${i18n('articles')}
		</h1>
		<div class="cat">
			<div style="margin-bottom:36px;">
			${i18n('articles.sub_categories')} :
			<br /><br />
			<ul style="list-style:none;">
				# IF C_CURRENT_CAT #
				<li style="float:left;"><a class="button_read_more" href="">{ID_CAT}</a></li>
				# ENDIF #
				# START cat_list #
				<li style="float:left;margin:0 5px 0 5px"><a itemprop="about" style="display:inline-block;" class="button_cat" href="{cat_list.U_CATEGORY}" title="{cat_list.CATEGORY_DESCRIPTION}">{cat_list.CATEGORY_NAME} ({cat_list.NBR_ARTICLES})</a></li>
				# END cat_list #
			</ul>
			</div>
		</div>
		<div class="spacer">&nbsp;</div>
		<hr />
	</header>
	# IF C_NO_ARTICLE_AVAILABLE #
	<div class="center">
		${i18n('articles.no_article.category')}
	</div>
	# ELSE #
		# IF C_ARTICLES_FILTERS #
			# INCLUDE FORM #
		# ENDIF #
		<div class="spacer">&nbsp;</div>
		# IF C_MOSAIC #
			<section class="content_mosaic">
				<article itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
					# START articles #
					<div class="box">
						<figure>
							<div class="article_tools">
								# IF articles.C_EDIT #
								<a href="{articles.U_EDIT_ARTICLE}" title="${i18n('articles.edit')}" class="icon-edit"></a>
								# ENDIF #
								# IF articles.C_DELETE #
								<a href="{articles.U_DELETE_ARTICLE}" title="${i18n('articles.delete')}" class="icon-delete" data-confirmation="delete-element"></a>
								# ENDIF #
							</div>
							# IF articles.C_HAS_PICTURE #
							<a itemprop="url" href="{articles.U_ARTICLE}"><img itemprop="thumbnailUrl" src="{articles.PICTURE}" width="261" height="214" alt="{articles.TITLE}" /></a>
							# ENDIF #
						</figure>
						<div class="article_details">
							# IF articles.C_AUTHOR_DISPLAYED #
							<i class="icon-user" title="${i18n('articles.sort_field.author')}"></i>&nbsp;<a itemprop="author" href="{articles.U_AUTHOR}" class="small {articles.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{articles.USER_GROUP_COLOR}"# ENDIF #>{articles.PSEUDO}</a>&nbsp;|
							# ELSE #
							&nbsp;
							# ENDIF #
							<i class="icon-calendar" title="${i18n('articles.sort_field.date')}"></i>&nbsp;<time datetime="{articles.DATE_ISO8601}" itemprop="datePublished">{articles.DATE}</time>&nbsp;|
							<i class="icon-eye" title="${i18n('articles.sort_field.views')}"></i>&nbsp;{articles.NUMBER_VIEW}
						</div>
						<h3 itemprop="name"><a itemprop="url" href="{articles.U_ARTICLE}">{articles.TITLE}</a></h3>
						<p itemprop="description" class="description">{articles.DESCRIPTION}</p>
						# IF C_KEYWORDS #
							# START keywords #
							<div class="tags">
								<a itemprop="keywords" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #&nbsp;# ENDIF #
							</div>
							# END keywords #
						# ENDIF #	
						<meta itemprop="url" content="{articles.U_ARTICLE}">
						<meta itemprop="description" content="{articles.DESCRIPTION}">
						<meta itemprop="datePublished" content="{articles.DATE_ISO8601}">
						<meta itemprop="discussionUrl" content="{articles.U_COMMENTS}">
						# IF C_HAS_PICTURE #<meta itemprop="thumbnailUrl" content="{articles.PICTURE}"># ENDIF #
						<meta itemprop="interactionCount" content="{articles.NUMBER_COMMENTS} UserComments">
						<footer>
							# IF C_COMMENTS_ENABLED #
							<div class="article_comment">
								<i class="icon-comment"></i><a itemprop="discussionUrl" class="small" href="{articles.U_COMMENTS}" title="{articles.L_COMMENTS}">&nbsp;{articles.NUMBER_COMMENTS}</a>
								# ELSE #
								&nbsp;
								# ENDIF #
							</div>
							<div class="notes">
								# IF articles.C_NOTATION_ENABLED #
								{articles.NOTE}
								# ENDIF #
							</div>
						</footer>
					</div>
					# END articles #
				</article>	
			</section>
		# ELSE #
			<section class="content_list">
				# START articles #
				<article itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
					<div class="box">
						<figure>
							
							# IF articles.C_HAS_PICTURE #
							<a itemprop="url" href="{articles.U_ARTICLE}"><img itemprop="thumbnailUrl" src="{articles.PICTURE}" width="261" height="214" alt="{articles.TITLE}" /></a>
							# ENDIF #
						</figure>
						<div class="title_description">
							<div class="article_tools">
								# IF articles.C_EDIT #
								<a href="{articles.U_EDIT_ARTICLE}" title="${i18n('articles.edit')}" class="icon-edit"></a>
								# ENDIF #
								# IF articles.C_DELETE #
								<a href="{articles.U_DELETE_ARTICLE}" title="${i18n('articles.delete')}" class="icon-delete" data-confirmation="delete-element"></a>
								# ENDIF #
							</div>
							<header>
								<h3 itemprop="name"><a itemprop="url" href="{articles.U_ARTICLE}">{articles.TITLE}</a></h3>
								<p itemprop="description" class="description">{articles.DESCRIPTION}</p>
							</header>
							# IF C_KEYWORDS #
								# START keywords #
								<div class="tags">
									<a itemprop="keywords" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #&nbsp;# ENDIF #
								</div>
								# END keywords #
							# ENDIF #
						</div>
						<meta itemprop="url" content="{articles.U_ARTICLE}">
						<meta itemprop="description" content="{articles.DESCRIPTION}">
						<meta itemprop="datePublished" content="{articles.DATE_ISO8601}">
						<meta itemprop="discussionUrl" content="{articles.U_COMMENTS}">
						# IF C_HAS_PICTURE #<meta itemprop="thumbnailUrl" content="{articles.PICTURE}"># ENDIF #
						<meta itemprop="interactionCount" content="{articles.NUMBER_COMMENTS} UserComments">
						<footer>	
							<div class="article_details">
								# IF articles.C_AUTHOR_DISPLAYED #
								<i class="icon-user" title="${i18n('articles.sort_field.author')}"></i>&nbsp;<a itemprop="author" href="{articles.U_AUTHOR}" class="small {articles.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{articles.USER_GROUP_COLOR}"# ENDIF #>{articles.PSEUDO}</a>&nbsp;|
								# ELSE #
								&nbsp;
								# ENDIF #
								<i class="icon-calendar" title="${i18n('articles.sort_field.date')}"></i>&nbsp;<time datetime="{articles.DATE_ISO8601}" itemprop="datePublished">{articles.DATE}</time>&nbsp;|
								<i class="icon-eye" title="${i18n('articles.sort_field.views')}"></i>&nbsp;{articles.NUMBER_VIEW}
							</div>
							<div class="article_comment">
								# IF C_COMMENTS_ENABLED #
								<i class="icon-comment"></i><a itemprop="discussionUrl" class="small" href="{articles.U_COMMENTS}">&nbsp;{articles.L_COMMENTS}</a>
								# ELSE #
								&nbsp;
								# ENDIF #
							</div>
							<div class="notes">
								# IF articles.C_NOTATION_ENABLED #
								{articles.NOTE}
								# ENDIF #
							</div>
						</footer>
					</div>
				</article>
				# END articles #
			</section>
		# ENDIF #
	# ENDIF #
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>