		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_MEDIA}</h5>
			</div>
			<div class="module_mini_contents" style="text-align:left;">
				# IF L_NONE_MEDIA #
					<div style="text-align:center;font-weight:bold;">{L_NONE_MEDIA}</div>
				# ELSE #
				<ul style="margin:0;padding:0;">
					# START last_media #
					<li style="margin:0px;margin-bottom:6px;list-style-type:none;" class="small_text">
						<span style="font-weight:bold;font-size:14px">&bull;</span> <a class="small_link" href="{last_media.U_MEDIA}">{last_media.MEDIA}</a> 
						<br />
						{L_IN} <a class="small_link" href="{last_media.U_CAT}">{last_media.CAT}</a>
					</li>
					# END last_media #
				</ul>
				# ENDIF #
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
		