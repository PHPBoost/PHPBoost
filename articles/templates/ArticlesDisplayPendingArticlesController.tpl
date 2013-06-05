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
	<a href="{U_PUBLISHED_ARTICLES}" title="{L_PUBLISHED_ARTICLES}" class="img_link">
		{L_PUBLISHED_ARTICLES}
	</a>
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
		<div style="float:right;width:240px;" class="row3" id="form">
			{L_ARTICLES_FILTERS_TITLE}# INCLUDE FORM #
		</div>
		# ENDIF #
		<div class="spacer">&nbsp;</div>
		<p style="padding-left: 5px;font-weight:bold">{L_PENDING_ARTICLES}</p>
		<hr />
		# START articles #	
		<div class="block_container" style="margin-bottom:20px;height:160px;">
			<div class="block_contents">
				<div style="float:left;width:70%">
					<p style="margin-bottom:10px">
						<a href="{articles.U_ARTICLE}" class="big_link">{articles.TITLE}</a>
						# IF articles.C_EDIT #
						<a href="{articles.U_EDIT_ARTICLE}">
						    <img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{articles.L_EDIT_ARTICLE}" title="{articles.L_EDIT_ARTICLE}" />
						</a>
						# ENDIF #
						# IF articles.C_DELETE #
						<a href="{articles.U_DELETE_ARTICLE}" onclick="return Confirm_del_article();">
						    <img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{articles.L_DELETE_ARTICLE}" title="{articles.L_DELETE_ARTICLE}" />
						</a>
						# ENDIF #
					</p>
					<p style="margin-bottom:10px">
						{articles.DESCRIPTION}
					</p>
					<div class="text_small">
						{L_DATE} : {articles.DATE}
						<br />
						{L_VIEW} : {articles.NUMBER_VIEW}
						<br />
						{L_COM} : <a href="{articles.U_COMMENTS}">{articles.L_NUMBER_COM} </a>
						<br />
						{L_NOTE} : {articles.NOTE}
						<br />
						{L_WRITTEN} : <a href="{articles.U_AUTHOR}" class="small_link {articles.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{articles.USER_GROUP_COLOR}"# ENDIF #>{articles.PSEUDO}</a>
					</div>
				</div>	
			</div>
		</div>
		# END articles #
		{PAGINATION}
		<br />
		<p style="text-align:center;padding-top:10px;" class="text_small">
			{L_NO_ARTICLE} {L_TOTAL_ARTICLES}
		</p>
		&nbsp;
	</div>
	<div class="module_bottom_l"></div>		
	<div class="module_bottom_r"></div>
	<div class="module_bottom text_strong">
		<a href="../articles/{SID}">{L_ARTICLES_INDEX}</a>
	</div>
</div>
