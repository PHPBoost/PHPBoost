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
			
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
			</div>
			# ENDIF #
			
			<form action="" method="post" enctype="multipart/form-data" class="fieldset_content">
				<fieldset>
					<legend>{L_UPLOAD_MODULE}</legend>
					<dl>
						<dt><label for="upload_module">{L_EXPLAIN_ARCHIVE_UPLOAD}</label></dt>
						<dd><label><input type="file" name="upload_module" id="upload_module" size="30" class="submit" />
						<input type="hidden" name="max_file_size" value="2000000" /></label></dd>
					</dl>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_UPLOAD}</legend>
					<input type="submit" value="{L_UPLOAD}" class="submit" />				
				</fieldset>	
			</form>
			
			<table class="module_table">
				<tr> 
					<th>
						{L_UPDATE_AVAILABLE}
					</th>
				</tr>
				<tr> 
					<td class="row1{WARNING_MODULES}" style="text-align:center">
						{UPDATE_MODULES_AVAILABLE} {L_MODULES_UPDATE}<br />
						# START update_modules_available #
						<a href="http://www.phpboost.com/phpboost/modules.php?name={update_modules_available.ID}">{update_modules_available.NAME} <em>({update_modules_available.VERSION})</em></a><br />
						# END update_modules_available #
					</td>
				</tr>	
			</table>
				
			<br /><br />		
			
			<form action="admin_modules_update.php?update=1" method="post">
				<table class="module_table">
					<tr> 
						<th colspan="6">
							{L_MODULES_AVAILABLE}
						</th>
					</tr>
					# IF C_MODULES_AVAILABLE #
					<tr>
						<td class="row2" style="width:160px">
							{L_NAME}
						</td>
						<td class="row2" style="width:140px;text-align:center;">
							{L_NEW_VERSION}
						</td>
						<td class="row2" style="width:140px;text-align:center;">
							{L_INSTALLED_VERSION}
						</td>
						<td class="row2">
							{L_DESC}
						</td>
						<td class="row2" style="width:100px">
							{L_UPDATE}
						</td>
					</tr>
					# ENDIF #
					# IF C_NO_MODULE #
					<tr>
						<td class="row2" colspan="4" style="text-align:center;">
							<strong>{L_NO_MODULES_AVAILABLE}</strong>
						</td>
					</tr>
					# ENDIF #
					
					
					# START available #
					<tr> 	
						<td class="row2">					
							<img class="valign_middle" src="../{available.ICON}/{available.ICON}.png" alt="" /> <strong>{available.NAME}</strong>
						</td>
						<td class="row2" style="text-align:center;">					
							<strong>{available.VERSION}</strong>
						</td>
						<td class="row2" style="text-align:center;">					
							<strong>{available.PREVIOUS_VERSION}</strong>
						</td>
						<td class="row2">	
							<strong>{L_AUTHOR}:</strong> {available.AUTHOR} {available.AUTHOR_WEBSITE}<br />
							<strong>{L_DESC}:</strong> {available.DESC}<br />
							<strong>{L_COMPAT}:</strong> PHPBoost {available.COMPAT}<br />
							<strong>{L_USE_SQL}:</strong> {available.USE_SQL} <em>{available.SQL_TABLE}</em><br />
							<strong>{L_USE_CACHE}:</strong> {available.USE_CACHE}<br />
							<strong>{L_ALTERNATIVE_CSS}:</strong> {available.ALTERNATIVE_CSS}<br />
						</td>
						<td class="row2" style="text-align:center;">	
							<input type="submit" name="{available.ID}" value="{L_UPDATE}" class="submit" />
						</td>
					</tr>						
					# END available #
				</table>			
			</form>
		</div>
		