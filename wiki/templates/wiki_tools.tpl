		<div style="margin-bottom:10px;">
			<menu class="dynamic_menu right group">
				<ul>
				# IF C_ACTIV_COM #
					<li>
						<a href="{U_COM}" ><i class="icon-comments"></i> {L_COM}</a>
					</li>
				# ENDIF #
					<li>
						<a><i class="icon-cog"></i> {L_OTHER_TOOLS}</a>
						<ul>
							# START contribution_tools #
							<li>
								<a href="{contribution_tools.U_ACTION}" title="{contribution_tools.L_ACTION}" onclick="{contribution_tools.ONCLICK}"><img src="{PICTURES_DATA_PATH}/images/{contribution_tools.DM_A_CLASS}"/> {contribution_tools.L_ACTION}</a>
							</li>
							# END contribution_tools #	
						</ul>
					</li>
					<li>
						<a><i class="icon-edit-sign"></i> {L_CONTRIBUTION_TOOLS}</a>
						<ul>
							# START other_tools #
							<li><a href="{other_tools.U_ACTION}" title="{other_tools.L_ACTION}" onclick="{other_tools.ONCLICK}"><img src="{PICTURES_DATA_PATH}/images/{other_tools.DM_A_CLASS}"/> {other_tools.L_ACTION}</a></li>
							# END other_tools #		
						</ul>
					</li>
				</ul>
			</menu>
		</div>
		<div  class="spacer" style="margin-top:15px;">&nbsp;</div>
		