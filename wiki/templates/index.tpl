		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div class="module_top_title">{TITLE}</div>
			</div>
			<div class="module_contents">
				# INCLUDE wiki_tools #
				<br /><br />
				{INDEX_TEXT}
				<br />
				# START cat_list #
					<hr /><br />
					<strong><em>{cat_list.L_CATS}</em></strong>
					<br /><br />
					# START cat_list.list #
						<img src="{PICTURES_DATA_PATH}/images/cat.png" class="valign_middle" alt="" />&nbsp;<a href="{PATH_TO_ROOT}/wiki/{cat_list.list.U_CAT}">{cat_list.list.CAT}</a><br />
					# END cat_list.list #
					{L_NO_CAT}
				# END cat_list #
				<br /><br />
				<div style="text-align:center;" class="row3">
					<a href="{PATH_TO_ROOT}/wiki/{U_EXPLORER}"><img src="{PICTURES_DATA_PATH}/images/explorer.png" alt="{L_EXPLORER}" class="valign_middle" /></a> &nbsp; <a href="{PATH_TO_ROOT}/wiki/{U_EXPLORER}">{L_EXPLORER}</a>
				</div>
				<br />
				# START last_articles #				
				<hr /><br />
				<table class="module_table">
					<tr>
						<th colspan="2">
							<strong><em>{last_articles.L_ARTICLES}</em></strong> {last_articles.RSS}
						</th>
					</tr>
					<tr>
						# START last_articles.list #
						{last_articles.list.TR}
							<td class="row2" style="width:50%">
								<img src="{PICTURES_DATA_PATH}/images/article.png" class="valign_middle" alt="" />&nbsp;<a href="{PATH_TO_ROOT}/wiki/{last_articles.list.U_ARTICLE}">{last_articles.list.ARTICLE}</a>
							</td>
						# END last_articles.list #
						{L_NO_ARTICLE}
					</tr>
				</table>
				# END last_articles #
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		