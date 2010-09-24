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
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
			</div>
			# ENDIF #
			
			<form action="admin_themes_add.php?token={TOKEN}" method="post" enctype="multipart/form-data" class="fieldset_content">
				<fieldset>
					<legend>{L_UPLOAD_THEME}</legend>
					<dl>
						<dt><label for="upload_theme">{L_EXPLAIN_ARCHIVE_UPLOAD}</label></dt>
						<dd><label><input type="file" name="upload_theme" id="upload_theme" size="30" class="file" />
						<input type="hidden" name="max_file_size" value="2000000" /></label></dd>
					</dl>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_UPLOAD}</legend>
					<input type="submit" value="{L_UPLOAD}" class="submit" />				
				</fieldset>	
			</form>
		
			<form action="admin_themes_add.php?install=1&amp;token={TOKEN}" method="post">
				<table class="module_table">
					<tr> 
						<th colspan="6">
							{L_THEME_ADD}
						</th>
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
							<strong>{list.THEME}</strong> <em>({list.VERSION})</em>	
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
						<td class="row2" style="text-align:center;">	
							<p>
								<strong>{L_ACTIV} :</strong>
								<label><input type="radio" name="{list.IDTHEME}activ" value="1" checked="checked" /> {L_YES}</label>
								<label><input type="radio" name="{list.IDTHEME}activ" value="0" /> {L_NO}</label>
							</p>
							<p>
								<strong>{L_RANK} :</strong>								
									{list.OPTIONS}
							</p>
							<p>
								<br />
								<br />
								<input type="submit" name="{list.IDTHEME}" value="{L_INSTALL}" class="submit" />
							</p>
						</td>
					</tr>
					# END list #
				</table>
			</form>
		</div>
		