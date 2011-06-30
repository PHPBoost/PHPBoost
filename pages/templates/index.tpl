<script type="text/javascript">
<!--
	var path = '{PICTURES_DATA_PATH}';
	var selected_cat = {SELECTED_CAT};
-->
</script>
<script type="text/javascript" src="{PICTURES_DATA_PATH}/images/pages.js"></script>

<table class="module_table">
	<tr>
		<th colspan="2">
			{TITLE}
		</th>
	</tr>
	<tr>
		<td class="row1" colspan="2">
			{L_EXPLAIN_PAGES}
		</td>
	</tr>
	<tr>
		<th style="width:50%;">	
			{L_TOOLS}
		</th>
		<th>	
			{L_STATS}
		</th>
	</tr>
	<tr>
		<td class="row2">	
			<div style="text-align:center;">
				<img src="{PICTURES_DATA_PATH}/images/tools_index.png" alt="{L_TOOLS}" />
			</div>
			<ul style="margin:auto;margin-left:20px;">
			# START tools #
				<li><a href="{tools.U_TOOL}">{tools.L_TOOL}</a></li>
			# END tools #
			</ul>
		</td>
		<td class="row2">
			<div style="text-align:center;">
				<img src="{PICTURES_DATA_PATH}/images/stats.png" alt="{L_TOOLS}" />
			</div>
			<ul style="margin:auto; margin-left:20px;">
				<li>{NUM_PAGES}</li>
				<li>{NUM_COMS}</li>
			</ul>
		</td>
	</tr>
	<tr>
		<th colspan="2">
			{L_EXPLORER}
		</th>
	</tr>
	<tr>
		<td colspan="2" style="padding:0;">
			<table style="border-collapse:collapse; width:100%;">
				<tr>
					<td style="width:200px;" class="row1">
						{L_CATS}
					</td>
					<td id="cat_contents" class="row2" rowspan="2" style="vertical-align:top;">
						<table style="width:100%;">
							{ROOT_CONTENTS}
						</table>
					</td>
				</tr>
				<tr>
					<td style="width:200px; vertical-align:top;" class="row2">
						<div style="overflow-x:auto; width:200px;">
							<span style="padding-left:17px;"><a href="javascript:open_cat(0);"><img src="{PICTURES_DATA_PATH}/images/cat_root.png" alt="" /> <span id="class_0" class="{CAT_0}">{L_ROOT}</span></a></span>
							<br />
							<ul style="margin:0;padding:0;list-style-type:none;line-height:normal;">
							# START list #
								{list.DIRECTORY}
							# END list #
							{CAT_LIST}
							</ul>
						</div>
						<br /><br />
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>