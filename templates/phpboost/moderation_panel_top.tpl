		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">&bull; <a href="moderation_panel.php">{L_MODERATION_PANEL}</a></div>
			<div class="module_contents" style="padding-bottom:75px;">
				<div style="margin-bottom:50px;">
					<div id="dynamic_menu" style="float:right;margin-right:55px;">
						<ul>
							<li onmouseover="show_menu(1, 0);" onmouseout="hide_menu(0);">
								<h5 style="margin-right:25px;"><img src="../templates/{THEME}/images/admin/members_mini.png" class="valign_middle" alt="" /> {L_MEMBERS}</h5>
								<ul id="smenu1">
									<li><a href="moderation_panel{U_WARNING}" style="background-image:url(../templates/{THEME}/images/admin/important.png);">{L_WARNING}</a></li>
									<li><a href="moderation_panel{U_PUNISH}" style="background-image:url(../templates/{THEME}/images/admin/stop.png);background-repeat:no-repeat;background-position:5px;">{L_PUNISHMENT}</a></li>
									<li><a href="moderation_panel{U_BAN}" style="background-image:url(../templates/{THEME}/images/admin/forbidden.png);background-repeat:no-repeat;background-position:5px;">{L_BAN}</a></li>
								</ul>
							</li>
							<li onmouseover="show_menu(2, 0);" onmouseout="hide_menu(0);">
								<h5 style="margin-right:20px;"><img src="../templates/{THEME}/images/admin/modules_mini.png" class="valign_middle" alt="" /> {L_MODULES}</h5>
								<ul id="smenu2">
									# START list_modules #
									<li><a href="../{list_modules.MOD_NAME}/{list_modules.U_LINK}" {list_modules.DM_A_CLASS}>{list_modules.NAME}</a></li>
									# END list_modules #
								</ul>
							</li>
						</ul>
					</div>
				</div>
				