<section id="module-wiki">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('wiki', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}" class="fa fa-syndication smaller"></a>
			${LangLoader::get_message('category', 'main')} {ID_CAT}
		</h1>
	</header>
	<article id="article-wiki-{ID}" class="article-wiki# IF C_NEW_CONTENT # new-content# ENDIF #">
		<header>
			<h2>
				{TITLE}
			</h2>
		</header>
		# INCLUDE wiki_tools #
		<article>
			<div class="content">
				# START warning #
				<div class="message-helper warning">{warning.UPDATED_ARTICLE}</div>
				# END warning #

				# START redirect #
					<div style="width:30%;">
					{redirect.REDIRECTED}
						# START redirect.remove_redirection #
							<a href="{redirect.remove_redirection.U_REMOVE_REDIRECTION}" title="{redirect.remove_redirection.L_REMOVE_REDIRECTION}" class="far fa-delete" data-confirmation="{redirect.remove_redirection.L_ALERT_REMOVE_REDIRECTION}"></a>
						# END redirect.remove_redirection #
					</div>
					<div class="spacer"></div>
				# END redirect #

				# START status #
					<div class="spacer"></div>
					<div class="blockquote">{status.ARTICLE_STATUS}</div>
					<div class="spacer"></div>
				# END status #

				# START menu #
					<div class="wiki-summary">
						<div class="wiki-summary-title">{L_TABLE_OF_CONTENTS}</div>
						{menu.MENU}
					</div>
				# END menu #

				{CONTENTS}
			</div>
		</article>

		<div class="elements-container columns-2">
			# START cat #
				# IF cat.list_cats #
					<aside class="block">
						<div class="wiki-list-container">
							<div class="wiki-list-top">{L_SUB_CATS}</div>
							<div class="wiki-list-content">
								# START cat.list_cats #
									<div class="wiki-list-item">
										<i class="far fa-folder"></i> <a href="{cat.list_cats.U_CAT}">{cat.list_cats.NAME}</a>
									</div>
								# END cat.list_cats #
								# START cat.no_sub_cat #
									<div class="wiki-list-item">{cat.no_sub_cat.NO_SUB_CAT}</div>
								# END cat.no_sub_cat #
							</div>
						</div>
					</aside>
				# ENDIF #
				<aside class="block">
					<div class="wiki-list-container">
						<div class="wiki-list-top">{L_SUB_ARTICLES}</div>
						<div class="wiki-list-content">
							# START cat.list_art #
								<div class="wiki-list-item">
									<i class="fa fa-file-o"></i> <a href="{cat.list_art.U_ARTICLE}">{cat.list_art.TITLE}</a>
								</div>
							# END cat.list_art #
							# START cat.no_sub_article #
								<div class="wiki-list-item">{cat.no_sub_article.NO_SUB_ARTICLE}</div>
							# END cat.no_sub_article #
						</div>
					</div>
				</aside>


			# END cat #
			<div class="spacer"></div>
		</div>
		<footer>
			<div class="wiki-hits">{HITS}</div>
		</footer>
	</article>
	<footer></footer>
</section>

<script>
	<!--
	jQuery('a[href^="#paragraph"]').click(function() {
		var the_id = $(this).attr("href");

		$('html, body').animate({
			scrollTop:$(the_id).offset().top
		}, 'slow');
		return false;
	})
	-->
</script>
