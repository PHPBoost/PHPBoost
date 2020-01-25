<script>
	var path = '{PICTURES_DATA_PATH}';
	var selected_cat = {SELECTED_CAT};
</script>
<script src="{PATH_TO_ROOT}/pages/templates/js/pages.js"></script>

<section id="module-pages">
	<header>
		<h1>{TITLE}</h1>
	</header>
	<div class="content">
		{L_EXPLAIN_PAGES}
		<hr />
		<div class="explorer">
			<div class="cats">
				<h2>{L_EXPLORER}</h2>
				<div class="content">
					<ul>
						<li><a id="class-0" class="{CAT_0}" href="javascript:open_cat(0);"><i class="fa fa-folder"></i>{L_ROOT}</a>
							<ul>
								# START list #
									<li class="sub">
										# IF list.C_SUB_CAT #
											<a class="parent" href="javascript:show_pages_cat_contents({list.ID}, 0);" aria-label="${LangLoader::get_message('display', 'common')}">
												<i class="fa fa-plus-square" id="img-subfolder-{list.ID}"></i>
												<i class="fa fa-folder" id ="img-folder-{list.ID}"></i>
											</a>
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
						{ROOT_CONTENTS}
					</ul>
				</div>
			</div>
		</div>
	</div>
	<footer></footer>
</section>
