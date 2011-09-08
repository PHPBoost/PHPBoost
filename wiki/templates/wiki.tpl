		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">{TITLE}</div>
			<div class="module_contents">
				# INCLUDE wiki_tools #
				
				# START warning #
					<br /><br />
					<div class="row3">{warning.UPDATED_ARTICLE}</div>
					<br />
				# END warning #
						
				# START redirect #
					<div class="row2" style="width:30%;">
					{redirect.REDIRECTED}
						# START redirect.remove_redirection #
							<a href="{redirect.remove_redirection.U_REMOVE_REDIRECTION}" title="{redirect.remove_redirection.L_REMOVE_REDIRECTION}" onclick="javascript: return confirm('{redirect.remove_redirection.L_ALERT_REMOVE_REDIRECTION}');"><img src="{PICTURES_DATA_PATH}/images/delete.png" alt="{redirect.remove_redirection.L_REMOVE_REDIRECTION}" class="valign_middle" /></a>
						# END redirect.remove_redirection #
					</div>
					<br />
				# END redirect #
				
				# START status #
					<br /><br />
					<div class="row3">{status.ARTICLE_STATUS}</div>
					<br />
				# END status #
				
				# START menu #
					<div class="row3" style="width:50%">
						<div style="text-align:center;"><strong>{L_TABLE_OF_CONTENTS}</strong></div>
						{menu.MENU}
					</div>
				# END menu #
				<br /><br /><br />
				{CONTENTS}
				<br /><br />
				# START cat #
					<hr />
					# IF cat.list_cats #
					<br />
					<strong>{L_SUB_CATS}</strong>
					<br /><br />
					# START cat.list_cats #
						<img src="{PICTURES_DATA_PATH}/images/cat.png"  class="valign_middle" alt="" />&nbsp;<a href="{cat.list_cats.U_CAT}">{cat.list_cats.NAME}</a><br />
					# END cat.list_cats #
					# START cat.no_sub_cat #
					{cat.no_sub_cat.NO_SUB_CAT}<br />
					# END cat.no_sub_cat #
					# END IF #
					<br />
					<strong>{L_SUB_ARTICLES}</strong> &nbsp; {cat.RSS}
					<br /><br />
					# START cat.list_art #
						<img src="{PICTURES_DATA_PATH}/images/article.png"  class="valign_middle" alt="" />&nbsp;<a href="{cat.list_art.U_ARTICLE}">{cat.list_art.TITLE}</a><br />
					# END cat.list_art #
					
					# START cat.no_sub_article #
					{cat.no_sub_article.NO_SUB_ARTICLE}
					# END cat.no_sub_article #
					
				# END cat #
				<div class="spacer" style="margin-top:30px;">&nbsp;</div>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom" style="text-align:center;margin-top:8px;margin-bottom:10px;">{HITS}</div>
		</div>
		