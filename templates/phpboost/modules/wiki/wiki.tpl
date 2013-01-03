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
				
				<hr style="margin:5px 0px;" />
				
				# START cat #
				<div class="pbt_container block_container">
					
					# IF cat.list_cats #
					<div class="pbt_content">
						<p class="pbt_title">Catégories :</p>
					</div>
					
					<ul class="bb_ul" style="list-style:none;margin-bottom:15px;line-height: 1.8em;">
						# START cat.list_cats #
						<li class="bb_li">
							<img src="{PICTURES_DATA_PATH}/images/cat.png" class="valign_middle" alt="" />
							&nbsp;
							<a href="{cat.list_cats.U_CAT}">{cat.list_cats.NAME}</a>
						</li>
						# END cat.list_cats #
					</ul>
					# END IF #

					# IF cat.list_art #
					<div class="pbt_content">
						<p class="pbt_title">
							<p class="pbt_title"><a href=${relative_url(SyndicationUrlBuilder::rss('wiki', ID_CAT))} title="Flux RSS">Articles</a></p>
						</p>
					</div>

					<ul class="bb_ul" style="list-style:none;margin-bottom:15px;line-height: 1.8em;">
						# START cat.list_art #
						<li class="bb_li">
							<img src="{PICTURES_DATA_PATH}/images/article.png"  class="valign_middle" alt="" />
							&nbsp;
							<a href="{cat.list_art.U_ARTICLE}">{cat.list_art.TITLE}</a>
						</li>
						# END cat.list_art #
					</ul>
					# END IF #
							
				</div>
				
				# START cat.no_sub_article #
				<div style="margin:10px;text-align: center;">
					{cat.no_sub_article.NO_SUB_ARTICLE}
				</div>
				
				# END cat.no_sub_article #
				
				# END cat #
				<div class="spacer" style="margin-top:30px;">&nbsp;</div>
			</div>
			<hr style="margin:5px 0px;" />
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom" style="text-align:center;margin-top:8px;margin-bottom:10px;">{HITS}</div>
		</div>
		