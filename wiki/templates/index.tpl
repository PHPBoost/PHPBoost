		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">{TITLE}</div>
			<div class="module_contents">
				<br />		
				{TOOLS}
				<br /><br />
				{INDEX_TEXT}
				<br /><br /><br />
				<div style="text-align:center;" class="row3">
					<a href="{U_EXPLORER}"><img src="{WIKI_PATH}/images/explorer.png" alt="{L_EXPLORER}" style="vertical-align:middle;" /></a> &nbsp; <a href="{U_EXPLORER}">{L_EXPLORER}</a>
				</div>
				<br />
				# START cat_list #
					<hr /><br />
					<strong><em>{cat_list.L_CATS}</em></strong>
					<br /><br />
					# START list #
						<img src="{WIKI_PATH}/images/cat.png" style="vertical-align:middle;" alt="" />&nbsp;<a href="{cat_list.list.U_CAT}">{cat_list.list.CAT}</a><br />
					# END list #
					{L_NO_CAT}
				# END cat_list #
				
				# START last_articles #				
				<hr /><br />
				<table class="module_table">
					<tr>
						<th colspan="2">
							<strong><em>{last_articles.L_ARTICLES}</em></strong> {last_articles.RSS}
						</th>
					</tr>
					<tr>
						# START list #
						{last_articles.list.TR}
							<td class="row2" style="width:50%">
								<img src="{WIKI_PATH}/images/article.png" style="vertical-align:middle;" alt="" />&nbsp;<a href="{last_articles.list.U_ARTICLE}">{last_articles.list.ARTICLE}</a>
							</td>
						# END list #
						{L_NO_ARTICLE}
					</tr>
				</table>
				# END last_articles #
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		