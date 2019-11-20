<script>
<!--
	var path = '{PICTURES_DATA_PATH}';
	var selected_cat = {SELECTED_CAT};
-->
</script>
<script src="{PATH_TO_ROOT}/pages/templates/js/pages.js"></script>

<section id="module-pages-explorer">
	<header>
		<h1>{TITLE}</h1>
	</header>
	<div class="content">
		<div class="explorer">
			<div class="cats">
				<h2>{TITLE}</h2>
				<div class="content">
					<ul>
						<li><a id="class-0" class="{CAT_0}" href="javascript:open_cat(0);"><i class="fa fa-folder" aria-hidden="true"></i>{L_ROOT}</a>
							<ul>
								# START list #
								<li class="sub">
									# IF list.U_FOLDER #
										<a class="parent" href="javascript:show_pages_cat_contents({list.ID}, 0);"><i class="fa fa-plus-square" id="img-subfolder-{list.ID}"></i><i id ="img-folder-{list.ID}" class="fa fa-folder" ></i></a>
										<a id="class-{list.ID}" href="javascript:open_cat({list.ID});">{list.TITLE}</a>
									# ELSE #
										<a id="class-{list.ID}" href="javascript:open_cat({list.ID});"><i class="fa fa-folder" aria-hidden="true"></i>{list.TITLE}</a>
									# ENDIF #

									<span id="cat-{list.ID}"></span>
								</li>
								# END list #
								{CAT_LIST}
							</ul>
						</li>
					</ul>
				</div>
			</div>
			<div class="files">
				<h2>{L_CATS}</h2>
				<div class="content" id="cat-contents">
					<ul>
						# START list_cats #
						<li>
							<a class="explorer-list-cat-link" href="javascript:open_cat({list_cats.KEY}); show_pages_cat_contents({list_cats.ID_PARENT}, 0);"><i class="fa fa-folder" aria-hidden="true"></i>{list_cats.TITLE}</a>
						</li>
						# END list_cats #
						# START list_files #
						<li>
							<a class="explorer-list-file-link" href="{list_files.URL_FILE}"><i class="fa fa-file" aria-hidden="true"></i>{list_files.TITLE}</a>
						</li>
						# END list_files #
					</ul>
				</div>
			</div>
		</div>
	</div>
	<footer></footer>
</section>
