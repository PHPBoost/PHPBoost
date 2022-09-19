<section id="module-wiki" class="several-items">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="${relative_url(SyndicationUrlBuilder::rss('wiki'))}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
		</div>
		<h1>
			{TITLE}
		</h1>
	</header>

	# INCLUDE WIKI_TOOLS #
	
	# IF C_HAS_ROOT_DESCRIPTION #
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					{ROOT_DESCRIPTION}
				</div>
			</div>
		</div>
	# ENDIF #

	<div class="sub-section">
		<div class="content-container">
			<div class="align-center">
				<a href="{PATH_TO_ROOT}/wiki/{U_EXPLORER}" class="button bgc-full link-color offload">
					<i class="fa fa-folder-open" aria-hidden="true"></i>
					{@wiki.explorer}
				</a>
			</div>
			<div class="cell-flex cell-columns-2 cell-tile">
				# START cat_list #
					<aside class="cell">
						<div class="cell-header">
							<h6 class="cell-name">{@wiki.categories.list}</h6>
						</div>
						# IF cat_list.C_CATEGORIES #
							<div class="cell-list">
								<ul>
									# START cat_list.list #
										<li>
											<i class="fa fa-folder small" aria-hidden="true"></i> <a class="offload" href="{PATH_TO_ROOT}/wiki/{cat_list.list.U_CATEGORY}">{cat_list.list.CATEGORY_NAME}</a>
										</li>
									# END cat_list.list #
								</ul>
							</div>
						# ELSE #
							<div class="cell-body bgc notice">
								<div class="cell-content">{@wiki.no.category}</div>
							</div>
						# ENDIF #
					</aside>
				# END cat_list #
				# START last_articles #
					<aside class="cell">
						<div class="cell-header">
							<h6 class="cell-name">
								{@wiki.last.items.list}
							</h6>
							# IF last_articles.C_ITEMS #
								<a class="offload" href="${relative_url(SyndicationUrlBuilder::rss('wiki'))}" aria-label="{@common.syndication}">
									<i class="fa fa-rss warning" aria-hidden="true"></i>
								</a>
							# ENDIF #
						</div>
						# IF last_articles.C_ITEMS #
							<div class="cell-list">
								<ul>
									# START last_articles.list #
										<li>
											<i class="fa fa-file-alt small" aria-hidden="true"></i> <a class="offload" href="{PATH_TO_ROOT}/wiki/{last_articles.list.U_ITEM}" class="wiki-list-element">{last_articles.list.ITEM_TITLE}</a>
										</li>
									# END last_articles.list #
								</ul>
							</div>
						# ELSE #
							<div class="cell-body bgc notice">
								<div class="cell-content">{@wiki.no.item}</div>
							</div>
						# ENDIF #
					</aside>
				# END last_articles #
			</div>
		</div>
	</div>
	<footer></footer>
</section>
