			<div style="width:98%;padding: 4px;">
				<div style="padding-bottom: 4px;padding-left: 10px;"><a href="{U_INDEX_SITE}"><img class="valign_middle" src="../templates/{THEME}/images/admin/home_mini.png" alt="" /> {L_INDEX_SITE}</a>
				&bull; <a href="../admin/admin_index.php"><img class="valign_middle" src="../templates/{THEME}/images/admin/admin_mini.png" alt="" /> {L_INDEX_ADMIN}</a></div>

				<table class="module_table">
					<tr>
						<th colspan="5">
							{L_SITE}
						</th>
					</tr>
					<tr style="text-align:center;">
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_config.php"><img src="../templates/{THEME}/images/admin/configuration.png" /></a>		
							<br />
							<a href="../admin/admin_config.php">{L_CONFIGURATION}</a>						
						</td>
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_modules.php"><img src="../templates/{THEME}/images/admin/modules.png" /></a>		
							<br />
							<a href="../admin/admin_modules.php">{L_MODULES}</a>	
						</td>
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_menus.php"><img src="../templates/{THEME}/images/admin/menus.png" /></a>		
							<br />
							<a href="../admin/admin_menus.php">{L_SITE_MENU}</a>		
						</td>
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_files.php"><img src="../templates/{THEME}/images/admin/files.png" /></a>		
							<br />
							<a href="../admin/admin_files.php">{L_FILES}</a>		
						</td>
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_database.php"><img src="../templates/{THEME}/images/admin/database.png" /></a>		
							<br />
							<a href="../admin/admin_database.php">{L_SITE_DATABASE}</a>		
						</td>
					</tr>	
					<tr style="text-align:center;">		
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_themes.php"><img src="../templates/{THEME}/images/admin/themes.png" /></a>		
							<br />
							<a href="../admin/admin_themes.php">{L_THEME}</a>			
						</td>
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_lang.php"><img src="../templates/{THEME}/images/admin/languages.png" /></a>		
							<br />
							<a href="../admin/admin_lang.php">{L_LANG}</a>
						</td>	
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_cache.php"><img src="../templates/{THEME}/images/admin/cache.png" /></a>		
							<br />
							<a href="../admin/admin_cache.php">{L_CACHE}</a>				
						</td>						
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_maintain.php"><img src="../templates/{THEME}/images/admin/maintain.png" /></a>		
							<br />
							<a href="../admin/admin_maintain.php">{L_MAINTAIN}</a>
						</td>	
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_smileys.php"><img src="../templates/{THEME}/images/admin/smileys.png" />
							<br />
							<a href="../admin/admin_smileys.php">{L_SMILEY}</a>	
						</td>								
					</tr>
					<tr style="text-align:center;">								
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_members.php"><img src="../templates/{THEME}/images/admin/members.png" /></a>		
							<br />
							<a href="../admin/admin_members.php">{L_USER}</a>
						</td>
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_groups.php"><img src="../templates/{THEME}/images/admin/groups.png" /></a>		
							<br />
							<a href="../admin/admin_groups.php">{L_GROUP}</a>
						</td>	
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_ranks.php"><img src="../templates/{THEME}/images/admin/ranks.png" /></a>		
							<br />
							<a href="../admin/admin_ranks.php">{L_RANKS}</a>	
						</td>
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_extend_field.php"><img src="../templates/{THEME}/images/admin/extendfield.png" /></a>		
							<br />
							<a href="../admin/admin_extend_field.php">{L_EXTEND_FIELD}</a>
						</td>							
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_errors.php"><img src="../templates/{THEME}/images/admin/errors.png" /></a>		
							<br />
							<a href="../admin/admin_errors.php">{L_ERRORS}</a>
						</td>			
						
					</tr>
					<tr style="text-align:center;">	
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_stats.php"><img src="../templates/{THEME}/images/admin/stats.png" /></a>		
								<br />
							<a href="../admin/admin_stats.php">{L_STATS}</a>
						</td>		
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_phpinfo.php"><img src="../templates/{THEME}/images/admin/phpinfo.png" /></a>		
								<br />
							<a href="../admin/admin_phpinfo.php">{L_PHPINFO}</a>
						</td>
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_com.php"><img src="../templates/{THEME}/images/admin/com.png" /></a>		
							<br />
							<a href="../admin/admin_com.php">{L_COM}</a>
						</td>					
						
						<td class="row2" style="width:20%;">
							<a href="../admin/admin_updates.php"><img src="../templates/{THEME}/images/admin/com.png" /></a>		
							<br />
							<a href="../admin/admin_updates.php">{L_WEBSITE_UPDATES}</a>
						</td>
						<td class="row2" style="width:20%;">
						</td>
					</tr>					
				</table>

				<table class="module_table">
					<tr>
						<th colspan="5">
							{L_MODULES}
						</th>
					</tr>
					# START modules_extend #
					{modules_extend.START_TR}
						<td class="row2" style="width:20%;">
							<a href="{modules_extend.U_ADMIN_MODULE}"><img src="{modules_extend.IMG}" /></a>		
							<br />
							<a href="{modules_extend.U_ADMIN_MODULE}">{modules_extend.NAME}</a>				
						</td>
						# START modules_extend.td #
						{modules_extend.td.TD}
						# END modules_extend.td #
					{modules_extend.END_TR}
					# END modules_extend #				
				</table>
			</div>