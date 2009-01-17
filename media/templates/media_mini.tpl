		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_MEDIA}</h5>
			</div>
			<div class="module_mini_contents" style="text-align:left;margin-left:5px;">
				# IF L_NONE_MEDIA #
					<div style="text-align:center;font-weight:bold;">{L_NONE_MEDIA}</div>
				# ELSE #
				<ul style="margin-left:10px;">
					# START last_media #
					<li style="margin: 5px;list-style-type: decimal;">
						<a href="{last_media.U_MEDIA}">{last_media.MEDIA}</a> {L_IN} <a href="{last_media.U_CAT}">{last_media.CAT}</a>
					</li>
					# END last_media #
				</ul>
				# ENDIF #
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>