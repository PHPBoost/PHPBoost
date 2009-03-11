		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_MODULES_MANAGEMENT}</li>
				<li>
					<a href="admin_modules.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="admin_modules.php" class="quick_link">{L_MODULES_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_modules_add.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="admin_modules_add.php" class="quick_link">{L_ADD_MODULES}</a>
				</li>
				<li>
					<a href="admin_modules_update.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modules.png" alt="" /></a>
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
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
			</div>
			# ENDIF #
			
			<form action="admin_modules_add.php?token={TOKEN}" method="post" enctype="multipart/form-data" class="fieldset_content">
				<fieldset>
					<legend>{L_UPLOAD_MODULE}</legend>
					<dl>
						<dt><label for="upload_module">{L_EXPLAIN_ARCHIVE_UPLOAD}</label></dt>
						<dd><label><input type="file" name="upload_module" id="upload_module" size="30" class="file" />
						<input type="hidden" name="max_file_size" value="2000000" /></label></dd>
					</dl>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_UPLOAD}</legend>
					<input type="submit" value="{L_UPLOAD}" class="submit" />				
				</fieldset>	
			</form>
			
			<form action="admin_modules_add.php?install=1" method="post">
				<table class="module_table">
					<tr> 
						<th colspan="4">
							{L_MODULES_AVAILABLE}
						</th>
					</tr>
					# IF C_MODULES_AVAILABLE #
					<tr>
						<td class="row2" style="width:160px;text-align:center;">
							{L_NAME}
						</td>
						<td class="row2" style="text-align:center;">
							{L_DESC}
						</td>
						<td class="row2" style="width:100px;text-align:center;">
							{L_ACTIV}
						</td>
						<td class="row2" style="width:100px;text-align:center;">
							{L_INSTALL}
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
							<img class="valign_middle" src="{PATH_TO_ROOT}/{available.ICON}/{available.ICON}.png" alt="" /> <strong>{available.NAME}</strong> <em>({available.VERSION})</em>
						</td>
						<td class="row2">	
							<strong>{L_AUTHOR}:</strong> {available.AUTHOR} {available.AUTHOR_WEBSITE}<br />
							<strong>{L_DESC}:</strong> {available.DESC}<br />
							<strong>{L_COMPAT}:</strong> PHPBoost {available.COMPAT}<br />
							<strong>{L_USE_SQL}:</strong> {available.USE_SQL} <em>{available.SQL_TABLE}</em><br />
							<strong>{L_USE_CACHE}:</strong> {available.USE_CACHE}<br />
							<strong>{L_ALTERNATIVE_CSS}:</strong> {available.ALTERNATIVE_CSS}<br />
						</td>
						<td class="row2">	
							<input type="radio" name="{available.ID}activ" value="1" checked="checked" /> {L_YES}
							<input type="radio" name="{available.ID}activ" value="0" /> {L_NO}
						</td>
						<td class="row2">	
							<input type="submit" name="module_{available.ID}" value="{L_INSTALL}" class="submit" />
						</td>
					</tr>						
					# END available #
				</table>			
			</form>
		</div>
		