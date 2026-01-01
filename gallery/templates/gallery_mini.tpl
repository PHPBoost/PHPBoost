<script>
	jQuery(document).ready(function() {
		var nbr_element = jQuery('#mini-gallery-slideshow li').length,
			windowHeight = jQuery('#scrolling-images').outerHeight();

		jQuery('.mini-picture').css('height', windowHeight);

		# IF C_HORIZONTAL_SCROLL #
			jQuery('#mini-gallery-slideshow').css('max-width', (nbr_element * 150) + 'px');
		# ENDIF #

		# IF NOT C_FADE #
			setInterval(function(){
				jQuery("#mini-gallery-slideshow").animate({# IF C_HORIZONTAL_SCROLL #marginLeft:-750# ELSE # marginTop:-150 # ENDIF #},1000,function(){
					jQuery(this).css({# IF C_HORIZONTAL_SCROLL #marginLeft# ELSE # marginTop # ENDIF #:0}).find("li").last().after(jQuery(this).find("li").first());
				})
			}, {SCROLL_DELAY});
		# ELSE #
			jQuery('#mini-gallery-slideshow li').first().show();
			setInterval(function() {
				jQuery("#mini-gallery-slideshow li").first().fadeOut(500, function() {
					jQuery('#mini-gallery-slideshow li').last().after($("#mini-gallery-slideshow li").first());
					jQuery('#mini-gallery-slideshow li').first().fadeIn(2000);
				});
			}, {SCROLL_DELAY});
		# ENDIF #
	});
</script>
# IF C_NO_ITEM #
	<div class="cell-alert">
		<div class="message-helper bgc notice">{@common.no.item.now}</div>
	</div>
# ELSE #
	<div class="cell-body">
		<div id="scrolling-images">
			# IF C_STATIC #
				# START pics_mini #
					<div class="mini-picture">
						<a class="offload" href="{pics_mini.U_ITEM}#pics_max">
							<img src="{pics_mini.U_THUMBNAIL}" alt="{pics_mini.NAME}" width="{pics_mini.WIDTH}" height="{pics_mini.HEIGHT}" />
						</a>
					</div>
				# END pics_mini #
			# ELSE #
				<ul id="mini-gallery-slideshow" class="# IF C_VERTICAL_SCROLL #vertical# ENDIF ## IF C_FADE #fade# ENDIF ## IF C_HORIZONTAL_SCROLL #horizontal# ENDIF #">
					# START pics_mini #
						<li class="mini-picture"><a class="offload" href="{pics_mini.U_ITEM}#pics_max"><img src="{pics_mini.U_THUMBNAIL}" alt="{pics_mini.NAME}" width="{pics_mini.WIDTH}" height="{pics_mini.HEIGHT}" /></a></li>
					# END pics_mini #
				</ul>
			# ENDIF #
		</div>
	</div>
# ENDIF #
<div class="cell-body">
	<div class="cell-content align-center">
		<a class="button small offload" href="{PATH_TO_ROOT}/gallery/gallery.php">{@gallery.module.title}</a>
	</div>
</div>
