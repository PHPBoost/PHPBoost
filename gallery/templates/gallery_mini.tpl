		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_RANDOM_PICS}</h5>
			</div>
			<div class="module_mini_contents">
				<div style="width:{WIDTH_DIV}px;height:{HEIGHT_DIV}px;margin:auto;">
					<div style="position:relative;width:{WIDTH_DIV}px;height:{HEIGHT_DIV}px;overflow:hidden;" id="scrolling_images">
						# IF C_STATIC #
							# START pics_mini #
							<a href="{pics_mini.U_PICS}#pics_max"><img src="{pics_mini.PICS}" alt="{pics_mini.NAME}" width="{pics_mini.WIDTH}px" height="{pics_mini.HEIGHT}px" /></a>
							# END pics_mini #
						# ELSE #
						<script src="{PATH_TO_ROOT}/gallery/templates/images/js/marquee.js" type="text/javascript"></script>
						<script type="text/javascript">
						<!--
							new Marquee({
							element: "scrolling_images",
							# IF C_VERTICAL_SCROLL #	
							animIn: Marquee.blindIn,
							animOut: Marquee.blindOut,
							# ENDIF #
							# IF C_HORIZONTAL_SCROLL #
							animIn: Marquee.slideOut,
							animOut: Marquee.slideIn,
							# ENDIF #
							# IF C_FADE #
							animIn: Marquee.fadeIn,
							animOut: Marquee.fadeOut,
							# ENDIF #
							delay: {SCROLL_DELAY},
							data: [
							# START pics_mini #
							{ message: '<a href="{pics_mini.U_PICS}#pics_max"><img src="{pics_mini.PICS}" alt="{pics_mini.NAME}" width="{pics_mini.WIDTH}px" height="{pics_mini.HEIGHT}px" /></a>' },
							# END pics_mini #
							]
							});
						-->
						</script>
						# ENDIF #
					</div>
				</div>
				<a class="small_link" href="{PATH_TO_ROOT}/gallery/gallery.php{SID}">{L_GALLERY}</a>
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
		<span id="test"></span>
		