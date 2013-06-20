<script type="text/javascript">
<!--
	function Confirm_del_article() {
		return confirm("{L_ALERT_DELETE_ARTICLE}");
	}
-->
</script>
# INCLUDE MSG #
<div class="module_position">					
	<div class="module_top_l"></div>		
	<div class="module_top_r"></div>
	<div class="module_top">
		<div class="module_top_title">
			<a href="{U_SYNDICATION}" title="Rss" class="img_link">
				<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Rss" title="Rss" />
			</a>
			{TITLE}
		</div>
		<div class="module_top_com">
			# IF C_COMMENTS_ENABLED #
			<a href="{U_COMMENTS}" title="{L_COMMENTS}" class="img_link">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/com_mini.png" alt="" class="valign_middle" />
				{L_COMMENTS}
			</a>
			# ENDIF #
			# IF C_EDIT #
			<a href="{U_EDIT_ARTICLE}" title="{L_EDIT}" class="img_link">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" class="valign_middle" alt="{L_EDIT}" />
			</a>
			# ENDIF #
			# IF C_DELETE #
			<a href="{U_DELETE_ARTICLE}" title="{L_DELETE}" onclick="javascript:return Confirm_del_article();" class="img_link">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" class="valign_middle" alt="{L_DELETE}" />
			</a>
			# ENDIF #
			<a href="{U_PRINT_ARTICLE}" title="{L_PRINTABLE_VERSION}" class="img_link" target="_blank">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/print_mini.png" alt="{L_PRINTABLE_VERSION}" class="valign_middle" />
			</a>
		</div>
	</div>
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
	<div class="module_bottom_l"></div>		
	<div class="module_bottom_r"></div>
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
		<div style="float:right" class="text_small">
			# IF C_AUTHOR_DISPLAYED #
			{L_WRITTEN}<a href="{U_AUTHOR}" class="small_link {USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}"# ENDIF #>{PSEUDO}</a>{L_ON}{DATE}
			# ELSE #
			{L_NO_AUTHOR_DISPLAYED}{DATE}
			# ENDIF #
		</div>
		<div class="spacer"></div>
	</div>
</div>
<br /><br />
# IF C_COMMENTS_ENABLED #
    # INCLUDE COMMENTS #
# ENDIF #
