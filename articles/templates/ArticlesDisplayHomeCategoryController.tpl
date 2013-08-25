<script type="text/javascript">
<!--
	function Confirm_del_article() {
	return confirm("${i18n('articles.form.alert_delete_article')}");
	}
-->
</script>
# IF C_MODERATE #
<div class="module_actions">
    <ul class="nav">
	<li id="options">
	    <a><span class="options"></span><span class="caret"></span></a>
	    <ul class="subnav">
		# IF IS_ADMIN #
		<li>
			<a href="{U_EDIT_CONFIG}" title="${i18n('articles_configuration')}"><img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/edit_white.png" alt="${i18n('articles_configuration')}" /></a>
		</li>
		# ENDIF #
		# IF C_ADD #
		<li>
			<a href="{U_ADD_ARTICLES}" title="${i18n('articles.add')}"><img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/add.png" alt="${i18n('articles.add')}" /></a>
		</li>
		# ENDIF #
		# IF C_PENDING_ARTICLES #
		<li>
			<a href="{U_PENDING_ARTICLES}" title="${i18n('articles.pending_articles')}"><img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/pending.png" alt="${i18n('articles.pending_articles')}" /></a>
		</li>
		# ENDIF #
	    </ul>
	</li>
    </ul>
</div>
# ENDIF #
<div class="spacer"></div>
	
