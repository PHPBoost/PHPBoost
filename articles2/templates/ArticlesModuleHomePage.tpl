{JAVA} 
<script type="text/javascript">
<!--
	function Confirm_del_article() {
	return confirm("{L_ALERT_DELETE_ARTICLE}");
	}
-->
</script>


<div class="module_actions">
	# IF IS_ADMIN #
	<a href="{U_EDIT_CONFIG}" title="{L_EDIT_CONFIG}" class="img_link">
		<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT_CONFIG}"/>
	</a>
	# ENDIF #
	# IF C_ADD #
		<a href="{U_ADD_ARTICLES}" title="{L_ADD_ARTICLES}" class="img_link">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" class="valign_middle" alt="{L_ADD_ARTICLES}" />
		</a>
		&nbsp;
	# ENDIF #
	# IF C_PENDING_ARTICLES #
		<a href="{U_PENDING_ARTICLES}" title="{L_PENDING_ARTICLES}" class="img_link">
			{L_PENDING_ARTICLES}
		</a>
	# ENDIF #
</div>
<div class="spacer"></div>
	

<div class="module_position">					
	<div class="module_top_l"></div>		
	<div class="module_top_r"></div>
	<div class="module_top">
		<div class="module_top_title">
			<a href="{PATH_TO_ROOT}/syndication/?url=/rss/articles/{ID_CAT}" title="Rss" class="img_link">
				<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Rss" title="Rss" />
			</a>
			{L_MODULE_NAME}
		</div>
	</div>
	<div class="module_contents">
                <p style="text-align:center;" class="text_strong">
                        {L_MANAGE_CAT}
                        # IF C_MODERATE # <a href="{U_MANAGE_CAT}"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="" /></a> # ENDIF #
                </p>
                <hr style="margin-bottom:20px;" />
                # START cat_list #
                <div style="float:left;text-align:center;width:{COLUMN_WIDTH_CAT}%;margin-bottom:20px;">
                        {cat_list.ICON_CAT}
                        <a href="{PATH_TO_ROOT}/articles/articles{cat_list.U_CAT}">{cat_list.CAT}</a> {cat_list.EDIT}
                        <br />
                        <span class="text_small">{cat_list.DESC}</span> 
                        <br />
                        <span class="text_small">{cat_list.L_NBR_ARTICLES}</span> 
                </div>
                # END cat_list #
                <div class="spacer">&nbsp;</div>				
                <p style="text-align:center;">{PAGINATION}</p>
                <hr />
	</div>
	<div class="module_bottom_l"></div>		
	<div class="module_bottom_r"></div>
	<div class="module_bottom text_strong">
		<a href="{PATH_TO_ROOT}/articles/articles.php{SID}">{L_ARTICLES_INDEX}</a> &raquo; {U_ARTICLES_CAT_LINKS} {EDIT} {ADD_ARTICLES}
	</div>
</div>
