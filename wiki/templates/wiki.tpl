<section id="module-wiki">
	<header>
		<div class="align-right controls">
			<a href="${relative_url(SyndicationUrlBuilder::rss('wiki', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			{CATEGORY_TITLE}
		</div>
		<h1 itemprop="name">{TITLE}</h1>
	</header>
	<div id="article-wiki-{ID}" class="article-wiki# IF C_NEW_CONTENT # new-content# ENDIF #">

		<div class="cell-flex cell-columns-2 cell-tile">
			# START cat #
				# IF cat.list_cats #
					<aside class="cell">
						<div class="cell-header">
							<h6 class="cell-name">{L_SUB_CATS}</h6>
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
						# START cat.no_sub_cat #
							<div class="cell-body"><div class="cell-content">{cat.no_sub_cat.NO_SUB_CAT}</div></div>
						# END cat.no_sub_cat #
					</aside>
				# ENDIF #
				<aside class="cell">
					<div class="cell-header">
						<h6 class="cell-name">{L_SUB_ARTICLES}</h6>
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

		# INCLUDE wiki_tools #

		<div class="content">
			# START warning #
			<div class="message-helper bgc warning">{warning.UPDATED_ARTICLE}</div>
			# END warning #

			# START redirect #
				<div style="width:30%;">
				{redirect.REDIRECTED}
					# START redirect.remove_redirection #
						<a href="{redirect.remove_redirection.U_REMOVE_REDIRECTION}" data-confirmation="{redirect.remove_redirection.L_ALERT_REMOVE_REDIRECTION}" aria-label="{redirect.remove_redirection.L_REMOVE_REDIRECTION}"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
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
			<div class="spacer"></div>
			{CONTENTS}
		</div>
		<div class="spacer"></div>
		${ContentSharingActionsMenuService::display()}

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
