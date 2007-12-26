		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_MODULES_MANAGEMENT}</li>
				<li>
					<a href="admin_modules.php"><img src="../templates/{THEME}/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="admin_modules.php" class="quick_link">{L_MODULES_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_modules_add.php"><img src="../templates/{THEME}/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="admin_modules_add.php" class="quick_link">{L_ADD_MODULES}</a>
				</li>
				<li>
					<a href="admin_modules_update.php"><img src="../templates/{THEME}/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="admin_modules_update.php" class="quick_link">{L_UPDATE_MODULES}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
		
			# START main #	
				<script type="text/javascript">
				<!--
					function check_select_multiple(id, status)
					{
						var i;		
						for(i = -1; i <= 2; i++)
						{
							if( document.getElementById(id + 'r' + i) )
								document.getElementById(id + 'r' + i).selected = status;
						}				
						document.getElementById(id + 'r3').selected = true;
						
						for(i = 0; i < {main.NBR_GROUP}; i++)
						{	
							if( document.getElementById(id + 'g' + i) )
								document.getElementById(id + 'g' + i).selected = status;		
						}	
					}
					function check_select_multiple_ranks(id, start)
					{
						var i;				
						for(i = start; i <= 2; i++)
						{	
							if( document.getElementById(id + i) )
								document.getElementById(id + i).selected = true;			
						}
					}
				-->
				</script>
				<form action="admin_modules.php?uninstall=1" method="post">
					<table class="module_table">
						<tr> 
							<th colspan="5">
								{L_MODULES_INSTALLED}
							</th>
						</tr>
						# START error_handler #
						<tr> 
							<td class="row2" colspan="5" style="text-align:center;">
								<span id="errorh"></span>
								<div class="{main.error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
									<img src="../templates/{THEME}/images/{main.error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {main.error_handler.L_ERROR}
									<br />	
								</div>
								<br />		
							</td>
						</tr>
						# END error_handler #
					
						# START modules_installed #
						<tr>
							<td class="row2" style="width:150px;text-align:center;">
								{L_NAME}
							</td>
							<td class="row2" style="text-align:center;">
								{L_DESC}
							</td>
							<td class="row2" style="width:100px;text-align:center;">
								{L_ACTIV}
							</td>
							<td class="row2" style="width:270px;text-align:center;">
								{L_AUTH_ACCESS}
							</td>
							<td class="row2" style="width:80px;text-align:center;">
								{L_UNINSTALL}
							</td>
						</tr>
						# END modules_installed #
						# START no_module_installed #
						<tr>
							<td class="row2" colspan="4" style="text-align:center;">
								<strong>{L_NO_MODULES_INSTALLED}</strong>
							</td>
						</tr>
						# END no_module_installed #
						

						# START installed #
						<tr> 	
							<td class="row2">					
								<span id="m{main.installed.ID}"></span>
								<img style="vertical-align:middle;" src="../{main.installed.ICON}/{main.installed.ICON}.png" alt="" /> <strong>{main.installed.NAME}</strong> <em>({main.installed.VERSION})</em>
							</td>
							<td class="row2">	
								<strong>{L_AUTHOR}:</strong> {main.installed.AUTHOR} {main.installed.AUTHOR_WEBSITE}<br />
								<strong>{L_DESC}:</strong> {main.installed.DESC}<br />
								<strong>{L_COMPAT}:</strong> PHPBoost {main.installed.COMPAT}<br />
								<strong>{L_ADMIN}:</strong> {main.installed.ADMIN}<br />
								<strong>{L_USE_SQL}:</strong> {main.installed.USE_SQL} <em>{main.installed.SQL_TABLE}</em><br />
								<strong>{L_USE_CACHE}:</strong> {main.installed.USE_CACHE}<br />
								<strong>{L_ALTERNATIVE_CSS}:</strong> {main.installed.ALTERNATIVE_CSS}<br />
							</td>
							<td class="row2">								
								<input type="radio" name="activ{main.installed.ID}" value="1" {main.installed.ACTIV_ENABLED} /> {L_YES}
								<input type="radio" name="activ{main.installed.ID}" value="0" {main.installed.ACTIV_DISABLED} /> {L_NO}
							</td>
							<td class="row2" style="text-align:center;">								
								<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
								<br />
								<select id="groups_auth" name="groups_auth{main.installed.ID}[]" size="8" multiple="multiple" onclick="document.getElementById('{main.installed.ID}r3').selected = true;">
									# START select_group #	
										{main.installed.select_group.GROUP}
									# END select_group #						
								</select>
								<br />
								<a href="javascript:check_select_multiple('{main.installed.ID}', true);">{L_SELECT_ALL}</a>
								&nbsp;/&nbsp;
								<a href="javascript:check_select_multiple('{main.installed.ID}', false);">{L_SELECT_NONE}</a>
							</td>
							<td class="row2">	
								<input type="submit" name="{main.installed.ID}" value="{L_UNINSTALL}" class="submit" />
							</td>
						</tr>					
						# END installed #
					</table>
					
					<br /><br />
					
					<fieldset class="fieldset_submit">
						<legend>{L_SUBMIT}</legend>
						<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
						&nbsp;&nbsp; 
						<input type="reset" value="{L_RESET}" class="reset" />
					</fieldset>
				</form>
			# END main #
			
			
			# START del #				
				<form action="admin_modules.php?uninstall=1" method="post" class="fieldset_content">
					<fieldset>
						<legend>{L_DEL_MODULE}</legend>
						<div class="error_warning" style="width:500px;margin:auto;">
							<img src="../templates/{THEME}/images/important.png" alt="" style="float:left;padding-right:6px;" /> {L_DEL_DATA}
						</div>
						<br />
						<dl>
							<dt><label for="drop_files">{L_DEL_FILE}</label></dt>
							<dd><label><input type="radio" name="drop_files" value="1" /> {L_YES}</label>
							<label><input type="radio" name="drop_files" id="drop_files" value="0" checked="checked" /> {L_NO}</label></dd>
						</dl>
					</fieldset>		
					<fieldset class="fieldset_submit">
						<legend>{L_SUBMIT}</legend>
						<input type="hidden" name="idmodule" value="{del.IDMODULE}" />
						<input type="submit" name="valid_del" value="{L_SUBMIT}" class="submit" />
					</fieldset>
				</form>
			# END del #
		</div>
		