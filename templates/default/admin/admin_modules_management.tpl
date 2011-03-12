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
			# IF C_MODULES_LIST #	
				<form action="admin_modules.php?uninstall=1" method="post">
					<table class="module_table" style="width:99%;margin-bottom:30px;">
						<tr> 
							<th colspan="5">
								{L_MODULES_INSTALLED}
							</th>
						</tr>
						# IF C_ERROR_HANDLER #
						<tr> 
							<td class="row2" colspan="5" style="text-align:center;">
								<span id="errorh"></span>
								<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
									<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
									<br />	
								</div>
								<br />		
							</td>
						</tr>
						# ENDIF #
					
						# IF C_MODULES_INSTALLED #
						<tr>
							<td class="row2" style="width:100px;text-align:center;">
								{L_NAME}
							</td>
							<td class="row2" style="text-align:center;">
								{L_DESC}
							</td>
							<td class="row2" style="width:50px;text-align:center;">
								{L_ACTIV}
							</td>
							<td class="row2" style="width:200px;text-align:center;">
								{L_AUTH_ACCESS}
							</td>
							<td class="row2" style="width:80px;text-align:center;">
								{L_UNINSTALL}
							</td>
						</tr>
						# ENDIF #
						# IF C_NO_MODULE_INSTALLED #
						<tr>
							<td class="row2" colspan="4" style="text-align:center;">
								<strong>{L_NO_MODULES_INSTALLED}</strong>
							</td>
						</tr>
						# ENDIF #

						# START installed #
						<tr> 	
							<td class="row2" style="text-align:center;">					
								<span id="m{installed.ID}"></span>
								<img class="valign_middle" src="{PATH_TO_ROOT}/{installed.ICON}/{installed.ICON}.png" alt="" /><br />
								<strong>{installed.NAME}</strong> <em>({installed.VERSION})</em>
							</td>
							<td class="row2">	
								<strong>{L_AUTHOR}:</strong> {installed.AUTHOR} {installed.AUTHOR_WEBSITE}<br />
								<strong>{L_DESC}:</strong> {installed.DESC}<br />
								<strong>{L_COMPAT}:</strong> PHPBoost {installed.COMPAT}
								<br /><br />
								<strong>{L_ADMIN}:</strong> {installed.ADMIN}<br />
								<strong>{L_USE_SQL}:</strong> {installed.USE_SQL} <em>{installed.SQL_TABLE}</em><br />
								<strong>{L_USE_CACHE}:</strong> {installed.USE_CACHE}<br />
								<strong>{L_ALTERNATIVE_CSS}:</strong> {installed.ALTERNATIVE_CSS}<br />
							</td>
							<td class="row2">								
								<label><input type="radio" name="activ{installed.ID}" value="1" {installed.ACTIV_ENABLED} /> {L_YES}</label>
								<label><input type="radio" name="activ{installed.ID}" value="0" {installed.ACTIV_DISABLED} /> {L_NO}</label>
							</td>
							<td class="row2">							
								{installed.AUTH_MODULES}			
							</td>
							<td class="row2">	
								<input type="submit" name="{installed.ID}" value="{L_UNINSTALL}" class="submit" />
							</td>
						</tr>					
						# END installed #
					</table>
					
					<fieldset class="fieldset_submit">
						<legend>{L_SUBMIT}</legend>
						<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
						&nbsp;&nbsp; 
						<input type="hidden" name="token" value="{TOKEN}" />
						<input type="reset" value="{L_RESET}" class="reset" />
					</fieldset>
				</form>
			# ENDIF #
			
			
			# IF C_MODULES_DEL #				
				<form action="admin_modules.php?uninstall=1" method="post" class="fieldset_content">
					<fieldset>
						<legend>{L_DEL_MODULE}</legend>
						<div class="error_warning" style="width:500px;margin:auto;">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/important.png" alt="" style="float:left;padding-right:6px;" /> {L_DEL_DATA}
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
						<input type="hidden" name="idmodule" value="{IDMODULE}" />
						<input type="hidden" name="token" value="{TOKEN}" />
						<input type="submit" name="valid_del" value="{L_SUBMIT}" class="submit" />
					</fieldset>
				</form>
			# ENDIF #
		</div>
		