<div class="module_position">	
	<div class="module_top">
		<div class="module_top_title">
			<a href="{U_SYNDICATION}" title="Rss" class="img_link">
				<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Rss" title="Rss" />
			</a>
			${i18n('articles')}
		</div>
	</div>
	<div class="module_contents">
		<div class="cat">
		    <div class="cat_tool">
			    # IF C_MODERATE # 
			    <a href="{U_MANAGE_CATEGORIES}" title="${i18n('admin.categories.manage')}"><img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/edit.png" alt="${i18n('admin.categories.manage')}" /></a>
			    # ENDIF #
		    </div>
		    # IF C_ARTICLES_CAT #
		    <div style="margin-bottom:36px;">
			${i18n('articles.sub_categories')} :
			<br /><br />
			<ul style="list-style:none;">
			    # IF C_CURRENT_CAT #
			    <li style="float:left;"><a class="button_read_more" href="">{ID_CAT}</a></li>
			    # ENDIF #
			    # START cat_list #
			    <li style="float:left;margin:0 5px 0 5px"><a style="display:inline-block;" class="button_cat" href="{cat_list.U_CATEGORY}">{cat_list.CATEGORY_NAME}</a></li>
			    # END cat_list #
			</ul>   
		    </div>
		    # ENDIF #
		</div>
		<div class="spacer">&nbsp;</div>
		<hr />					
		# IF C_ARTICLES_FILTERS #
		<div style="float:right;width:300px;height:40px;">
			# INCLUDE FORM #
		</div>
		# ENDIF #
		<div class="spacer">&nbsp;</div>
		<div class="articles_container">
		# IF C_MOSAIC #
			<section class="article_content">
				# START articles #
				<article>
					<div class="article_tools">
						# IF articles.C_EDIT #
						<a style="text-decoration:none;" href="{articles.U_EDIT_ARTICLE}">
							<img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/edit.png" alt="${i18n('articles.edit')}" title="${i18n('articles.edit')}" />
						</a>
						# ENDIF #
						# IF articles.C_DELETE #
						<a href="{articles.U_DELETE_ARTICLE}" onclick="return Confirm_del_article();">
						    <img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/delete.png" alt="${i18n('articles.delete')}" title="${i18n('articles.delete')}" />
						</a>
						# ENDIF #
					</div>
					<header>
						<div>
							# IF articles.C_NOTATION_ENABLED #
							{articles.NOTE}
							# ENDIF #
						</div>
						<h3 title="{articles.TITLE}">{articles.TITLE}</h3>
						<span>
							# IF articles.C_AUTHOR_DISPLAYED #
							<img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/author.png" alt="${i18n('articles.sort_field.author')}" title="${i18n('articles.sort_field.author')}" />&nbsp;<a href="{articles.U_AUTHOR}" class="small_link {articles.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{articles.USER_GROUP_COLOR}"# ENDIF #>{articles.PSEUDO}</a>
							# ELSE #
							&nbsp;
							# ENDIF #
							<br />
							<img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/calendar.png" alt="${i18n('articles.sort_field.date')}" title="${i18n('articles.sort_field.date')}" />&nbsp;{articles.DATE}&nbsp;|
							<img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/view.png" alt="${i18n('articles.sort_field.views')}" title="${i18n('articles.sort_field.views')}" />&nbsp;{articles.NUMBER_VIEW}
							<br />
							# IF C_COMMENTS_ENABLED #
							<img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/comment.png" /><a class="small_link" href="{articles.U_COMMENTS}">&nbsp;{articles.L_COMMENTS}</a>
							# ELSE #
							&nbsp;
							# ENDIF #
						</span>
					</header>
					<figure>
					    # IF articles.C_HAS_PICTURE #
					    <div class="img_container">
						    <img src="{articles.PICTURE}" class="valign_middle" alt="{articles.TITLE}" />
					    </div>
					    # ENDIF #
					</figure>
					<div class="description">{articles.DESCRIPTION}</div>
					<a href="{articles.U_ARTICLE}" class="button_read_more">${i18n('articles.read_more')}</a>
					# IF articles.C_KEYWORDS #
					<div class="article_tags">
						<img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/tags.png" alt="${i18n('articles.tags')}" title="${i18n('articles.tags')}" /> : 
						{articles.U_KEYWORDS_LIST}	
					</div>
					# ENDIF #
				</article>
				# END articles #
			</section>
		# ELSE #
			# START articles #
			<section class="article_content_list_view">
				<article>
					<div class="article_tools">
						# IF articles.C_EDIT #
						<a style="text-decoration:none;" href="{articles.U_EDIT_ARTICLE}">
							<img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/edit.png" alt="${i18n('articles.edit')}" title="${i18n('articles.edit')}" />
						</a>
						# ENDIF #
						# IF articles.C_DELETE #
						<a href="{articles.U_DELETE_ARTICLE}" onclick="return Confirm_del_article();">
						    <img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/delete.png" alt="${i18n('articles.delete')}" title="${i18n('articles.delete')}" />
						</a>
						# ENDIF #
					</div>
					<div>
						# IF articles.C_NOTATION_ENABLED #
						{articles.NOTE}
						# ELSE #
						&nbsp;
						# ENDIF #
					</div>
					<figure>
					    # IF articles.C_HAS_PICTURE #
					    <div class="img_container">
						    <img src="{articles.PICTURE}" class="valign_middle" alt="{articles.TITLE}" />
					    </div>
					    # ENDIF #
					</figure>
					<header>
						<h3 title="{articles.TITLE}">{articles.TITLE}</h3>
						<span>
							# IF articles.C_AUTHOR_DISPLAYED #
							<img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/author.png" alt="${i18n('articles.sort_field.author')}" title="${i18n('articles.sort_field.author')}" /><a href="{articles.U_AUTHOR}" class="small_link {articles.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{articles.USER_GROUP_COLOR}"# ENDIF #>&nbsp;{articles.PSEUDO}&nbsp;</a>|
							# ENDIF #
							<img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/calendar.png" alt="${i18n('articles.sort_field.date')}" title="${i18n('articles.sort_field.date')}" />&nbsp;{articles.DATE}&nbsp;|
							<img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/view.png" alt="${i18n('articles.sort_field.views')}" title="${i18n('articles.sort_field.views')}" />&nbsp;{articles.NUMBER_VIEW}
							# IF C_COMMENTS_ENABLED #
							&nbsp;|&nbsp;<img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/comment.png" /><a class="small_link" href="{articles.U_COMMENTS}">&nbsp;{articles.L_COMMENTS}</a>
							# ENDIF #
						</span>
					</header>
					<div class="description">{articles.DESCRIPTION}</div>
					<a style="margin-left:10px;" href="{articles.U_ARTICLE}" class="button_read_more">${i18n('articles.read_more')}</a>
					# IF articles.C_KEYWORDS #
					<div class="article_tags">
						<img class="valign_middle" src="{PATH_TO_ROOT}/articles/templates/images/tags.png" alt="${i18n('articles.tags')}" title="${i18n('articles.tags')}" /> : 
						{articles.U_KEYWORDS_LIST}
					</div>
					# ENDIF #
				</article>
			</section>
			# END articles #
		# ENDIF #
		</div>
		{PAGINATION}
		<div class="spacer">&nbsp;</div>
		<p style="text-align:center;padding-top:10px;" class="text_small">
			{L_NO_ARTICLE} {L_TOTAL_ARTICLES}
		</p>
		&nbsp;
	</div>
	<div class="module_bottom text_strong">
		<a href="../articles/{SID}">${i18n('articles')}</a>
	</div>
</div>
