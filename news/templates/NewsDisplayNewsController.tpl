<article itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
	<header>
		<h1>
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'main')}" class="fa fa-syndication"></a>
			<span itemprop="name">{NAME}</span>
			<span class="actions">
				# IF C_EDIT #
					<a href="{U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
				# ENDIF #
				# IF C_DELETE #
					<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="delete-element"></a>
				# ENDIF #
			</span>
		</h1>
		
		<div class="more">
			# IF C_AUTHOR_DISPLAYED #
				${LangLoader::get_message('by', 'common')} 
				# IF C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="small {USER_LEVEL_CLASS}" href="{U_AUTHOR_PROFILE}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{PSEUDO}</a># ELSE #{PSEUDO}# ENDIF #,  
			# ENDIF #
			${TextHelper::lowercase_first(LangLoader::get_message('the', 'common'))} <time datetime="{DATE_ISO8601}" itemprop="datePublished">{DATE}</time>
			${TextHelper::lowercase_first(LangLoader::get_message('in', 'common'))} <a itemprop="about" href="{U_CATEGORY}">{CATEGORY_NAME}</a>
			- # IF C_COMMENTS # {NUMBER_COMMENTS} # ENDIF #	{L_COMMENTS}
		</div>
		
		<meta itemprop="url" content="{U_LINK}">
		<meta itemprop="description" content="${escape(DESCRIPTION)}" />
		<meta itemprop="discussionUrl" content="{U_COMMENTS}">
		<meta itemprop="interactionCount" content="{NUMBER_COMMENTS} UserComments">
		
	</header>
	<div class="content">
		# IF C_PICTURE #<img itemprop="thumbnailUrl" src="{U_PICTURE}" alt="{NAME}" title="{NAME}" class="right" /># ENDIF #
		
		<span itemprop="text">{CONTENTS}</span>
	</div>
	<aside>
		# IF C_SOURCES #
		<div id="news-sources-container">
			<span>${LangLoader::get_message('form.sources', 'common')}</span> :
			# START sources #
			<a itemprop="isBasedOnUrl" href="{sources.URL}" class="small">{sources.NAME}</a># IF sources.C_SEPARATOR #, # ENDIF #
			# END sources #
		</div>
		# ENDIF #

		# IF C_KEYWORDS #
		<div id="news-tags-container">
			<span>${LangLoader::get_message('form.keywords', 'common')}</span> :
				# START keywords #
					<a itemprop="keywords" rel="tag" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
				# END keywords #
		</div>
		# ENDIF #
		
		# IF C_SUGGESTED_NEWS #
			<div id="news-suggested-container">
				<span>${LangLoader::get_message('suggestions', 'common')} :</span>
				<ul>
					# START suggested #
					<li><a href="{suggested.URL}">{suggested.NAME}</a></li>
					# END suggested #
				</ul>
			</div>
		# ENDIF #
		
		<hr class="news-separator">
		
		# IF C_NEWS_NAVIGATION_LINKS #
		<div class="navigation-link">
			# IF C_PREVIOUS_NEWS #
			<span style="float:left">
				<a href="{U_PREVIOUS_NEWS}"><i class="fa fa-arrow-left fa-2x"></i></a>
				<a href="{U_PREVIOUS_NEWS}">{PREVIOUS_NEWS}</a>
			</span>
			# ENDIF #
			# IF C_NEXT_NEWS #
			<span style="float:right">
				<a href="{U_NEXT_NEWS}">{NEXT_NEWS}</a>
				<a href="{U_NEXT_NEWS}"><i class="fa fa-arrow-right fa-2x"></i></a>
			</span>
			# ENDIF #
			<div class="spacer"></div>
		</div>
		# ENDIF #
	
		# INCLUDE COMMENTS #
	</aside>
	<footer></footer>
</article>