<script type="text/javascript">
<!--
	function Confirm_del_article() {
	return confirm("{L_ALERT_DELETE_ARTICLE}");
	}
-->
</script>

<div class="module_actions">
    <ul class="nav">
	<li id="options">
	    <a><span class="options"></span><span class="caret"></span></a>
	    <ul class="subnav">
		# IF IS_ADMIN #
		<li>
			<a href="{U_EDIT_CONFIG}" title="{L_EDIT_CONFIG}"><img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/edit_white.png" alt="{L_EDIT_CONFIG}" /></a>
		</li>
		# ENDIF #
		# IF C_ADD #
		<li>
			<a href="{U_ADD_ARTICLES}" title="{L_ADD_ARTICLES}"><img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/add.png" alt="{L_ADD_ARTICLES}" /></a>
		</li>
		# ENDIF #
		<li>
			<a href="{U_PUBLISHED_ARTICLES}" title="{L_PUBLISHED_ARTICLES}"><img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/published.png" alt="{L_PUBLISHED_ARTICLES}" /></a>
		</li>
	    </ul>
	</li>
    </ul>
</div>
<div class="spacer"></div>
	
<div class="module_position">	
	<div class="module_top_l"></div>		
	<div class="module_top_r"></div>
	<div class="module_top">
		<div class="module_top_title">
			<a href="{U_SYNDICATION}" title="Rss" class="img_link">
				<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Rss" title="Rss" />
			</a>
			{L_MODULE_NAME}
		</div>
	</div>
	<div class="module_contents">				
		# IF C_ARTICLES_FILTERS #
		<div style="float:right;width:300px;height:40px;">
			# INCLUDE FORM #
		</div>
		# ENDIF #
		<div class="spacer">&nbsp;</div>
		<div class="articles_container">		
		    # START articles #
		    <section class="article_content_list_view">
			    <article>
				    <div class="article_tools">
					    # IF articles.C_EDIT #
					    <a style="text-decoration:none;" href="{articles.U_EDIT_ARTICLE}">
						    <img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/edit.png" alt="{articles.L_EDIT_ARTICLE}" title="{articles.L_EDIT_ARTICLE}" />
					    </a>
					    # ENDIF #
					    # IF articles.C_DELETE #
					    <a href="{articles.U_DELETE_ARTICLE}" onclick="return Confirm_del_article();">
						<img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/delete.png" alt="{articles.L_DELETE_ARTICLE}" title="{articles.L_DELETE_ARTICLE}" />
					    </a>
					    # ENDIF #
				    </div>
				    <div>
					    # IF articles.C_NOTATION_ENABLED #
					    {articles.NOTE}
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
						    <img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/author.png" alt="{articles.L_AUTHOR}" title="{articles.L_AUTHOR}" /><a href="{articles.U_AUTHOR}" class="small_link {articles.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{articles.USER_GROUP_COLOR}"# ENDIF #>&nbsp;{articles.PSEUDO}&nbsp;</a>|
						    # ENDIF #
						    <img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/calendar.png" alt="{articles.L_DATE}" title="{articles.L_DATE}" />&nbsp;{articles.DATE}&nbsp;|
						    <img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/view.png" alt="{articles.L_VIEW}" title="{articles.L_VIEW}" />&nbsp;{articles.NUMBER_VIEW}
						    # IF C_COMMENTS_ENABLED #
						    &nbsp;|&nbsp;<img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/comment.png" /><a class="small_link" href="{articles.U_COMMENTS}">&nbsp;{articles.L_NUMBER_COM}</a>
						    # ENDIF #
						    &nbsp;|&nbsp;<img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/category.png" alt="{articles.L_CATEGORY}" title="{articles.L_CATEGORY}" />&nbsp;<a class="small_link" href="{articles.U_CATEGORY}">{articles.L_CAT_NAME}</a>
					    </span>
				    </header>
				    <div class="description">{articles.DESCRIPTION}</div>
				    <a style="margin-left:10px;" href="{articles.U_ARTICLE}" class="button_read_more">{articles.L_READ_MORE}</a>
				    # IF C_KEYWORDS #
				    <div class="article_tags">
					    <img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/tags.png" alt="{articles.L_TAGS}" title="{articles.L_TAGS}" /> : 
					    # START keywords #
					    {keywords.COMMA}<a href="{keywords.U_KEYWORD}" class="small_link">{keywords.NAME}</a>
					    # END keywords #
				    </div>
				    # ENDIF #
			    </article>
		    </section>
		    # END articles #
		</div>
		
		{PAGINATION}
		<div class="spacer">&nbsp;</div>
		<p style="text-align:center;padding-top:10px;" class="text_small">
			{L_NO_ARTICLE} {L_TOTAL_ARTICLES}
		</p>
		&nbsp;
	</div>
	<div class="module_bottom text_strong">
		<a href="../articles/{SID}">{L_MODULE_NAME}</a>
	</div>
</div>
