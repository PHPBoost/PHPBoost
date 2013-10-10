<menu class="dynamic_menu right">
	<ul>
		<li><a><i class="icon-cog"></i></a>
			<ul>
				# IF IS_ADMIN #
				<li>
					<a href="${relative_url(ArticlesUrlBuilder::articles_configuration())}" title="${i18n('articles_configuration')}"><i class="icon-pencil"></i></a>
				</li>
				# ENDIF #
				# IF C_ADD #
				<li>
					<a href="{U_ADD_ARTICLES}" title="${i18n('articles.add')}"><i class="icon-plus-sign"></i></a>
				</li>
				# ENDIF #
				# IF C_PENDING_ARTICLES #
				<li>
					<a href="${relative_url(ArticlesUrlBuilder::display_pending_articles())}" title="${i18n('articles.pending_articles')}"><i class="icon-time"></i></a>
				</li>
				# ENDIF #
				# IF C_PUBLISHED_ARTICLES #
				<li>
					<a href="${relative_url(ArticlesUrlBuilder::home())}" title="${i18n('articles.published_articles')}"><i class="icon-book"></i></a>
				</li>
				# ENDIF #
			</ul>
		</li>
	</ul>
</menu>

<div class="spacer"></div>
	
<section>	
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('articles'))}" class="syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
			${i18n('articles')}
		</h1>
		# IF C_ARTICLES_CAT #
		<div class="cat">
		    <div class="cat_tool">
			    # IF C_MODERATE # 
			    <a href="${relative_url(ArticlesUrlBuilder::manage_categories())}" title="${i18n('admin.categories.manage')}" class="edit"></a>
			    # ENDIF #
		    </div>
		    <div style="margin-bottom:36px;">
			${i18n('articles.sub_categories')} :
			<br /><br />
			<ul style="list-style:none;">
			    # IF C_CURRENT_CAT #
			    <li style="float:left;"><a class="button_read_more" href="">{ID_CAT}</a></li>
			    # ENDIF #
			    # START cat_list #
			    <li style="float:left;margin:0 5px 0 5px"><a itemprop="about" style="display:inline-block;" class="button_cat" href="{cat_list.U_CATEGORY}">{cat_list.CATEGORY_NAME}</a></li>
			    # END cat_list #
			</ul>   
		    </div>
		</div>
		<div class="spacer">&nbsp;</div>
		<hr />
		# ENDIF #
	</header>
	# IF C_NO_ARTICLE_AVAILABLE #
	<div class="center">
		${i18n('articles.no_article.category')}
	</div>
	# ELSE #
		# IF C_ARTICLES_FILTERS #
		<div class="article_filters">
			# INCLUDE FORM #
		</div>
		# ENDIF #
		<div class="spacer">&nbsp;</div>
		# IF C_MOSAIC #
			<section class="article_content">
				# START articles #
				<article itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				    <header>
					<div class="article_tools">
						# IF articles.C_EDIT #
						<a href="{articles.U_EDIT_ARTICLE}" title="${i18n('articles.edit')}" class="edit"></a>
						# ENDIF #
						# IF articles.C_DELETE #
						<a href="{articles.U_DELETE_ARTICLE}" title="${i18n('articles.delete')}" class="delete"></a>
						# ENDIF #
					</div>				
					<div>
						# IF articles.C_NOTATION_ENABLED #
						{articles.NOTE}
						# ENDIF #
					</div>
				    </header>
				    <aside>
						<h3 title="{articles.TITLE}" itemprop="name">{articles.TITLE}</h3>
						<span>
							# IF articles.C_AUTHOR_DISPLAYED #
							<i class="icon-user" title="${i18n('articles.sort_field.author')}"></i>&nbsp;<a itemprop="author" href="{articles.U_AUTHOR}" class="small {articles.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{articles.USER_GROUP_COLOR}"# ENDIF #>{articles.PSEUDO}</a>
							# ELSE #
							&nbsp;
							# ENDIF #
							<br />
							<i class="icon-calendar" title="${i18n('articles.sort_field.date')}"></i>&nbsp;<time datetime="{articles.DATE_ISO8601}" itemprop="datePublished">{articles.DATE}</time>&nbsp;|
							<i class="icon-eye-open" title="${i18n('articles.sort_field.views')}"></i>&nbsp;{articles.NUMBER_VIEW}
							<br />
							# IF C_COMMENTS_ENABLED #
							<i class="icon-comment"></i><a itemprop="discussionUrl" class="small" href="{articles.U_COMMENTS}">&nbsp;{articles.L_COMMENTS}</a>
							# ELSE #
							&nbsp;
							# ENDIF #
						</span>
					 </aside>
					<figure>
					    # IF articles.C_HAS_PICTURE #
					    <div class="img_container">
						    <img itemprop="thumbnailUrl" src="{articles.PICTURE}" class="valign_middle" alt="{articles.TITLE}" />
					    </div>
					    # ENDIF #
					</figure>
				   
					    
					<meta itemprop="url" content="{articles.U_ARTICLE}">
					<meta itemprop="description" content="{articles.DESCRIPTION}">
					<meta itemprop="datePublished" content="{articles.DATE_ISO8601}">
					<meta itemprop="discussionUrl" content="{articles.U_COMMENTS}">
					# IF C_HAS_PICTURE #<meta itemprop="thumbnailUrl" content="{articles.PICTURE}"># ENDIF #
					<meta itemprop="interactionCount" content="{articles.NUMBER_COMMENTS} UserComments">
						
					<div itemprop="description" class="description">{articles.SHORT_DESCRIPTION}</div>
					<a itemprop="url" href="{articles.U_ARTICLE}" class="button_read_more">${i18n('articles.read_more')}</a>
					# IF articles.C_KEYWORDS #
					<div itemprop="url" class="article_tags">
						<i title="${i18n('articles.tags')}" class="icon-tags"></i> : 
						{articles.U_KEYWORDS_LIST}	
					</div>
					# ENDIF #
					<footer></footer>
				</article>
				# END articles #
			</section>
		# ELSE #
			# START articles #
			<section class="article_content_list_view">
				<article itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				    <header>
					<div class="article_tools">
						# IF articles.C_EDIT #
						<a style="text-decoration:none;" href="{articles.U_EDIT_ARTICLE}" title="${i18n('articles.edit')}" class="edit"></a>
						# ENDIF #
						# IF articles.C_DELETE #
						<a href="{articles.U_DELETE_ARTICLE}" title="${i18n('articles.delete')}" class="delete"></a>
						# ENDIF #
					</div>
					<div>
						# IF articles.C_NOTATION_ENABLED #
						{articles.NOTE}
						# ELSE #
						&nbsp;
						# ENDIF #
					</div>
				    </header>
				    
					<figure>
					    # IF articles.C_HAS_PICTURE #
					    <div class="img_container">
						    <img itemprop="thumbnailUrl" src="{articles.PICTURE}" class="valign_middle" alt="{articles.TITLE}" />
					    </div>
					    # ENDIF #
					</figure>
				    <aside>
						<h3 itemprop="name" title="{articles.TITLE}">{articles.TITLE}</h3>
						<span>
							# IF articles.C_AUTHOR_DISPLAYED #
							<i class="icon-user" title="${i18n('articles.sort_field.author')}"></i><a itemprop="author" href="{articles.U_AUTHOR}" class="small {articles.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{articles.USER_GROUP_COLOR}"# ENDIF #>&nbsp;{articles.PSEUDO}&nbsp;</a>|
							# ENDIF #
							<i class="icon-calendar" title="${i18n('articles.sort_field.date')}"></i>&nbsp;<time datetime="{articles.DATE_ISO8601}" itemprop="datePublished">{articles.DATE}</time>&nbsp;|
							<i class="icon-eye-open" title="${i18n('articles.sort_field.views')}"></i>&nbsp;{articles.NUMBER_VIEW}
							# IF C_COMMENTS_ENABLED #
							&nbsp;|&nbsp;<i class="icon-comment"></i><a itemprop="discussionUrl" class="small" href="{articles.U_COMMENTS}">&nbsp;{articles.L_COMMENTS}</a>
							# ENDIF #
						</span>
				   </aside>
					<meta itemprop="url" content="{articles.U_ARTICLE}">
					<meta itemprop="description" content="{articles.DESCRIPTION}">
					<meta itemprop="datePublished" content="{articles.DATE_ISO8601}">
					<meta itemprop="discussionUrl" content="{articles.U_COMMENTS}">
					# IF C_HAS_PICTURE #<meta itemprop="thumbnailUrl" content="{articles.PICTURE}"># ENDIF #
					<meta itemprop="interactionCount" content="{articles.L_COMMENTS} UserComments">
					
					<div itemprop="description" class="description">{articles.SHORT_DESCRIPTION}</div>
					<a itemprop="url" style="margin-left:10px;" href="{articles.U_ARTICLE}" class="button_read_more">${i18n('articles.read_more')}</a>
					# IF articles.C_KEYWORDS #
					<div itemprop="keywords" class="article_tags">
						<i title="${i18n('articles.tags')}" class="icon-tags"></i> : 
						{articles.U_KEYWORDS_LIST}
					</div>
					# ENDIF #
					<footer></footer>
				</article>
			</section>
			# END articles #
		# ENDIF #
	# ENDIF #	
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
