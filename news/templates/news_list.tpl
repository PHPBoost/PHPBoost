		<script type="text/javascript">
		<!--
			function Confirm()
			{
				return confirm("{L_ALERT_DELETE_NEWS}");
			}
		-->
		</script>

		# IF C_ADD_OR_WRITER #
		<div class="module_actions">
			# IF C_ADD #
			<a href="{U_ADD}" title="{L_ADD}" class="img_link">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" class="valign_middle" alt="{L_ADD}" />
			</a>
			# ENDIF #
			&nbsp;
			# IF C_WRITER #
			<a href="{PATH_TO_ROOT}/news/news.php?user=1" title="{L_NEWS_WAITING}">
				<img src="{PATH_TO_ROOT}/news/news_mini.png" class="valign_middle" alt="{L_NEWS_WAITING}" />
			</a>
			# ENDIF #
		</div>
		<div class="spacer"></div>
		# ENDIF #

		# IF C_EDITO #
		<div class="module_position edito">
			<div class="module_top_l"></div>
			<div class="module_top_r"></div>
			<div class="module_top">
        		<div class="module_top_title">
					<a href="{U_SYNDICATION}" title="{L_SYNDICATION}" class="img_link">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{L_SYNDICATION}" />
					</a>
					{EDITO_NAME}
				</div>
				<div class="module_top_com">
					# IF C_ADMIN #
					<a href="{U_ADMIN}" title="{L_ADMIN}" class="img_link">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_ADMIN}" />
					</a>
					# ENDIF #
				</div>
				<div class="spacer"></div>
			</div>
			# IF EDITO_CONTENTS #
			<div class="module_contents">
				{EDITO_CONTENTS}
			</div>
			# ENDIF #
			<div class="module_bottom_l"></div>
           	<div class="module_bottom_r"></div>
           	<div class="module_bottom"></div>
		</div>
		# ENDIF #

		# IF C_NEWS_NO_AVAILABLE #
		<div class="module_position">
			<div class="module_top_l"></div>
			<div class="module_top_r"></div>
			<div class="module_top">
               	<div class="module_top_title">
					<a href="{U_SYNDICATION}" title="{L_SYNDICATION}" class="img_link">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{L_SYNDICATION}" />
					</a>
					{L_LAST_NEWS}
					# IF C_CAT # : {EDITO_NAME}# ENDIF #
				</div>
				<div class="module_top_com">
					# IF C_ADMIN #
					<a href="{U_ADMIN}" title="{L_ADMIN}" class="img_link">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_ADMIN}" />
					</a>
					# ENDIF #
				</div>
				<div class="spacer"></div>
			</div>
			<div class="module_contents" style="text-align:center;">
				{L_NO_NEWS_AVAILABLE}
			</div>
			<div class="module_bottom_l"></div>
           	<div class="module_bottom_r"></div>
           	<div class="module_bottom"></div>
		</div>
		# ELSE #
		<div class="module_position">
			<div class="module_top_l"></div>
			<div class="module_top_r"></div>
			<div class="module_top">
               	<div class="module_top_title">
					<a href="{U_SYNDICATION}" title="{L_SYNDICATION}" class="img_link">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{L_SYNDICATION}" />
					</a>
					{L_LAST_NEWS}
					# IF C_CAT # : {EDITO_NAME}# ENDIF #
				</div>
				<div class="module_top_com">
					# IF C_ADMIN #
					<a href="{PATH_TO_ROOT}/news/admin_news.php" title="{L_ADMIN}" class="img_link">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_ADMIN}" />
					</a>
					# ENDIF #
				</div>
				<div class="spacer"></div>
			</div>
			<div class="module_contents">
				# START list #
				# IF list.C_NEWS_ROW #<div class="spacer"></div># ENDIF #
				# IF C_NEWS_LINK_COLUMN #<div style="float:left;width:{COLUMN_WIDTH}%"># ELSE #<div># ENDIF #
					<ul style="margin:0;padding:0;list-style-type:none;">
						<li>
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/li.png" alt="" />
							# IF list.ICON #<a href="{list.U_CAT}"><img class="valign_middle" src="{list.ICON}" alt="" /></a># ENDIF #
							# IF list.DATE #<span class="text_small">{list.DATE} : </span># ENDIF #
							<a href="{list.U_NEWS}">{list.TITLE}</a>
							# IF list.C_COM #({list.COM})# ENDIF #
						</li>
					</ul>
				</div>
				# END list #
				<div class="spacer"></div>
				# IF PAGINATION #<div class="text_center">{PAGINATION}</div># ENDIF #
			</div>
			<div class="module_bottom_l"></div>
           	<div class="module_bottom_r"></div>
           	<div class="module_bottom"></div>
		</div>
		# ENDIF #