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
			<menu class="tools_menu right group">
				<ul>
				# IF C_ACTIV_COM #
					<li>
						<a href="{U_COM}" ><i class="icon-comments"></i> {L_COM}</a>
					</li>
				# ENDIF #
					<li>
						<a><i class="icon-cog"></i> {L_OTHER_TOOLS}<a>
						<ul>
							# START contribution_tools #
							<li>
								<a href="{contribution_tools.U_ACTION}" title="{contribution_tools.L_ACTION}" onclick="{contribution_tools.ONCLICK}" style="background-image:url({PICTURES_DATA_PATH}/images/{contribution_tools.DM_A_CLASS});background-repeat:no-repeat;background-position:5px;">{contribution_tools.L_ACTION}</a>
							</li>
							# END contribution_tools #	
						</ul>
					</li>
					<li>
						<a><i class="icon-edit-sign"></i> {L_CONTRIBUTION_TOOLS}</a>
						<ul>
							# START other_tools #
							<li><a href="{other_tools.U_ACTION}" title="{other_tools.L_ACTION}" onclick="{other_tools.ONCLICK}" style="background-image:url({PICTURES_DATA_PATH}/images/{other_tools.DM_A_CLASS});background-repeat:no-repeat;background-position:5px;">{other_tools.L_ACTION}</a></li>
							# END other_tools #		
						</ul>
					</li>
				</ul>
			</menu>
		</div>
		<div class="spacer" style="margin-top:15px;">&nbsp;</div>
		