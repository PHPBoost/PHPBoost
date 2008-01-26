		<noscript>
			<div class="row2" style="text-align:right;">
				&nbsp;
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

		<div id="dynamic_menu">
			<div style="float:right;">
				<div style="float:left;" onmouseover="show_menu(1);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(1)" style="margin-right:20px;" class="horizontal"><img src="{WIKI_PATH}/images/contribuate.png" class="valign_middle" alt="" />&nbsp;{L_OTHER_TOOLS}&nbsp;</h5>					
					<div id="smenu1" class="horizontal_block">
						<ul>
							# START contribution_tools #
							<li><a href="{contribution_tools.U_ACTION}" title="{contribution_tools.L_ACTION}" onclick="{contribution_tools.ONCLICK}" {contribution_tools.DM_A_CLASS}>{contribution_tools.L_ACTION}</a></li>
							# END contribution_tools #							
						</ul>
						<span class="dm_bottom"></span>
					</div>						
				</div>
				<div style="float:left;" onmouseover="show_menu(2);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(2)" style="margin-right:5px;" class="horizontal"><img src="{WIKI_PATH}/images/tools.png" class="valign_middle" alt="" />&nbsp;{L_CONTRIBUTION_TOOLS}&nbsp;</h5>					
					<div id="smenu2" class="horizontal_block">
						<ul>
							# START other_tools #
							<li><a href="{other_tools.U_ACTION}" title="{other_tools.L_ACTION}" onclick="{other_tools.ONCLICK}" {other_tools.DM_A_CLASS}>{other_tools.L_ACTION}</a></li>
							# END other_tools #							
						</ul>
						<span class="dm_bottom"></span>
					</div>						
				</div>
			</div>
		</div>
		