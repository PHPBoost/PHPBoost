<section id="module-wiki">
	<header class="section-header">
		<div class="controls align-right">
			<a href="${relative_url(SyndicationUrlBuilder::rss('wiki', ID_CAT))}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			{CATEGORY_TITLE}
		</div>
		<h1 itemprop="name">{TITLE}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<div class="cell-flex cell-columns-2 cell-tile">
				# START cat #
					# IF cat.list_cats #
						<aside class="cell">
							<div class="cell-header">
								<h6 class="cell-name">{@wiki.sub.categories.in.category}</h6>
							</div>
							<div class="cell-list">
								<ul>
									# START cat.list_cats #
										<li>
											<i class="fa fa-folder" aria-hidden="true"></i> <a href="{cat.list_cats.U_CAT}">{cat.list_cats.NAME}</a>
										</li>
									# END cat.list_cats #
								</ul>
							</div>
						</aside>
					# ENDIF #
					<aside class="cell">
						<div class="cell-header">
							<h6 class="cell-name">{@wiki.items.in.category}</h6>
						</div>
						<div class="cell-list">
							<ul>
								# START cat.list_art #
									<li>
										<i class="fa fa-file" aria-hidden="true"></i> <a href="{cat.list_art.U_ARTICLE}">{cat.list_art.TITLE}</a>
									</li>
								# END cat.list_art #
							</ul>
						</div>
						# START cat.no_sub_article #
							<div class="cell-body"><div class="cell-content">{cat.no_sub_article.NO_SUB_ARTICLE}</div></div>
						# END cat.no_sub_article #
					</aside>
				# END cat #
				<div class="spacer"></div>
			</div>
		</div>
	</div>
	<div class="sub-section">
		<div class="content-container">
			<article id="article-wiki-{ID}" class="wiki-item single-item# IF C_NEW_CONTENT # new-content# ENDIF #">

				# INCLUDE WIKI_TOOLS #

				<div class="content">
					# IF C_WARNING_UPDATE #
						<div class="message-helper bgc warning">{@wiki.warning.update}</div>
					# ENDIF #

					# START redirect #
						{redirect.REDIRECTED}
						# START redirect.remove_redirection #
							<a href="{redirect.remove_redirection.U_REMOVE_REDIRECTION}" data-confirmation="{redirect.remove_redirection.L_ALERT_REMOVE_REDIRECTION}" aria-label="{redirect.remove_redirection.L_REMOVE_REDIRECTION}"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
						# END redirect.remove_redirection #
					# END redirect #

					# START status #
						{status.ARTICLE_STATUS}
					# END status #

					# START menu #
						# IF C_STICKY_MENU #
							<span class="wiki-sticky-title blink">{L_TABLE_OF_CONTENTS}</span>
							<div class="wiki-sticky">
								{menu.MENU}
							</div>
						# ELSE #
							<div class="wiki-summary">
								<div class="wiki-summary-title">{L_TABLE_OF_CONTENTS}</div>
								{menu.MENU}
							</div>
						# ENDIF #
					# END menu #
					{CONTENT}
				</div>
				<div class="spacer"></div>
				${ContentSharingActionsMenuService::display()}
			</article>
		</div>
	</div>
	<footer><div class="wiki-hits">{HITS}</div></footer>
</section>

# IF C_STICKY_MENU #
	<script>
		/* Push the body and the nav over by the menu div width over */
		var summaryWidth = jQuery('.wiki-sticky').innerWidth();
		var viewportWidth = jQuery(window).width();

		jQuery('.wiki-sticky-title').on('click',function(f) {
			jQuery('.wiki-sticky-title').removeClass('blink');
			jQuery('.wiki-sticky').animate({
				left: "0px",
				'max-width': viewportWidth + 'px'
			}, 200);

			jQuery('body').animate({
				left: summaryWidth + 'px'
			}, 200);
			f.stopPropagation();
		});

		/* Then push them back by clicking outside the menu or on an inside link*/
		jQuery(document).on('click',function(f) {
			if (jQuery(f.target).is('.wiki-sticky-title') === false) {
				jQuery('.wiki-sticky').animate({
					left: -summaryWidth + 'px'
				}, 200);

				jQuery('body').animate({
					left: "0px"
				}, 200);
			}
		});
		jQuery('.wiki-sticky a').on('click',function() {
			jQuery('.wiki-sticky').animate({
				left: -summaryWidth + 'px'
			}, 200);

			jQuery('body').animate({
				left: "0px"
			}, 200);
		});

		// smooth scroll when clicking on an inside link
		jQuery('.wiki-sticky a').on('click',function(){
			var the_id = jQuery(this).attr("href");

			jQuery('html, body').animate({
				scrollTop:jQuery(the_id).offset().top
			}, 'slow');
			return false;
		});
	</script>
# ELSE #
	<script>
		// smooth scroll when clicking on a inside link
		jQuery('a[href^="#paragraph"]').on('click',function() {
			var the_id = $(this).attr("href");

			$('html, body').animate({
				scrollTop:$(the_id).offset().top
			}, 'slow');
			return false;
		})
	</script>
# ENDIF #
