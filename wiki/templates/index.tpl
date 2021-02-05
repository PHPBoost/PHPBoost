<section id="module-wiki">
	<header class="section-header">
		<div class="controls align-right">
			<a href="${relative_url(SyndicationUrlBuilder::rss('wiki'))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
		</div>
		<h1>
			{TITLE}
		</h1>
	</header>

	# INCLUDE wiki_tools #

	<div class="sub-section">
		<div class="content">
			{INDEX_TEXT}
			<div class="options">
				<a href="{PATH_TO_ROOT}/wiki/{U_EXPLORER}">
					<i class="fa fa-folder-open" aria-hidden="true"></i>
					{L_EXPLORER}
				</a>
			</div>
		</div>
	</div>

	<div class="sub-section">
		<div class="cell-flex cell-columns-2 cell-tile">
			# START cat_list #
				<aside class="cell">
					<div class="cell-header">
						<h6 class="cell-name">{cat_list.L_CATS}</h6>
					</div>
					# IF C_NO_CATEGORY #
						<div class="cell-body">
							<div class="cell-content">{L_NO_CATEGORY}</div>
						</div>
					# ELSE #
						<div class="cell-list">
							<ul>
								# START cat_list.list #
									<li>
										<i class="fa fa-folder small" aria-hidden="true"></i> <a href="{PATH_TO_ROOT}/wiki/{cat_list.list.U_CAT}">{cat_list.list.CAT}</a>
									</li>
								# END cat_list.list #
							</ul>
						</div>
					# ENDIF #
				</aside>
			# END cat_list #
			# START last_articles #
				<aside class="cell">
					<div class="cell-header">
						<h6 class="cell-name">
							{last_articles.L_ARTICLES}
						</h6>
						# IF last_articles.C_ARTICLES #
							<a href="${relative_url(SyndicationUrlBuilder::rss('wiki'))}" aria-label="${LangLoader::get_message('syndication', 'common')}">
								<i class="fa fa-rss warning" aria-hidden="true"></i>
							</a>
						# ENDIF #
					</div>
					# IF C_NO_ITEM #
						<div class="cell-body">
							<div class="cell-content">{L_NO_ITEM}</div>
						</div>
					# ELSE #
						<div class="cell-list">
							<ul>
								# START last_articles.list #
									<li>
										<i class="fa fa-file-alt small" aria-hidden="true"></i> <a href="{PATH_TO_ROOT}/wiki/{last_articles.list.U_ARTICLE}" class="wiki-list-element">{last_articles.list.ARTICLE}</a>
									</li>
								# END last_articles.list #
							</ul>
						</div>
					# ENDIF #
				</aside>
			# END last_articles #
		</div>		
	</div>
	<footer></footer>
</section>
