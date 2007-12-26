		<div id="dynamic_menu">
			<div style="float:left;">				
				<div style="float:left;" onmouseover="show_menu(1);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(1)" class="horizontal"><img src="../templates/{THEME}/images/admin/index_mini.png" style="vertical-align:middle;" alt="" /> {L_INDEX}</h5>					
					<div id="smenu1" class="horizontal_block">
						<ul>
							<li><a href="{U_INDEX_SITE}" class="dm_menu_home">{L_INDEX_SITE}</a></li>
							<li><a href="../admin/admin_index.php" class="dm_menu_admin">{L_INDEX_ADMIN}</a></li>								
						</ul>
						<span class="dm_bottom"></span>
					</div>						
				</div>
				<div style="float:left;" onmouseover="show_menu(2);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(2)" class="horizontal"><img src="../templates/{THEME}/images/admin/site_mini.png" style="vertical-align:middle;" alt="" /> {L_SITE}</h5>					
					<div id="smenu2" class="horizontal_block">
						<ul>
							<li><a href="../admin/admin_config.php" class="dm_menu_config">{L_CONFIGURATION}</a></li>
							<li><a href="../admin/admin_modules.php" class="dm_menu_modules">{L_MODULES}</a></li>
							<li><a href="../admin/admin_themes.php" class="dm_menu_themes">{L_THEME}</a></li>
							<li><a href="../admin/admin_lang.php" class="dm_menu_languages">{L_LANG}</a></li>
							<li><a href="../admin/admin_smileys.php" class="dm_menu_smileys">{L_SMILEY}</a></li>								
						</ul>
						<span class="dm_bottom"></span>
					</div>						
				</div>
				<div style="float:left;" onmouseover="show_menu(3);" onmouseout="hide_menu();">					
					<h5 onclick="temporise_menu(3)" class="horizontal"><img src="../templates/{THEME}/images/admin/tools_mini.png" style="vertical-align:middle;" alt="" /> {L_TOOLS}</h5>
					<div id="smenu3" class="horizontal_block">
						<ul>							
							<li><a href="../admin/admin_maintain.php" class="dm_menu_maintain">{L_MAINTAIN}</a></li>
							<li><a href="../admin/admin_database.php" class="dm_menu_database">{L_SITE_DATABASE}</a></li>
							<li><a href="../admin/admin_cache.php" class="dm_menu_cache">{L_CACHE}</a></li>		
							<li><a href="../admin/admin_stats.php" class="dm_menu_stats">{L_STATS}</a></li>
							<li><a href="../admin/admin_errors.php" class="dm_menu_errors">{L_ERRORS}</a></li>
						</ul>
						<span class="dm_bottom"></span>
					</div>
				</div>
				<div style="float:left;" onmouseover="show_menu(4);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(4)" class="horizontal"><img src="../templates/{THEME}/images/admin/members_mini.png" style="vertical-align:middle;" alt="" /> {L_MEMBER}</h5>
					<div id="smenu4" class="horizontal_block">
						<ul>
							<li><a href="../admin/admin_members.php" class="dm_menu_members">{L_MEMBER}</a></li>								
							<li><a href="../admin/admin_groups.php" class="dm_menu_groups">{L_GROUP}</a></li>
							<li><a href="../admin/admin_extend_field.php" class="dm_menu_extendfield">{L_EXTEND_FIELD}</a></li>
							<li><a href="../admin/admin_ranks.php" class="dm_menu_ranks">{L_RANKS}</a></li>
							<li><a href="../admin/admin_terms.php" class="dm_menu_terms">{L_TERMS}</a></li>
						</ul>
						<span class="dm_bottom"></span>
					</div>
				</div>
				<div style="float:left;" onmouseover="show_menu(5);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(5)" class="horizontal"><img src="../templates/{THEME}/images/admin/contents_mini.png" style="vertical-align:middle;" alt="" /> {L_CONTENTS}</h5>
					<div id="smenu5" class="horizontal_block">
						<ul>
							<li><a href="../admin/admin_menus.php" class="dm_menu_menus">{L_SITE_MENU}</a></li>
							<li><a href="../admin/admin_files.php" class="dm_menu_files">{L_FILES}</a></li>								
							<li><a href="../admin/admin_com.php" class="dm_menu_com">{L_COMMENTS}</a></li>
						</ul>
						<span class="dm_bottom"></span>
					</div>
				</div>
				<div style="float:left;" onmouseover="show_menu(6);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(6)" class="horizontal"><img src="../templates/{THEME}/images/admin/modules_mini.png" style="vertical-align:middle;" alt="" /> {L_MODULES}</h5>
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
					<h5 class="horizontal"><a style="text-decoration:none" href="../admin/admin_extend.php">Menu étendu{L_EXTEND_MENU}</a></h5>
				</div>
			</div>
		</div>
		
		<div id="admin_main">