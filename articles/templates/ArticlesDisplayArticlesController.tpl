# INCLUDE MSG #
<article itemscope="itemscope" itemtype="http://schema.org/Article">
	<header>
		<h1>
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'main')}" class="icon-syndication"></a>
			<span id="name" itemprop="name">{TITLE}</span>
			<span class="actions">
				# IF C_EDIT #
					<a href="{U_EDIT_ARTICLE_PAGE}" title="${i18n('articles.edit')}" class="icon-pencil"></a>
				# ENDIF #
				# IF C_DELETE #
					<a href="{U_DELETE_ARTICLE}" title="${i18n('articles.delete')}" class="icon-delete" data-confirmation="delete-element"></a>
				# ENDIF #
					<a href="{U_PRINT_ARTICLE}" title="${LangLoader::get_message('printable_version', 'main')}" target="blank" class="icon-print"></a>
			</span>
		</h1>
		
		<div class="more">
			# IF C_AUTHOR_DISPLAYED #
			<i class="icon-user" title="{@articles.sort_field.author}"></i><a itemprop="author" href="{U_AUTHOR}" class="small {USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}"# ENDIF #>&nbsp;{PSEUDO}&nbsp;</a>|&nbsp;
			# ENDIF #
			<i class="icon-calendar" title="{@articles.sort_field.date}"></i>&nbsp;<time datetime="{DATE_ISO8601}" itemprop="datePublished">{DATE}</time>&nbsp;|
			&nbsp;<i class="icon-eye" title="{@articles.sort_field.views}"></i>&nbsp;{NUMBER_VIEW}
			# IF C_COMMENTS_ENABLED #
				&nbsp;|&nbsp;<i class="icon-comment"></i><a itemprop="discussionUrl" class="small" href="{U_COMMENTS}">&nbsp;{L_COMMENTS}</a>
			# ENDIF #
			&nbsp;|&nbsp;<i class="icon-folder" title="{@articles.category}"></i>&nbsp;<a itemprop="about" class="small" href="{U_CATEGORY}">{L_CAT_NAME}</a>
			# IF C_KEYWORDS #
			&nbsp;|&nbsp;<i title="{@articles.tags}" class="icon-tags"></i> 
				# START keywords #
					<a itemprop="keywords" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
				# END keywords #
			# ENDIF #
		</div>
		
		<meta itemprop="url" content="{U_ARTICLE}">
		<meta itemprop="description" content="{DESCRIPTION}">
		<meta itemprop="datePublished" content="{DATE_ISO8601}">
		<meta itemprop="discussionUrl" content="{U_COMMENTS}">
		# IF C_HAS_PICTURE #<meta itemprop="thumbnailUrl" content="{PICTURE}"># ENDIF #
		<meta itemprop="interactionCount" content="{NUMBER_COMMENTS} UserComments">
	</header>
	<div class="content">
		# IF C_PAGINATION #
			# INCLUDE FORM #
			<div class="spacer">&nbsp;</div>
		# ENDIF #
		# IF PAGE_NAME #
			<h2 class="title page_name">{PAGE_NAME}</h2>
		# ENDIF #
		<span itemprop="text">{CONTENTS}</span>
		<div class="spacer" style="margin-top:35px;">&nbsp;</div>
		<hr />
		# IF C_PAGINATION #
			<div class="pages_pagination right">
				# IF C_NEXT_PAGE #
				<a style="text-decoration:none;" href="{U_NEXT_PAGE}">{L_NEXT_TITLE} <i class="icon-arrow-right"></i></a>
				# ELSE #
				&nbsp;
				# ENDIF #
			</div>
			<div class="pages_pagination center"># INCLUDE PAGINATION_ARTICLES #</div>
			<div class="pages_pagination">
				# IF C_PREVIOUS_PAGE #
				<a style="text-decoration:none;" href="{U_PREVIOUS_PAGE}"><i class="icon-arrow-left"></i> {L_PREVIOUS_TITLE}</a>
				# ENDIF #
			</div>
		# ENDIF #
		<div class="spacer">&nbsp;</div>
	</div>
	<aside>
		# IF C_SOURCES #
		<div id="articles_sources_container">
			<span class="articles_more_title">{@articles.sources}</span> :
			# START sources #
			<a itemprop="isBasedOnUrl" href="{sources.URL}" class="small">{sources.NAME}</a># IF sources.C_SEPARATOR #, # ENDIF #
			# END sources #
		</div>
		# ENDIF #
		# IF C_DATE_UPDATED #
		<div><i>{@articles.date_updated}<time datetime="{DATE_UPDATED_ISO8601}" itemprop="datePublished">{DATE_UPDATED}</time></i></div>
		# ENDIF #
		<div class="spacer">&nbsp;</div>
		# IF C_NOTATION_ENABLED #
		<div style="float:left" class="smaller">
			{KERNEL_NOTATION}
		</div>
		# ENDIF #
		<div class="spacer"></div>
		# IF C_COMMENTS_ENABLED #
			# INCLUDE COMMENTS #
		# ENDIF #
	</aside>
	<footer></footer>
</article>