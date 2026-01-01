<script>
	var selected_cat = {SELECTED_CAT};
</script>
<script src="{PATH_TO_ROOT}/wiki/templates/js/wiki# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>

<section id="module-wiki-explorer" class="explorer">
	<header class="section-header">
		<h1>{@wiki.explorer}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<div class="cell-flex cell-tile cell-columns-2">
				<div class="cats cell">
					<div class="cell-header">
						<h6 class="cell-name">{@wiki.categories.tree}</h6>
					</div>
					<div class="cell-list no-list">
						<ul>
							<li>
								<a id="class-0" class="{ROOT_CATEGORY}" href="javascript:open_cat(0);"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> {@common.root}</a>
								<ul>
									# START list #
										<li class="sub-cat-tree">
											# IF list.U_FOLDER #
												<a class="parent" href="javascript:show_wiki_cat_contents({list.ID}, 0);">
													<i class="far fa-fw fa-plus-square" id="img-subfolder-{list.ID}"></i>
													<i id="img-folder-{list.ID}" class="fa fa-fw fa-folder"></i>
												</a>
												<a id="class-{list.ID}" href="javascript:open_cat({list.ID});">{list.TITLE}</a>
											# ELSE #
												<a id="class-{list.ID}" href="javascript:open_cat({list.ID});"><i class="fa fa-fw fa-folder" aria-hidden="true"></i> {list.TITLE}</a>
											# ENDIF #
											<span id="cat-{list.ID}"></span>
										</li>
									# END list #
									{CATEGORIES_LIST}
								</ul>
							</li>
						</ul>
					</div>
				</div>
				<div class="files cell">
					<div class="cell-header">
						<h6 class="cell-name">{@wiki.content}</h6>
					</div>
					<div class="cell-list" id="cat-contents">
						<ul>
							# START list_cats #
								<li>
									<a class="explorer-list-cat-link" href="javascript:open_cat({list_cats.KEY}); show_wiki_cat_contents({list_cats.ID_PARENT}, 0);">
										<i class="fa fa-fw fa-folder" aria-hidden="true"></i> {list_cats.TITLE}
									</a>
								</li>
							# END list_cats #
							# START list_files #
								<li>
									<a class="explorer-list-file-link offload" href="{list_files.U_ITEM}">
										<i class="fa fa-fw fa-file" aria-hidden="true"></i> {list_files.TITLE}
									</a>
								</li>
							# END list_files #
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<footer></footer>
</section>
