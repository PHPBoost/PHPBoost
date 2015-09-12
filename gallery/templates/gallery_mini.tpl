		<div class="module-mini-container">
			<div class="module-mini-top">
				<h5 class="sub-title">{L_RANDOM_PICS}</h5>
			</div>
			<div class="module-mini-contents" id="module-gallery-content">
				<div style="width:{WIDTH_DIV}px;height:{HEIGHT_DIV}px;margin:auto;">
					<div style="position:relative;width:{WIDTH_DIV}px;height:{HEIGHT_DIV}px;overflow:hidden;" id="scrolling_images">
						# IF C_STATIC #
							# START pics_mini #
							<a href="{pics_mini.U_PICS}#pics_max"><img src="{pics_mini.PICS}" alt="{pics_mini.NAME}" width="{pics_mini.WIDTH}px" height="{pics_mini.HEIGHT}px" /></a>
							# END pics_mini #
						# ELSE #
						<ul id="mini-gallery-slideshow" class="# IF C_VERTICAL_SCROLL #vertical# ENDIF ## IF C_FADE #fade# ENDIF ## IF C_HORIZONTAL_SCROLL #horizontal# ENDIF #">
							# START pics_mini #
							<li><a href="{pics_mini.U_PICS}#pics_max"><img src="{pics_mini.PICS}" alt="{pics_mini.NAME}" width="{pics_mini.WIDTH}px" height="{pics_mini.HEIGHT}px" /></a></li>
							# END pics_mini #
						</ul>
						# ENDIF #
					</div>
				</div>
				<script type="text/javascript">
					jQuery(document).ready(function() {
                        var nbr_element = jQuery('#mini-gallery-slideshow li').length;
                        jQuery('#mini-gallery-slideshow').css('visibility', 'visible');
                        # IF C_VERTICAL_SCROLL #
                            jQuery('#mini-gallery-slideshow').css('min-width', (nbr_element * 150) + 'px');
                        # ENDIF #

                        # IF NOT C_FADE #
                        setInterval(function(){
                            jQuery("#mini-gallery-slideshow").animate({# IF C_VERTICAL_SCROLL #marginLeft# ELSE # marginTop # ENDIF #:-150},1000,function(){
                                jQuery(this).css({# IF C_VERTICAL_SCROLL #marginLeft# ELSE # marginTop # ENDIF #:0}).find("li:last").after(jQuery(this).find("li:first"));
                            })
                        }, {SCROLL_DELAY});
                        # ELSE #
                        jQuery('#mini-gallery-slideshow li:first').show();
                        setInterval(function() {
                            jQuery("#mini-gallery-slideshow li:first").fadeOut(500, function() {
                                jQuery('#mini-gallery-slideshow li:last').after($("#mini-gallery-slideshow li:first"));
                                jQuery('#mini-gallery-slideshow li:first').fadeIn(2000);
                            });
                        }, {SCROLL_DELAY});
                        # ENDIF #
                    });
				</script>
				<a class="small" href="{PATH_TO_ROOT}/gallery/gallery.php">{L_GALLERY}</a>
			</div>
			<div class="module-mini-bottom">
			</div>
		</div>
		