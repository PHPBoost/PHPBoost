		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">{TITLE}</div>
			<div class="module_contents" style="padding-bottom:65px;">
				# START redirect #
					<div class="row3" style="width:auto; float:left;">
					{redirect.REDIRECTED_FROM} {redirect.DELETE_REDIRECTION}
					</div>
				# END redirect #
				<noscript>
					<div class="row2" style="text-align:right;">
						&nbsp;
						# START link #
							<a href="{link.U_LINK}">{link.L_LINK}</a>
							# START separation #
								&bull;
							# END separation #
						# END link #
						&nbsp;
						<br />
					</div>
				</noscript>

				<div style="float:right">
					# IF C_ACTIV_COM #
					<img src="{PICTURES_DATA_PATH}/images/com.png" class="valign_middle" alt="" /> <a href="{U_COM}">{L_COM}</a>&nbsp;
					# ENDIF #
					# START links_list #
					{links_list.BULL}
					<img src="{links_list.DM_A_CLASS}" alt="" class="valign_middle" />
					<a href="{links_list.U_ACTION}" title="{links_list.L_ACTION}" onclick="{links_list.ONCLICK}" class="small_link">{links_list.L_ACTION}</a>
					
					# END links_list #
				</div>
				<div class="spacer">&nbsp;</div>
					{CONTENTS}
				<div class="spacer">&nbsp;</div>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom" style="text-align:center">{COUNT_HITS}</div>
		</div>