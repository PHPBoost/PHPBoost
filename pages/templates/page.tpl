		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">{TITLE}</div>
			<div class="module_contents">
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

				<div id="dynamic_menu">
					<div style="float:right;">
						# START com #
						<div style="float:left;">
							<h5 style="margin-right:20px;" class="horizontal">
								<img src="{PAGES_PATH}/images/com.png" style="vertical-align:middle;" alt="" />&nbsp;<a href="{com.U_COM}">{com.L_COM}</a>
							</h5>
						</div>
						# END com #
						<div style="float:left;" onmouseover="show_menu(1);" onmouseout="hide_menu();">
							<h5 onclick="temporise_menu(1)" style="margin-right:20px;" class="horizontal"><img src="{PAGES_PATH}/images/tools.png" style="vertical-align:middle;" alt="" />&nbsp;{L_LINKS}&nbsp;</h5>					
							<div id="smenu1" class="horizontal_block">
								<ul>
									# START links_list #
									<li><a href="{links_list.U_ACTION}" title="{links_list.L_ACTION}" onclick="{links_list.ONCLICK}" {links_list.DM_A_CLASS}>{links_list.L_ACTION}</a></li>
									# END links_list #	
								</ul>
								<span class="dm_bottom"></span>
							</div>						
						</div>
					</div>
				</div>
				<br /><br /><br />
				{CONTENTS}
				<br /><br />
				<div class="spacer">&nbsp;</div>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom" style="text-align:center">{COUNT_HITS}</div>
		</div>