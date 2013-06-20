<script type="text/javascript">
<!--
function Confirm()
{
	return confirm("${i18n('news.message.delete')}");
}
-->
</script>

<div class="module_position">
	<div class="module_top_l"></div>
	<div class="module_top_r"></div>
	<div class="module_top module_top_news">
	
		<ul class="module_top_options">
			<li>
				<a>
					<span class="options"></span><span class="caret"></span>
				</a>
				<ul>
					# IF C_EDIT #
					<li>
						<a href="{U_EDIT}" title="{L_EDIT}" class="img_link">{L_EDIT}</a>
					</li>
					# ENDIF #
					# IF C_DELETE #
					<li>
						<a href="{U_DELETE}" title="{L_DELETE}" onclick="javascript:return Confirm();">{L_DELETE}</a>
					</li>
					# ENDIF #
				</ul>
			</li>
		</ul>

		<div class="module_top_title">
			<a href="{U_SYNDICATION}" title="{L_SYNDICATION}" class="img_link">
				<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{L_SYNDICATION}"/>
			</a>
			{NAME}
		</div>
		
		<div class="news_author_container">
			Par # IF PSEUDO #<a class="small_link {USER_LEVEL_CLASS}" href="{U_AUTHOR_PROFILE}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{PSEUDO}</a>, # ENDIF # 
			le {DATE},
			dans la catégorie <a href="{U_CATEGORY}">{CATEGORY_NAME}</a>
		</div>
		
		<div class="spacer"></div>
		
	</div>
	<div class="module_contents">
		# IF C_PICTURE #<img src="{U_PICTURE}" alt="{NAME}" title="{NAME}" class="img_right" /># ENDIF #
		
		{CONTENTS}
		
		<div class="spacer" style="margin-bottom:40px;"></div>
	
		<!-- # IF C_SOURCES # -->
		<div id="news_sources_container">
			<span class="news_more_title">{L_SOURCES}</span> :
			# START sources #
			<a href="{sources.URL}" class="small_link">{sources.NAME}</a> - 
			# END sources #
		</div>
		<!-- # ENDIF # -->

		<div id="news_tags_container">
			<span class="news_more_title">Mot cléfs</span> :
			<span>PHP</span> - <span>Boost</span> - <span>Elephant</span>
		</div>
								
		<div class="spacer"></div>
	</div>
	<div class="module_bottom_l"></div>
	<div class="module_bottom_r"></div>
	<div class="module_bottom">
		<div class="spacer"></div>
	</div>
		
		<!-- # IF C_NEWS_SUGGESTED # -->
		<div id="news_suggested_container">
			<div class="news_more_title">{L_NEWS_SUGGESTED}</div>
			<ul class="bb_ul" style="margin: 10px 30px 0;">
				# START suggested #
				<li class="bb_li"><a href="{suggested.URL}">{suggested.TITLE}</a></li>
				# END suggested #
			</ul>
		</div>
		<!-- # ENDIF # -->
	
	<hr style="width:70%;margin:0px auto 40px auto;">
	
	# IF C_NEWS_NAVIGATION_LINKS #
	<div class="navigation_link">
		# IF C_PREVIOUS_NEWS #
		<span style="float:left">
			<a href="{U_PREVIOUS_NEWS}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/left.png" alt="" class="valign_middle" /></a>
			<a href="{U_PREVIOUS_NEWS}">{PREVIOUS_NEWS}</a>
		</span>
		# ENDIF #
		# IF C_NEXT_NEWS #
		<span style="float:right">
			<a href="{U_NEXT_NEWS}">{NEXT_NEWS}</a>
			<a href="{U_NEXT_NEWS}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/right.png" alt="" class="valign_middle" /></a>
		</span>
		# ENDIF #
		<div class="spacer"></div>
	</div>
	# ENDIF #
	
	<div class="">
		Ajouter un menu déroulant pour afficher / masquer l'ajout d'un commentaire.
	</div>
	# INCLUDE COMMENTS #	
</div>