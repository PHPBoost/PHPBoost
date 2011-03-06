		<noscript>
			<div class="row2" style="text-align:right;">
				&nbsp;
				<a href="{U_COM}">{L_COM}</a>&bull;
				# START tool #
					<a href="{tool.U_TOOL}">{tool.L_TOOL}</a>
					# START tool.separation #
						&bull;
					# END tool.separation #
				# END tool #
				&nbsp;
				<br />
			</div>
		</noscript>

		<div style="margin-bottom:10px;">
			<div class="dynamic_menu" style="float:right;margin-right:55px;">
				<ul>
				# IF C_ACTIV_COM #
					<li>
						<h5 style="margin-right:20px;">
							<img src="{PICTURES_DATA_PATH}/images/com.png" class="valign_middle" alt="" />
							<a href="{U_COM}">{L_COM}</a>
						</h5>
					</li>
				# ENDIF #
					<li onmouseover="show_menu(1, 0);" onmouseout="hide_menu(0);">
						<h5 style="margin-right:20px;"><img src="{PICTURES_DATA_PATH}/images/contribuate.png" class="valign_middle" alt="" /> {L_OTHER_TOOLS}</h5>
						<ul id="smenu1">
							# START contribution_tools #
							<li>
								<a href="{contribution_tools.U_ACTION}" title="{contribution_tools.L_ACTION}" onclick="{contribution_tools.ONCLICK}" style="background-image:url({PICTURES_DATA_PATH}/images/{contribution_tools.DM_A_CLASS});background-repeat:no-repeat;background-position:5px;">{contribution_tools.L_ACTION}</a>
							</li>
							# END contribution_tools #	
						</ul>
					</li>
					<li onmouseover="show_menu(2, 0);" onmouseout="hide_menu(0);">
						<h5 style="margin-right:5px;"><img src="{PICTURES_DATA_PATH}/images/tools.png" class="valign_middle" alt="" /> {L_CONTRIBUTION_TOOLS}</h5>
						<ul id="smenu2">
							# START other_tools #
							<li><a href="{other_tools.U_ACTION}" title="{other_tools.L_ACTION}" onclick="{other_tools.ONCLICK}" style="background-image:url({PICTURES_DATA_PATH}/images/{other_tools.DM_A_CLASS});background-repeat:no-repeat;background-position:5px;">{other_tools.L_ACTION}</a></li>
							# END other_tools #		
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<div  class="spacer" style="margin-top:15px;">&nbsp;</div>
		