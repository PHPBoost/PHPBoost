		<script type="text/javascript">
		<!--
		var sum_height = {SUM_HEIGHT};
		var sum_width = {SUM_WIDTH};
		var hidden_height = {HIDDEN_HEIGHT};		
		var hidden_width = {HIDDEN_WIDTH};		
		var scroll_speed = {SCROLL_SPEED};
		var scroll_mode = '{SCROLL_MODE}';
		{ARRAY_PICS}
		-->
		</script>
		
		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_RANDOM_PICS}</h5>
			</div>
			<div class="module_mini_table">
				<div style="width:{WIDTH_DIV}px;height:{HEIGHT_DIV}px;overflow:hidden;text-align:center;margin:auto;position:relative;">	
					<div id="thumb_mini" style="left:0px;top:0px;position:relative;margin-top:5px;" onmouseover="temporize_scroll()" onmouseout="temporize_scroll();">
						# START vertical_scroll #	
							# START pics_mini #
						<a href="{vertical_scroll.pics_mini.U_PICS}#pics_max"><img src="{vertical_scroll.pics_mini.PICS}" alt="{vertical_scroll.pics_mini.NAME}" width="{vertical_scroll.pics_mini.WIDTH}" height="{vertical_scroll.pics_mini.HEIGHT}" /></a>
							# END pics_mini #
						# END vertical_scroll #
						
						# START horizontal_scroll #
						<table>
							<tr>
								# START pics_mini #
								<td style="padding:4px;"><a href="{horizontal_scroll.pics_mini.U_PICS}#pics_max"><img src="{horizontal_scroll.pics_mini.PICS}" alt="{horizontal_scroll.pics_mini.NAME}" width="{horizontal_scroll.pics_mini.WIDTH}" height="{horizontal_scroll.pics_mini.HEIGHT}" /></a></td>
								# END pics_mini #
							</tr>
						</table>
						# END horizontal_scroll #
					</div>
					{L_NO_RANDOM_PICS}
				</div>
				<div>
					<a class="small_link" href="../gallery/gallery.php{SID}">{L_GALLERY}</a>
				</div>
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
		<script type="text/javascript" src="{MODULE_DATA_PATH}/images/js/scroll.js"></script>
		