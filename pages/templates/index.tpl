<script type="text/javascript">
<!--
	var path = '{PICTURES_DATA_PATH}';
	var selected_cat = {SELECTED_CAT};
-->
</script>
<script type="text/javascript" src="{PICTURES_DATA_PATH}/js/pages.js"></script>

<section>
	<header>
		<h1>{TITLE}</h1>
	</header>
	<div class="content">
		{L_EXPLAIN_PAGES}	
		<hr />
		<div class="explorer">
			<div class="cats">
				<h1>{L_CATS}</h1>
				<div class="content">
					<ul>
						<li><a id="class_0" class="{CAT_0}" href="javascript:open_cat(0);"><i class="icon-folder"></i>{L_ROOT}</a>
							<ul>
								# START list #
									{list.DIRECTORY}
								# END list #
								{CAT_LIST}
							</ul>
						</li>
					</ul>
				</div>
			</div>
			<div class="files">
				<h1>{L_EXPLORER}</h1>
				<div class="content" id="cat_contents">
					<ul>
						{ROOT_CONTENTS}
					</ul>
				</div>
			</div>
		</div>
	</div>
	<footer></footer>
</section>