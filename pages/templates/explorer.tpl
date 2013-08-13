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
