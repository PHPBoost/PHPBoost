		<div id="dynamic_menu">
			<div style="float:left;">				
				<div style="float:left;" onmouseover="show_menu(1);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(1)" class="horizontal"><img src="../templates/{THEME}/images/admin/index_mini.png" class="valign_middle" alt="" /> {L_INDEX}</h5>					
					<div id="smenu1" class="horizontal_block">
						<ul>
							<li><a href="{U_INDEX_SITE}" style="background-image:url(../templates/{THEME}/images/admin/admin_mini.png);background-repeat:no-repeat;background-position:5px;">{L_INDEX_SITE}</a></li>
							<li><a href="../admin/admin_index.php" style="background-image:url(../templates/{THEME}/images/admin/ranks_mini.png);background-repeat:no-repeat;background-position:5px;">{L_INDEX_ADMIN}</a></li>								<li><a href="../admin/admin_index.php?disconnect=true" style="background-image:url(../templates/{THEME}/images/admin/home_mini.png);background-repeat:no-repeat;background-position:5px;">{L_DISCONNECT}</a></li>
						</ul>
						<span class="dm_bottom"></span>
					</div>						
				</div>
				<div style="float:left;" onmouseover="show_menu(2);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(2)" class="horizontal"><img src="../templates/{THEME}/images/admin/site_mini.png" class="valign_middle" alt="" /> {L_SITE}</h5>					
					<div id="smenu2" class="horizontal_block">
						<ul>
							<li><a href="../admin/admin_config.php" style="background-image:url(../templates/{THEME}/images/admin/config_mini.png);background-repeat:no-repeat;background-position:5px;">{L_CONFIGURATION}</a></li>
							<li><a href="../admin/admin_modules.php" style="background-image:url(../templates/{THEME}/images/admin/modules_mini.png);background-repeat:no-repeat;background-position:5px;">{L_MODULES}</a></li>
							<li><a href="../admin/admin_themes.php" style="background-image:url(../templates/{THEME}/images/admin/themes_mini.png);background-repeat:no-repeat;background-position:5px;">{L_THEME}</a></li>
							<li><a href="../admin/admin_lang.php" style="background-image:url(../templates/{THEME}/images/admin/languages_mini.png);background-repeat:no-repeat;background-position:5px;">{L_LANG}</a></li>
							<li><a href="../admin/admin_smileys.php" style="background-image:url(../templates/{THEME}/images/admin/smileys_mini.png);background-repeat:no-repeat;background-position:5px;">{L_SMILEY}</a></li>
						</ul>
						<span class="dm_bottom"></span>
					</div>						
				</div>
				<div style="float:left;" onmouseover="show_menu(3);" onmouseout="hide_menu();">					
					<h5 onclick="temporise_menu(3)" class="horizontal"><img src="../templates/{THEME}/images/admin/tools_mini.png" class="valign_middle" alt="" /> {L_TOOLS}</h5>
					<div id="smenu3" class="horizontal_block">
						<ul>							
							<li><a href="../admin/admin_maintain.php" style="background-image:url(../templates/{THEME}/images/admin/maintain_mini.png);background-repeat:no-repeat;background-position:5px;">{L_MAINTAIN}</a></li>
							<li><a href="../admin/admin_database.php" style="background-image:url(../templates/{THEME}/images/admin/database_mini.png);background-repeat:no-repeat;background-position:5px;">{L_SITE_DATABASE}</a></li>
							<li><a href="../admin/admin_cache.php" style="background-image:url(../templates/{THEME}/images/admin/cache_mini.png);background-repeat:no-repeat;background-position:5px;">{L_CACHE}</a></li>		
							<li><a href="../admin/admin_stats.php" style="background-image:url(../templates/{THEME}/images/admin/stats_mini.png);background-repeat:no-repeat;background-position:5px;">{L_STATS}</a></li>
							<li><a href="../admin/admin_errors.php" style="background-image:url(../templates/{THEME}/images/admin/errors_mini.png);background-repeat:no-repeat;background-position:5px;">{L_ERRORS}</a></li>
							<li><a href="../admin/admin_phpinfo.php" style="background-image:url(../templates/{THEME}/images/admin/phpinfo_mini.png);background-repeat:no-repeat;background-position:5px;">{L_PHPINFO}</a></li>
						</ul>
						<span class="dm_bottom"></span>
					</div>
				</div>
				<div style="float:left;" onmouseover="show_menu(4);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(4)" class="horizontal"><img src="../templates/{THEME}/images/admin/groups_mini.png" class="valign_middle" alt="" /> {L_MEMBER}</h5>
					<div id="smenu4" class="horizontal_block">
						<ul>
							<li><a href="../admin/admin_members.php" style="background-image:url(../templates/{THEME}/images/admin/members_mini.png);background-repeat:no-repeat;background-position:5px;">{L_MEMBER}</a></li>								
							<li><a href="../admin/admin_groups.php" style="background-image:url(../templates/{THEME}/images/admin/groups_mini.png);background-repeat:no-repeat;background-position:5px;">{L_GROUP}</a></li>
							<li><a href="../admin/admin_extend_field.php" style="background-image:url(../templates/{THEME}/images/admin/extendfield_mini.png);background-repeat:no-repeat;background-position:5px;">{L_EXTEND_FIELD}</a></li>
							<li><a href="../admin/admin_ranks.php" style="background-image:url(../templates/{THEME}/images/admin/ranks_mini.png);background-repeat:no-repeat;background-position:5px;">{L_RANKS}</a></li>
							<li><a href="../admin/admin_terms.php" style="background-image:url(../templates/{THEME}/images/admin/terms_mini.png);background-repeat:no-repeat;background-position:5px;">{L_TERMS}</a></li>
						</ul>
						<span class="dm_bottom"></span>
					</div>
				</div>
				<div style="float:left;" onmouseover="show_menu(5);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(5)" class="horizontal"><img src="../templates/{THEME}/images/admin/contents_mini.png" class="valign_middle" alt="" /> {L_CONTENTS}</h5>
					<div id="smenu5" class="horizontal_block">
						<ul>
							<li><a href="../admin/admin_menus.php" style="background-image:url(../templates/{THEME}/images/admin/menus_mini.png);background-repeat:no-repeat;background-position:5px;">{L_SITE_MENU}</a></li>
							<li><a href="../admin/admin_files.php" style="background-image:url(../templates/{THEME}/images/admin/files_mini.png);background-repeat:no-repeat;background-position:5px;">{L_FILES}</a></li>								
							<li><a href="../admin/admin_com.php" style="background-image:url(../templates/{THEME}/images/admin/com_mini.png);background-repeat:no-repeat;background-position:5px;">{L_COMMENTS}</a></li>
						</ul>
						<span class="dm_bottom"></span>
					</div>
				</div>
				<div style="float:left;" onmouseover="show_menu(6);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(6)" class="horizontal"><img src="../templates/{THEME}/images/admin/modules_mini.png" class="valign_middle" alt="" /> {L_MODULES}</h5>
					<div id="smenu6" class="horizontal_block">
						<ul>
							# START modules #
							<li><a href="{modules.U_ADMIN_MODULE}" {modules.DM_A_CLASS}>{modules.NAME}</a></li>				
							# END modules #
						</ul>
						<span class="dm_bottom"></span>
					</div>
				</div>
			</div>
			<div style="float:right;">
				<div style="margin-right:10px;">
					<h5 class="horizontal"><a style="text-decoration:none;color:inherit;" href="../admin/admin_extend.php">{L_EXTEND_MENU}</a></h5>
				</div>
			</div>
		</div>
		
		<div id="admin_main">