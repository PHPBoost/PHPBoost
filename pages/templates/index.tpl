<script type="text/javascript">
<!--
	var path = '{PICTURES_DATA_PATH}';
	var selected_cat = {SELECTED_CAT};
-->
</script>
<script type="text/javascript" src="{PICTURES_DATA_PATH}/images/pages.js"></script>

<section>
	<div class="content">
		<section class="block">
			<header>
				<h1>{TITLE}</h1>
			</header>
			<div class="contents">
				{L_EXPLAIN_PAGES}
			</div>
		</section>
		<section class="medium_block">
			<header>
				<h1>{L_TOOLS}</h1>
			</header>
			<div class="contents">
				<img src="{PICTURES_DATA_PATH}/images/tools_index.png" alt="{L_TOOLS}" class="center"/>
				<ul style="margin:auto;margin-left:20px;">
				# START tools #
					<li><a href="{tools.U_TOOL}">{tools.L_TOOL}</a></li>
				# END tools #
				</ul>
			</div>
		</section>
		<section class="medium_block">
			<header>
				<h1>{L_STATS}</h1>
			</header>
			<div class="contents">	
				<img src="{PICTURES_DATA_PATH}/images/stats.png" alt="{L_TOOLS}" class="center"/>
				<ul style="margin:auto; margin-left:20px;">
					<li>{NUM_PAGES}</li>
					<li>{NUM_COMS}</li>
				</ul>
			</div>
		</section>
		<div class="spacer"> </div>
		<div class="explorer">
			<div class="cats">
					<h1>{L_CATS}</h1>
				<div class="contents">
					<ul>
						<li><a id="class_0" class="{CAT_0}" href="javascript:open_cat(0);"><img src="{PICTURES_DATA_PATH}/images/cat_root.png" alt="" />{L_ROOT}</a>
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
</section>