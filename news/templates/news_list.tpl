		<script type="text/javascript">
		<!--
			function Confirm()
			{
				return confirm("{L_ALERT_DELETE_NEWS}");
			}
		-->
		</script>

		# IF C_ADD_OR_WRITER #
		<div class="float_right" style="margin:0 10px 15px;">
			# IF C_ADD #
			<a href="{U_ADD}" title="{L_ADD}">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" class="valign_middle" alt="{L_ADD}" />
			</a>
			# ENDIF #
			&nbsp;
			# IF C_WRITER #
			<a href="news.php?user=1" title="{L_WRITER}">
				<img src="news_mini.png" class="valign_middle" alt="{L_WRITER}" />
			</a>
			# ENDIF #
		</div>
		<div class="spacer"></div>
		# ENDIF #

		# IF C_EDITO #
		<div class="news_container edito">
			<div class="news_top_l"></div>
			<div class="news_top_r"></div>
			<div class="news_top">
				<div class="float_left">
					<a href="{U_SYNDICATION}" title="{L_SYNDICATION}">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{L_SYNDICATION}" />
					</a>
					<h3 class="title">{EDITO_NAME}</h3>
				</div>
				<div class="float_right">
					# IF C_ADMIN #
					<a href="{U_ADMIN}" title="{L_ADMIN}">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_ADMIN}" />
					</a>
					# ENDIF #
				</div>
				<div class="spacer"></div>
			</div>
			# IF EDITO_CONTENTS #
			<div class="news_content">
				{EDITO_CONTENTS}
			</div>
			# ENDIF #
			<div class="news_bottom_l"></div>
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>
		# ENDIF #

		# IF C_NEWS_NO_AVAILABLE #
		<div class="news_container">
			<div class="news_top_l"></div>
			<div class="news_top_r"></div>
			<div class="news_top">
				<div class="float_left">
					<a href="{U_SYNDICATION}" title="{L_SYNDICATION}">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{L_SYNDICATION}" />
					</a>
					<h3 class="title">
						{L_LAST_NEWS}
						# IF C_CAT # : {EDITO_NAME}# ENDIF #
					</h3>
				</div>
				<div class="float_right">
					# IF C_ADMIN #
					<a href="{U_ADMIN}" title="{L_ADMIN}">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_ADMIN}" />
					</a>
					# ENDIF #
				</div>
				<div class="spacer"></div>
			</div>
			<div class="news_content" style="text-align:center;">
				{L_NO_NEWS_AVAILABLE}
			</div>
			<div class="news_bottom_l"></div>
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>
		# ELSE #
		<div class="news_container">
			<div class="news_top_l"></div>
			<div class="news_top_r"></div>
			<div class="news_top">
				<div class="float_left">
					<a href="{U_SYNDICATION}" title="{L_SYNDICATION}">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{L_SYNDICATION}" />
					</a>
					<h3 class="title">
						{L_LAST_NEWS}
						# IF C_CAT # : {EDITO_NAME}# ENDIF #
					</h3>
				</div>
				<div class="float_right">
					# IF C_ADMIN #
					<a href="{U_ADMIN}" title="{L_ADMIN}">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_ADMIN}" />
					</a>
					# ENDIF #
				</div>
				<div class="spacer"></div>
			</div>
			<div class="news_content">
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
			<div class="news_bottom_l"></div>
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>
		# ENDIF #