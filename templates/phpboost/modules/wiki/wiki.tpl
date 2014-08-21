		<article>					
			<header>
				<h1>
					<a href="${relative_url(SyndicationUrlBuilder::rss('wiki', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}" class="fa fa-syndication"></a>
					{TITLE}
				</h1>
			</header>
			<div class="content">
				# INCLUDE wiki_tools #
				
				# START warning #
				<div id="id-message-helper" class="warning">{warning.UPDATED_ARTICLE}</div>
				# END warning #
						
				# START redirect #
					<div style="width:30%;">
					{redirect.REDIRECTED}
						# START redirect.remove_redirection #
							<a href="{redirect.remove_redirection.U_REMOVE_REDIRECTION}" title="{redirect.remove_redirection.L_REMOVE_REDIRECTION}" class="fa fa-delete" data-confirmation="{redirect.remove_redirection.L_ALERT_REMOVE_REDIRECTION}"></a>
						# END redirect.remove_redirection #
					</div>
					<br />
				# END redirect #
				
				# START status #
					<br /><br />
					<div class="blockquote">{status.ARTICLE_STATUS}</div>
					<br />
				# END status #
				
				# START menu #
					<div class="options" style="float:none;width:50%">
						<div style="text-align:center;"><strong>{L_TABLE_OF_CONTENTS}</strong></div>
						{menu.MENU}
					</div>
				# END menu #
				<br /><br /><br />
				{CONTENTS}
				<br /><br />
				
				<hr style="margin:5px 0px;" />
				
				# START cat #
					
					# IF cat.list_cats #
					<div class="pbt-container block-container">
						<div class="pbt-content">
							<p class="pbt-title">Catégories :</p>
						</div>
						
						<ul class="bb-ul no-list">
							# START cat.list_cats #
							<li>
								<i class="fa fa-folder"></i> <a href="{cat.list_cats.U_CAT}">{cat.list_cats.NAME}</a>
							</li>
							# END cat.list_cats #
						</ul>
					# ELSE #
					# IF cat.list_art #
					<div class="pbt-container block-container">
					# END IF #
					# END IF #

					# IF cat.list_art #
						<div class="pbt-content">
							<p class="pbt-title">
								<p class="pbt-title"><a href=${relative_url(SyndicationUrlBuilder::rss('wiki', ID_CAT))} title="Flux RSS">Articles</a></p>
							</p>
						</div>
		
						<ul class="bb-ul no-list">
							# START cat.list_art #
							<li>
								<i class="fa fa-file-text"></i> <a href="{cat.list_art.U_ARTICLE}">{cat.list_art.TITLE}</a>
							</li>
							# END cat.list_art #
						</ul>
					</div>
					# ELSE #
					# IF cat.list_cats #
					</div>
					# END IF #
					# END IF #

				# START cat.no_sub_article #
				<div class="no-article">
					{cat.no_sub_article.NO_SUB_ARTICLE}
				</div>
				# END cat.no_sub_article #
				
				# END cat #
				<div class="spacer" style="margin-top:30px;">&nbsp;</div>
			</div>
			<footer>
				<div style="text-align:center;margin-top:8px;margin-bottom:10px;">{HITS}</div>
			</footer>
		</article>