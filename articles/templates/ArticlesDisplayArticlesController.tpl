<script type="text/javascript">
<!--
	function Confirm_del_article() {
		return confirm("{L_ALERT_DELETE_ARTICLE}");
	}
-->
</script>
# INCLUDE MSG #

<div class="module_actions">
    <ul class="nav">
	<li id="options">
	    <a><span class="options"></span><span class="caret"></span></a>
	    <ul class="subnav">
		# IF C_EDIT #
		<li>
			<a href="{U_EDIT_ARTICLE}" title="{L_EDIT_ARTICLE}"><img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/edit_white.png" alt="{L_EDIT_ARTICLE}" /></a>
		</li>
		# ENDIF #
		# IF C_DELETE #
		<li>
			<a href="{U_DELETE_ARTICLE}" title="{L_DELETE_ARTICLE}"><img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/delete.png" alt="{L_DELETE_ARTICLE}" /></a>
		</li>
		# ENDIF #
		<li>
			<a href="{U_PRINT_ARTICLE}" title="{L_PRINTABLE_VERSION}"><img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/print.png" alt="{L_PRINTABLE_VERSION}" /></a>
		</li>
	    </ul>
	</li>
    </ul>
</div>
<div class="spacer"></div>

<div class="module_position">					
	<div class="article_top">
		<div class="article_top_title">
			<a href="{U_SYNDICATION}" title="Rss" class="img_link">
				<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Rss" title="Rss" />
			</a>
			{TITLE}
		</div>
		<div class="article_info">
			# IF C_AUTHOR_DISPLAYED #
			<img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/author.png" alt="{L_AUTHOR}" title="{L_AUTHOR}" /><a href="{U_AUTHOR}" class="small_link {USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}"# ENDIF #>&nbsp;{PSEUDO}&nbsp;</a>|
			# ENDIF #
			&nbsp;<img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/calendar.png" alt="{L_DATE}" title="{L_DATE}" />&nbsp;{DATE}&nbsp;|
			&nbsp;<img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/view.png" alt="{L_VIEW}" title="{L_VIEW}" />&nbsp;{NUMBER_VIEW}
			# IF C_COMMENTS_ENABLED #
				&nbsp;|&nbsp;<img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/comment.png" /><a class="small_link" href="{U_COMMENTS}">&nbsp;{L_COMMENTS}</a>
			# ENDIF #
			&nbsp;|&nbsp;<img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/category.png" alt="{L_CATEGORY}" title="{L_CATEGORY}" />&nbsp;<a class="small_link" href="{U_CATEGORY}">{L_CAT_NAME}</a>
			# IF C_KEYWORDS #
			&nbsp;|&nbsp;<img class="valign_middle" src="{PATH_TO_ROOT}/{L_MODULE_NAME}/templates/images/tags.png" alt="{L_TAGS}" title="{L_TAGS}" /> 
				# START keywords #
				{keywords.COMMA}<a href="{keywords.U_KEYWORD}" class="small_link">{keywords.NAME}</a>
				# END keywords #
			# ENDIF #
		</div>
	</div>
	<div class="spacer"></div>
	
	<div class="module_contents">
			# IF PAGINATION_ARTICLES #
			<div style="float:right;margin-right:35px;width:250px;">
				<p class="row2 text_strong" style="padding:2px;text-indent:4px;">{L_SUMMARY}:</p>
				# INCLUDE FORM #	
			</div>
			<div class="spacer">&nbsp;</div>
			# ENDIF #					
			# IF PAGE_NAME #
			<h2 class="title" style="text-indent:35px;">{PAGE_NAME}</h2>
			# ENDIF #	
			{CONTENTS}
			<div class="spacer" style="margin-top:35px;">&nbsp;</div>
			# IF PAGINATION_ARTICLES #
			<div style="float:left;width:33%;text-align:right"><a href="{PAGE_PREVIOUS_ARTICLES}">&nbsp;&leftarrow;{L_PREVIOUS_TITLE}</a></div>
			<div style="float:left;width:33%" class="text_center">{PAGINATION_ARTICLES}</div>
			<div style="float:left;width:33%;"><a href="{PAGE_NEXT_ARTICLES}">{L_NEXT_TITLE}&nbsp;&rightarrow;</a></div>
			# ENDIF #		
		<div class="spacer">&nbsp;</div>
	</div>
	<div class="module_bottom">
		# IF C_SOURCES #
		<div><b> {L_SOURCE} : </b># START sources #{sources.COMMA}<a href="{sources.URL}" class="small_link">{sources.NAME}</a># END sources #</div>
		# ENDIF #
		<div class="spacer">&nbsp;</div>
		# IF C_NOTATION_ENABLED #
		<div style="float:left" class="text_small">
			{KERNEL_NOTATION}
		</div>
		# ENDIF #
		<div class="spacer"></div>
	</div>
</div>
<br /><br />
# IF C_COMMENTS_ENABLED #
    # INCLUDE COMMENTS #
# ENDIF #
