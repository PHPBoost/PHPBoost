<script type="text/javascript">
<!--
	var path = '{PICTURES_DATA_PATH}';
	var selected_cat = {SELECTED_CAT};
-->
</script>
<script type="text/javascript" src="{PICTURES_DATA_PATH}/images/pages.js"></script>

<menu class="dynamic_menu right">
	<ul>
		<li><a><i class="icon-cog"></i></a>
			<ul>
				# START tools #
					<li><a href="{tools.U_TOOL}">{tools.L_TOOL}</a></li>
				# END tools #
			</ul>
		</li>
	</ul>
</menu>

<section>
	<header>
		<h1>{TITLE}</h1>
	</header>
	<div class="content">
		<section class="block">
			<div class="contents">
				{L_EXPLAIN_PAGES}
			</div>
		</section>
		<div class="explorer">
			<div class="cats">
				<h1>{L_CATS}</h1>
				<div class="contents">
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
				<div class="contents" id="cat_contents">
					<ul>
						{ROOT_CONTENTS}
					</ul>
				</div>
			</div>
		</div>
	</div>
	<footer></footer>
</section>