		<div id="dynamic_menu">
			<div style="float:left;">				
				<div style="float:left;" onmouseover="show_menu(1);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(1)" class="horizontal"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/index_mini.png" class="valign_middle" alt="" /> <?php echo isset($this->_var['L_INDEX']) ? $this->_var['L_INDEX'] : ''; ?></h5>					
					<div id="smenu1" class="horizontal_block">
						<ul>
							<li><a href="<?php echo isset($this->_var['U_INDEX_SITE']) ? $this->_var['U_INDEX_SITE'] : ''; ?>" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/home_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_INDEX_SITE']) ? $this->_var['L_INDEX_SITE'] : ''; ?></a></li>
							<li><a href="../admin/admin_index.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/admin_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_INDEX_ADMIN']) ? $this->_var['L_INDEX_ADMIN'] : ''; ?></a></li>								
						</ul>
						<span class="dm_bottom"></span>
					</div>						
				</div>
				<div style="float:left;" onmouseover="show_menu(2);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(2)" class="horizontal"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/site_mini.png" class="valign_middle" alt="" /> <?php echo isset($this->_var['L_SITE']) ? $this->_var['L_SITE'] : ''; ?></h5>					
					<div id="smenu2" class="horizontal_block">
						<ul>
							<li><a href="../admin/admin_config.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/config_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_CONFIGURATION']) ? $this->_var['L_CONFIGURATION'] : ''; ?></a></li>
							<li><a href="../admin/admin_modules.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/modules_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_MODULES']) ? $this->_var['L_MODULES'] : ''; ?></a></li>
							<li><a href="../admin/admin_themes.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/themes_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_THEME']) ? $this->_var['L_THEME'] : ''; ?></a></li>
							<li><a href="../admin/admin_lang.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/languages_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_LANG']) ? $this->_var['L_LANG'] : ''; ?></a></li>
							<li><a href="../admin/admin_smileys.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/smileys_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_SMILEY']) ? $this->_var['L_SMILEY'] : ''; ?></a></li>
						</ul>
						<span class="dm_bottom"></span>
					</div>						
				</div>
				<div style="float:left;" onmouseover="show_menu(3);" onmouseout="hide_menu();">					
					<h5 onclick="temporise_menu(3)" class="horizontal"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/tools_mini.png" class="valign_middle" alt="" /> <?php echo isset($this->_var['L_TOOLS']) ? $this->_var['L_TOOLS'] : ''; ?></h5>
					<div id="smenu3" class="horizontal_block">
						<ul>							
							<li><a href="../admin/admin_maintain.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/maintain_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_MAINTAIN']) ? $this->_var['L_MAINTAIN'] : ''; ?></a></li>
							<li><a href="../admin/admin_database.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/database_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_SITE_DATABASE']) ? $this->_var['L_SITE_DATABASE'] : ''; ?></a></li>
							<li><a href="../admin/admin_cache.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/cache_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_CACHE']) ? $this->_var['L_CACHE'] : ''; ?></a></li>		
							<li><a href="../admin/admin_stats.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/stats_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_STATS']) ? $this->_var['L_STATS'] : ''; ?></a></li>
							<li><a href="../admin/admin_errors.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/errors_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_ERRORS']) ? $this->_var['L_ERRORS'] : ''; ?></a></li>
							<li><a href="../admin/admin_phpinfo.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/phpinfo_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_PHPINFO']) ? $this->_var['L_PHPINFO'] : ''; ?></a></li>
						</ul>
						<span class="dm_bottom"></span>
					</div>
				</div>
				<div style="float:left;" onmouseover="show_menu(4);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(4)" class="horizontal"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/groups_mini.png" class="valign_middle" alt="" /> <?php echo isset($this->_var['L_MEMBER']) ? $this->_var['L_MEMBER'] : ''; ?></h5>
					<div id="smenu4" class="horizontal_block">
						<ul>
							<li><a href="../admin/admin_members.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/members_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_MEMBER']) ? $this->_var['L_MEMBER'] : ''; ?></a></li>								
							<li><a href="../admin/admin_groups.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/groups_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_GROUP']) ? $this->_var['L_GROUP'] : ''; ?></a></li>
							<li><a href="../admin/admin_extend_field.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/extendfield_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_EXTEND_FIELD']) ? $this->_var['L_EXTEND_FIELD'] : ''; ?></a></li>
							<li><a href="../admin/admin_ranks.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/ranks_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_RANKS']) ? $this->_var['L_RANKS'] : ''; ?></a></li>
							<li><a href="../admin/admin_terms.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/terms_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_TERMS']) ? $this->_var['L_TERMS'] : ''; ?></a></li>
						</ul>
						<span class="dm_bottom"></span>
					</div>
				</div>
				<div style="float:left;" onmouseover="show_menu(5);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(5)" class="horizontal"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/contents_mini.png" class="valign_middle" alt="" /> <?php echo isset($this->_var['L_CONTENTS']) ? $this->_var['L_CONTENTS'] : ''; ?></h5>
					<div id="smenu5" class="horizontal_block">
						<ul>
							<li><a href="../admin/admin_menus.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/menus_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_SITE_MENU']) ? $this->_var['L_SITE_MENU'] : ''; ?></a></li>
							<li><a href="../admin/admin_files.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/files_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_FILES']) ? $this->_var['L_FILES'] : ''; ?></a></li>								
							<li><a href="../admin/admin_com.php" style="background-image:url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/com_mini.png);background-repeat:no-repeat;background-position:5px;"><?php echo isset($this->_var['L_COMMENTS']) ? $this->_var['L_COMMENTS'] : ''; ?></a></li>
						</ul>
						<span class="dm_bottom"></span>
					</div>
				</div>
				<div style="float:left;" onmouseover="show_menu(6);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(6)" class="horizontal"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/modules_mini.png" class="valign_middle" alt="" /> <?php echo isset($this->_var['L_MODULES']) ? $this->_var['L_MODULES'] : ''; ?></h5>
					<div id="smenu6" class="horizontal_block">
						<ul>
							<?php if( !isset($this->_block['modules']) || !is_array($this->_block['modules']) ) $this->_block['modules'] = array();
foreach($this->_block['modules'] as $modules_key => $modules_value) {
$_tmpb_modules = &$this->_block['modules'][$modules_key]; ?>
							<li><a href="<?php echo isset($_tmpb_modules['U_ADMIN_MODULE']) ? $_tmpb_modules['U_ADMIN_MODULE'] : ''; ?>" <?php echo isset($_tmpb_modules['DM_A_CLASS']) ? $_tmpb_modules['DM_A_CLASS'] : ''; ?>><?php echo isset($_tmpb_modules['NAME']) ? $_tmpb_modules['NAME'] : ''; ?></a></li>				
							<?php } ?>
						</ul>
						<span class="dm_bottom"></span>
					</div>
				</div>
			</div>
			<div style="float:right;">
				<div style="margin-right:10px;">
					<h5 class="horizontal"><a style="text-decoration:none;color:inherit;" href="../admin/admin_extend.php"><?php echo isset($this->_var['L_EXTEND_MENU']) ? $this->_var['L_EXTEND_MENU'] : ''; ?></a></h5>
				</div>
			</div>
		</div>
		
		<div id="admin_main">