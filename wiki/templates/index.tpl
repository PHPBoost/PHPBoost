<section id="module-wiki">
	<header>
		<div class="align-right">
			<a href="${relative_url(SyndicationUrlBuilder::rss('wiki'))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss" aria-hidden="true"></i></a>
		</div>
		<h1>
			{TITLE}
		</h1>
	</header>

	# INCLUDE wiki_tools #

	<article>
		<div class="content">
			{INDEX_TEXT}
			<div class="options">
				<a href="{PATH_TO_ROOT}/wiki/{U_EXPLORER}">
					<i class="fa fa-folder-open" aria-hidden="true"></i>
					{L_EXPLORER}
				</a>
			</div>
		</div>
	</article>

	<div class="elements-container columns-2">
		# START cat_list #
			<aside class="block">
				<div class="wiki-list-container">
					<div class="wiki-list-top">{cat_list.L_CATS}</div>
					<div class="wiki-list-content">
						# START cat_list.list #
							<div class="wiki-list-item">
								<i class="fa fa-folder small" aria-hidden="true"></i> <a href="{PATH_TO_ROOT}/wiki/{cat_list.list.U_CAT}">{cat_list.list.CAT}</a>
							</div>
						# END cat_list.list #
					</div>
					{L_NO_CAT}
				</div>
			</aside>
		# END cat_list #
		# START last_articles #
			<aside class="block">
				<div class="wiki-list-container">
					<div class="wiki-list-top">
						# IF last_articles.C_ARTICLES #
							<a href="${relative_url(SyndicationUrlBuilder::rss('wiki'))}" aria-label="${LangLoader::get_message('syndication', 'common')}">
								<i class="fa fa-rss small" aria-hidden="true"></i>
							</a>
						# ENDIF #
						{last_articles.L_ARTICLES}
					</div>
					<div class="wiki-list-content">
						# START last_articles.list #
							<div class="wiki-list-item">
								<i class="fa fa-file-alt small" aria-hidden="true"></i> <a href="{PATH_TO_ROOT}/wiki/{last_articles.list.U_ARTICLE}" class="wiki-list-element">{last_articles.list.ARTICLE}</a>
							</div>
						# END last_articles.list #
						{L_NO_ARTICLE}
					</div>
					<div class="table-footer wiki-list-bottom"></div>
				</div>
			</aside>
		# END last_articles #

	</div>
	<footer></footer>
</section>
