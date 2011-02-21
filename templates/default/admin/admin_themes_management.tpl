		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_THEME_MANAGEMENT}</li>
				<li>
					<a href="admin_themes.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/themes.png" alt="" /></a>
					<br />
					<a href="admin_themes.php" class="quick_link">{L_THEME_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_themes_add.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/themes.png" alt="" /></a>
					<br />
					<a href="admin_themes_add.php" class="quick_link">{L_THEME_ADD}</a>
				</li>
			</ul>
		</div>
			
		<div id="admin_contents">
		
			# IF C_THEME_MAIN #
			<form action="admin_themes.php?uninstall=1&amp;token={TOKEN}" method="post">
				<table class="module_table">
					<tr> 
						<th colspan="7">
							{L_THEME_MANAGEMENT}
						</th>
					</tr>
					# IF C_ERROR_HANDLER #
					<tr> 
						<td class="row2" colspan="7" style="text-align:center;">
							<span id="errorh"></span>
							<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
								<br />	
							</div>
							<br />		
						</td>
					</tr>
					# ENDIF #				
					<tr>
						<td class="row2" colspan="7">
							<strong>{L_EXPLAIN_DEFAULT_THEME}</strong>
						</td>
					</tr>
					# IF C_NO_THEME_PRESENT #
					<tr> 
						<td class="row1" style="text-align:center;">
							{L_NO_THEME_ON_SERV}
						</td>
					</tr>
					# ENDIF #
					# IF C_THEME_PRESENT #
					<tr>
						<td class="row2" style="text-align:center;">
							{L_THEME}
						</td>
						<td class="row2" style="text-align:center;">
							{L_IMAGE}
						</td>
						<td class="row2" style="text-align:center;">
							{L_INSTALL}
						</td>
					</tr>
					# ENDIF #
					
					# START list #
					<tr> 	
						<td class="row2">				
							<span id="t{list.IDTHEME}"><strong>{list.THEME}</strong></span> <em>({list.VERSION})</em>
							&nbsp;<a href="admin_themes.php?edit=1&amp;id={list.IDTHEME}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="" class="valign_middle" /></a>	
							<br />
							<br />
							<br />
							<strong>{L_AUTHOR}:</strong> {list.AUTHOR} {list.AUTHOR_WEBSITE}<br />
							<strong>{L_DESC}:</strong> {list.DESC}<br />
							<strong>{L_COMPAT}:</strong> PHPBoost {list.COMPAT}<br />
							<strong>{L_XHTML}:</strong> {list.HTML_VERSION}<br />
							<strong>{L_CSS}:</strong> {list.CSS_VERSION}<br />
							<strong>{L_MAIN_COLOR}:</strong> {list.MAIN_COLOR}<br />
							<strong>{L_VARIABLE_WIDTH}:</strong> {list.VARIABLE_WIDTH}<br />
							<strong>{L_WIDTH}:</strong> {list.WIDTH}						
						</td>
						<td class="row2">					
							<img src="{PATH_TO_ROOT}/templates/{list.ICON}/theme/images/theme.jpg" alt="" />
						</td>
						# IF list.C_THEME_NOT_DEFAULT #
						<td class="row2" style="text-align:center;">	
							<p>
								<strong>{L_ACTIV} :</strong>
								<label><input type="radio" name="{list.IDTHEME}activ" value="1" {list.THEME_ACTIV} onchange="document.location = 'admin_themes.php?activ=1&amp;id={list.IDTHEME}&amp;token={TOKEN}'" /> {L_YES}</label>
								<label><input type="radio" name="{list.IDTHEME}activ" value="0" {list.THEME_UNACTIV} onchange="document.location = 'admin_themes.php?activ=0&amp;id={list.IDTHEME}&amp;token={TOKEN}'" /> {L_NO}</label>
							</p>
							<p>
								<br />
								<br />
								<input type="submit" name="{list.IDTHEME}" value="{L_UNINSTALL}" class="submit" />
							</p>
						</td>
						# ENDIF #
						
						# IF list.C_THEME_DEFAULT #
						<td class="row2" style="text-align:center;">	
							<p>
								<strong>{L_ACTIV} :</strong>
								{L_YES}
							</p>
						</td>
						# ENDIF #
					</tr>
					# END list #	
					<tr> 
						<th colspan="7">
							<input type="submit" name="valid" id="submit_theme" value="{L_SUBMIT}" class="submit" />
							<script type="text/javascript">
							<!--				
							document.getElementById('submit_theme').style.display = 'none';
							-->
							</script>
							&nbsp;
						</th>
					</tr>	
				</table>
			</form>
			# ENDIF #
		
		
			# IF C_DEL_THEME #
			<form action="admin_themes.php?uninstall=1&amp;token={TOKEN}" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_DEL_THEME}</legend>
					<dl>
						<dt><label for="drop_files">{L_DEL_FILE}</label></dt>
						<dd><label><input type="radio" name="drop_files" value="1" /> {L_YES}</label>
						<label><input type="radio" name="drop_files" id="drop_files" value="0" checked="checked" /> {L_NO}</label></dd>
					</dl>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_DELETE}</legend>
					<input type="submit" name="valid_del" value="{L_DELETE}" class="submit" />	
					<input type="hidden" name="idtheme" value="{IDTHEME}" />					
				</fieldset>	
			</form>
			# ENDIF #
			
			
			# IF C_EDIT_THEME #			
			<form action="admin_themes.php?edit=1&amp;id={IDTHEME}&amp;token={TOKEN}" method="post" class="fieldset_content">	
				<fieldset> 
				<legend>{L_THEME_MANAGEMENT}</legend>
					<p>{L_THEME} <strong>{THEME_NAME}</strong></p>
					<dl>
						<dt>
							<label for="auth_theme">{L_AUTH}</label>
						</dt>
						<dd>
							{AUTH_THEME}
						</dd>
					</dl>
					<dl>
						<dt><label for="left_column">{L_LEFT_COLUMN}</label></dt>
						<dd><label><input type="checkbox" {LEFT_COLUMN_ENABLED} name="left_column" id="left_column" value="1" /></label></dd>
					</dl>
					<dl>
						<dt><label for="right_column">{L_RIGHT_COLUMN}</label></dt>
						<dd><label><input type="checkbox" {RIGHT_COLUMN_ENABLED} name="right_column" id="right_column" value="1" /></label></dd>
					</dl>
				</fieldset>
				<fieldset class="fieldset_submit" style="margin-bottom:0px">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid_edit" value="{L_UPDATE}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />
				</fieldset>
			</form>			
			# ENDIF #
			
		</div>
