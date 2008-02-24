		<div id="sub_header">
			<div id="sub_header_left">				
			</div>
			<div id="sub_header_right">
			</div>		
			
			<div id="dynamic_menu" style="padding-top:9px;">
				<ul>
					<li onmouseover="show_menu(1, 0);" onmouseout="hide_menu(0);">
						<h5 class="links"><img src="../templates/{THEME}/images/admin/admin_mini.png" class="valign_middle" alt="" /> {L_INDEX}</h5>
						<ul id="smenu1">
							<li><a href="{U_INDEX_SITE}" style="background-image:url(../templates/{THEME}/images/admin/admin_mini.png);">{L_INDEX_SITE}</a></li>
							<li><a href="../admin/admin_index.php" style="background-image:url(../templates/{THEME}/images/admin/ranks_mini.png);">{L_INDEX_ADMIN}</a></li>
							<li><a href="../admin/admin_index.php?disconnect=true" style="background-image:url(../templates/{THEME}/images/admin/home_mini.png);">{L_DISCONNECT}</a></li>
						</ul>
					</li>
					<li onmouseover="show_menu(2, 0);" onmouseout="hide_menu(0);">
						<h5 class="links"><img src="../templates/{THEME}/images/admin/site_mini.png" class="valign_middle" alt="" /> {L_SITE}</h5>
						<ul id="smenu2">
							<li class="extend" onmouseover="show_menu(21, 1);" onmouseout="hide_menu(1);">
								<a href="../admin/admin_config.php" style="background-image:url(../templates/{THEME}/images/admin/config_mini.png);">{L_CONFIGURATION}</a>
								<ul id="ssmenu21">
									<li><a href="../admin/admin_config.php" style="background-image:url(../templates/{THEME}/images/admin/config_mini.png);">{L_MANAGEMENT}</a></li>
									<li><a href="../admin/admin_config.php?adv=1" style="background-image:url(../templates/{THEME}/images/admin/config_mini.png);">{L_CONFIG_ADVANCED}</a></li>
								</ul>
							</li>
							<li class="extend" onmouseover="show_menu(22, 1);" onmouseout="hide_menu(1);">
								<a href="../admin/admin_modules.php" style="background-image:url(../templates/{THEME}/images/admin/modules_mini.png);">{L_MODULES}</a>
								<ul id="ssmenu22">
									<li><a href="../admin/admin_modules.php" style="background-image:url(../templates/{THEME}/images/admin/modules_mini.png);">{L_MANAGEMENT}</a></li>
									<li><a href="../admin/admin_modules_add.php" style="background-image:url(../templates/{THEME}/images/admin/modules_mini.png);">{L_ADD}</a></li>
									<li><a href="../admin/admin_config_update.php" style="background-image:url(../templates/{THEME}/images/admin/modules_mini.png);">{L_UPDATE_MODULES}</a></li>
								</ul>
							</li>
							<li class="extend" onmouseover="show_menu(23, 1);" onmouseout="hide_menu(1);">
								<a href="../admin/admin_themes.php" style="background-image:url(../templates/{THEME}/images/admin/themes_mini.png);">{L_THEME}</a>
								<ul id="ssmenu23">
									<li><a href="../admin/admin_themes.php" style="background-image:url(../templates/{THEME}/images/admin/themes_mini.png);">{L_MANAGEMENT}</a></li>
									<li><a href="../admin/admin_themes_add.php" style="background-image:url(../templates/{THEME}/images/admin/themes_mini.png);">{L_ADD}</a></li>
								</ul>
							</li>
							<li class="extend" onmouseover="show_menu(24, 1);" onmouseout="hide_menu(1);">
								<a href="../admin/admin_lang.php" style="background-image:url(../templates/{THEME}/images/admin/languages_mini.png);">{L_LANG}</a>
								<ul id="ssmenu24">
									<li><a href="../admin/admin_lang.php" style="background-image:url(../templates/{THEME}/images/admin/languages_mini.png);">{L_MANAGEMENT}</a></li>
									<li><a href="../admin/admin_lang_add.php" style="background-image:url(../templates/{THEME}/images/admin/languages_mini.png);">{L_ADD}</a></li>
								</ul>
							</li>
						</ul>
					</li>
					<li onmouseover="show_menu(3, 0);" onmouseout="hide_menu(0);">
						<h5 class="links"><img src="../templates/{THEME}/images/admin/tools_mini.png" class="valign_middle" alt="" /> {L_TOOLS}</h5>
						<ul id="smenu3">
							<li><a href="../admin/admin_maintain.php" style="background-image:url(../templates/{THEME}/images/admin/maintain_mini.png);">{L_MAINTAIN}</a></li>
							<li class="extend" onmouseover="show_menu(31, 1);" onmouseout="hide_menu(1);">
								<a href="../admin/admin_database.php" style="background-image:url(../templates/{THEME}/images/admin/database_mini.png);">{L_SITE_DATABASE}</a>
								<ul id="ssmenu31">
									<li><a href="../admin/admin_database.php" style="background-image:url(../templates/{THEME}/images/admin/database_mini.png);">{L_MANAGEMENT}</a></li>
									<li><a href="../admin/admin_database.php?query=1" style="background-image:url(../templates/{THEME}/images/admin/database_mini.png);">{L_DATABASE_QUERY}</a></li>
								</ul>
							</li>
							<li><a href="../admin/admin_cache.php" style="background-image:url(../templates/{THEME}/images/admin/cache_mini.png);">{L_CACHE}</a></li>		
							<li><a href="../admin/admin_stats.php" style="background-image:url(../templates/{THEME}/images/admin/stats_mini.png);">{L_STATS}</a></li>
							<li><a href="../admin/admin_errors.php" style="background-image:url(../templates/{THEME}/images/admin/errors_mini.png);">{L_ERRORS}</a></li>
							<li><a href="../admin/admin_phpinfo.php" style="background-image:url(../templates/{THEME}/images/admin/phpinfo_mini.png);">{L_PHPINFO}</a></li>
						</ul>
					</li>
					<li onmouseover="show_menu(4, 0);" onmouseout="hide_menu(0);">
						<h5 class="links"><img src="../templates/{THEME}/images/admin/groups_mini.png" class="valign_middle" alt="" /> {L_MEMBER}</h5>
						<ul id="smenu4">
							<li class="extend" onmouseover="show_menu(41, 1);" onmouseout="hide_menu(1);">
								<a href="../admin/admin_members.php" style="background-image:url(../templates/{THEME}/images/admin/members_mini.png);">{L_MEMBER}</a>
								<ul id="ssmenu41">
									<li><a href="../admin/admin_members.php" style="background-image:url(../templates/{THEME}/images/admin/members_mini.png);">{L_MANAGEMENT}</a></li>
									<li><a href="../admin/admin_members.php?add=1" style="background-image:url(../templates/{THEME}/images/admin/members_mini.png);">{L_ADD}</a></li>
									<li><a href="../admin/admin_members_config.php" style="background-image:url(../templates/{THEME}/images/admin/members_mini.png);">{L_CONFIGURATION}</a></li>
									<li><a href="../admin/admin_members_punishement.php" style="background-image:url(../templates/{THEME}/images/admin/members_mini.png);">{L_PUNISHEMENT}</a></li>
								</ul>
							</li>								
							<li class="extend" onmouseover="show_menu(42, 1);" onmouseout="hide_menu(1);">
								<a href="../admin/admin_groups.php" style="background-image:url(../templates/{THEME}/images/admin/groups_mini.png);">{L_GROUP}</a>
								<ul id="ssmenu42">
									<li><a href="../admin/admin_groups.php" style="background-image:url(../templates/{THEME}/images/admin/groups_mini.png);">{L_MANAGEMENT}</a></li>
									<li><a href="../admin/admin_groups.php?add=1" style="background-image:url(../templates/{THEME}/images/admin/groups_mini.png);">{L_ADD}</a></li>
								</ul>
							</li>
							<li onmouseover="show_menu(43, 1);" onmouseout="hide_menu(1);">
								<a href="../admin/admin_extend_field.php" style="background-image:url(../templates/{THEME}/images/admin/extendfield_mini.png);">{L_EXTEND_FIELD}</a>
								<ul id="ssmenu43">
									<li><a href="../admin/admin_extend_field.php" style="background-image:url(../templates/{THEME}/images/admin/extendfield_mini.png);">{L_MANAGEMENT}</a></li>
									<li><a href="../admin/admin_extend_field_add.php" style="background-image:url(../templates/{THEME}/images/admin/extendfield_mini.png);">{L_ADD}</a></li>
								</ul>
							</li>
							<li class="extend" onmouseover="show_menu(44, 1);" onmouseout="hide_menu(1);">
								<a href="../admin/admin_ranks.php" style="background-image:url(../templates/{THEME}/images/admin/ranks_mini.png);">{L_RANKS}</a>
								<ul id="ssmenu44">
									<li><a href="../admin/admin_ranks.php" style="background-image:url(../templates/{THEME}/images/admin/ranks_mini.png);">{L_MANAGEMENT}</a></li>
									<li><a href="../admin/admin_ranks_add.php" style="background-image:url(../templates/{THEME}/images/admin/ranks_mini.png);">{L_ADD}</a></li>
								</ul>
							</li>
							<li><a href="../admin/admin_terms.php" style="background-image:url(../templates/{THEME}/images/admin/terms_mini.png);">{L_TERMS}</a></li>
						</ul>
					</li>
					<li onmouseover="show_menu(5, 0);" onmouseout="hide_menu(0);">
						<h5 class="links"><img src="../templates/{THEME}/images/admin/contents_mini.png" class="valign_middle" alt="" /> {L_CONTENTS}</h5>
						<ul id="smenu5">
							<li class="extend" onmouseover="show_menu(51, 1);" onmouseout="hide_menu(1);">
								<a href="../admin/admin_menus.php" style="background-image:url(../templates/{THEME}/images/admin/menus_mini.png);">{L_SITE_MENU}</a>
								<ul id="ssmenu51">
									<li><a href="../admin/admin_menus.php" style="background-image:url(../templates/{THEME}/images/admin/menus_mini.png);">{L_MANAGEMENT}</a></li>
									<li><a href="../admin/admin_menus_add.php" style="background-image:url(../templates/{THEME}/images/admin/menus_mini.png);">{L_ADD}</a></li>
								</ul>
							</li>
							<li class="extend" onmouseover="show_menu(52, 1);" onmouseout="hide_menu(1);">
								<a href="../admin/admin_files.php" style="background-image:url(../templates/{THEME}/images/admin/files_mini.png);">{L_FILES}</a>
								<ul id="ssmenu52">
									<li><a href="../admin/admin_files.php" style="background-image:url(../templates/{THEME}/images/admin/files_mini.png);">{L_MANAGEMENT}</a></li>
									<li><a href="../admin/admin_files_config.php" style="background-image:url(../templates/{THEME}/images/admin/files_mini.png);">{L_CONFIGURATION}</a></li>
								</ul>
							</li>								
							<li class="extend" onmouseover="show_menu(53, 1);" onmouseout="hide_menu(1);">
								<a href="../admin/admin_com.php" style="background-image:url(../templates/{THEME}/images/admin/com_mini.png);">{L_COMMENTS}</a>
								<ul id="ssmenu53">
									<li><a href="../admin/admin_com.php" style="background-image:url(../templates/{THEME}/images/admin/com_mini.png);">{L_MANAGEMENT}</a></li>
									<li><a href="../admin/admin_com_config.php" style="background-image:url(../templates/{THEME}/images/admin/com_mini.png);">{L_CONFIGURATION}</a></li>
								</ul>
							</li>
						</ul>
					</li>
					<li onmouseover="show_menu(6, 0);" onmouseout="hide_menu(0);">
						<h5 class="links"><img src="../templates/{THEME}/images/admin/modules_mini.png" class="valign_middle" alt="" /> {L_MODULES}</h5>
						<ul id="smenu6">
							<li class="extend" onmouseover="show_menu(61, 1);" onmouseout="hide_menu(1);">
								<a href="../admin/admin_modules.php" style="background-image:url(../templates/{THEME}/images/admin/modules_mini.png);">{L_MODULES}</a>
								<ul id="ssmenu61">
									<li><a href="../admin/admin_modules.php" style="background-image:url(../templates/{THEME}/images/admin/modules_mini.png);">{L_MANAGEMENT}</a></li>
									<li><a href="../admin/admin_modules_add.php" style="background-image:url(../templates/{THEME}/images/admin/modules_mini.png);">{L_ADD}</a></li>
									<li><a href="../admin/admin_modules_update.php" style="background-image:url(../templates/{THEME}/images/admin/modules_mini.png);">{L_UPDATE_MODULES}</a></li>
								</ul>
							</li>
							<li class="separator"></li>
							# START modules #
							<li><a href="{modules.U_ADMIN_MODULE}" {modules.DM_A_STYLE}>{modules.NAME}</a></li>				
							# END modules #
						</ul>
					</li>
				</ul>
			</div>
			<div style="float:right;margin-top:4px;">
				<a href="../admin/admin_extend.php" style="text-decoration:none;color:#FFFFFF;"><img src="../templates/{THEME}/images/admin/extendfield_mini.png" class="valign_middle" alt="" /> {L_EXTEND_MENU}</a></a>
			</div>	
		</div>
			
		<div id="admin_main">
			&nbsp;