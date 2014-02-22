		# START item #
		<!-- ITEM -->
			<div class="news_container" style="float:left;width:365px;margin-left:10px;">
				<div class="news_top_l"></div>
				<div class="news_top_r"></div>
				<div class="news_top">
					<h3 class="title valign_middle">
						<a href="{item.U_LINK}">{item.TITLE}</a><span class="text_small" style="float:right;position:relative;top:-13px;">{item.DATE}</span>
					</h3>
				</div>
				<div class="news_content">
					<div style="min-height:125px;">
					# IF item.C_IMG #
						<div style="float:right;margin-left:10px;margin-bottom:5px;margin-right:0px;">
							<img src="{item.U_IMG}" title="{item.TITLE}" style="max-width:150px;max-height:150px;"/>
						</div>
					# END IF #
					{item.DESC}
					</div>
					<div style="text-align:right;"><a href="./news/news.php" class="small_link">Plus de news...</a></div>
				</div>
				<div class="news_bottom_l"></div>
				<div class="news_bottom_r"></div>
				<div class="news_bottom"></div>
			</div>
		<!-- END ITEM -->
		# END item #
