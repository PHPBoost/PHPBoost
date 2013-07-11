		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div class="module_top_title">{TITLE}</div>
			</div>
			<div class="module_contents" style="padding-bottom:85px;">
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

				<div style="margin-bottom:10px;">
					<div class="dynamic_menu" style="float:right;margin-right:70px;">
						<ul>
						# IF C_ACTIV_COM #
							<li>
								<h5 style="margin-right:20px;">
									<img src="{PICTURES_DATA_PATH}/images/com.png" class="valign_middle" alt="" /> 
									<a href="{U_COM}" >{L_COM}</a>
								</h5>
							</li>
						# ENDIF #
							<li onmouseover="show_menu(1, 0);" onmouseout="hide_menu(0);">
								<h5 style="margin-right:25px;"><img src="{PICTURES_DATA_PATH}/images/tools.png" class="valign_middle" alt="" />{L_PAGE_OUTILS}</h5>
								<ul id="smenu1">
								# START links_list #
									<li><a href="{links_list.U_ACTION}" title="{links_list.L_ACTION}" onclick="{links_list.ONCLICK}" class="small_link" style="background-image:url({links_list.DM_A_CLASS});background-repeat:no-repeat;background-position:5px;">{links_list.L_ACTION}</a></li>
								# END links_list #
								</ul>
							</li>
						</ul>
					</div>	
				</div>			
				<div class="spacer">&nbsp;</div>
					{CONTENTS}
				<div class="spacer">&nbsp;</div>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom" style="text-align:center">{COUNT_HITS}</div>
		</div>