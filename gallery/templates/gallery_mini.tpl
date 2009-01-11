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
			<div class="module_mini_contents">
				<div style="width:{WIDTH_DIV}px;margin:auto;">	
					<div style="position:relative;width:{WIDTH_DIV}px;height:{HEIGHT_DIV}px;overflow:hidden;">	
						<div id="thumb_mini" style="left:0px;top:0px;position:relative;margin-top:5px;" onmouseover="temporize_scroll()" onmouseout="temporize_scroll();">
							# IF C_VERTICAL_SCROLL #	
								# START pics_mini #
							<a href="{vertical_scroll.pics_mini.U_PICS}#pics_max"><img src="{vertical_scroll.pics_mini.PICS}" alt="{vertical_scroll.pics_mini.NAME}" width="{vertical_scroll.pics_mini.WIDTH}px" height="{vertical_scroll.pics_mini.HEIGHT}px" /></a>
								# END pics_mini #
							# ENDIF #
							
							# IF C_HORIZONTAL_SCROLL #
							<table>
								<tr>
									# START pics_mini #
									<td style="padding:4px;"><a href="{horizontal_scroll.pics_mini.U_PICS}#pics_max"><img src="{horizontal_scroll.pics_mini.PICS}" alt="{horizontal_scroll.pics_mini.NAME}" width="{horizontal_scroll.pics_mini.WIDTH}" height="{horizontal_scroll.pics_mini.HEIGHT}" /></a></td>
									# END pics_mini #
								</tr>
							</table>
							# ENDIF #
							{L_NO_RANDOM_PICS}
						</div>
					</div>
				</div>
				<a class="small_link" href="{PATH_TO_ROOT}/gallery/gallery.php?{SID}">{L_GALLERY}</a>
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
		<script type="text/javascript" src="{MODULE_DATA_PATH}/images/js/scroll.js"></script>
		