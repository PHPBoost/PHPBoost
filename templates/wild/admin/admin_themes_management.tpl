		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_THEME_MANAGEMENT}</li>
				<li>
					<a href="admin_themes.php"><img src="../templates/{THEME}/images/admin/themes.png" alt="" /></a>
					<br />
					<a href="admin_themes.php" class="quick_link">{L_THEME_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_themes_add.php"><img src="../templates/{THEME}/images/admin/themes.png" alt="" /></a>
					<br />
					<a href="admin_themes_add.php" class="quick_link">{L_THEME_ADD}</a>
				</li>
			</ul>
		</div>
			
		<div id="admin_contents">
		
			# START main #
			<form action="admin_themes.php?uninstall=1" method="post">
				<table class="module_table">
					<tr> 
						<th colspan="6">
							{L_THEME_MANAGEMENT}
						</th>
					</tr>
					# START main.error_handler #
					<tr> 
						<td class="row2" colspan="6" style="text-align:center;">
							<span id="errorh"></span>
							<div class="{main.error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
								<img src="../templates/{THEME}/images/{main.error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {main.error_handler.L_ERROR}
								<br />	
							</div>
							<br />		
						</td>
					</tr>
					# END main.error_handler #				
					<tr>
						<td class="row2" colspan="6">
							<strong>{L_EXPLAIN_DEFAULT_THEME}</strong>
						</td>
					</tr>
					# START main.no_theme #
					<tr> 
						<td class="row1" style="text-align:center;">
							{L_NO_THEME_ON_SERV}
						</td>
					</tr>
					# END main.no_theme #
					# START main.theme #
					<tr>
						<td class="row2" style="width:120px;text-align:center;">
							{L_THEME}
						</td>
						<td class="row2" style="width:160px;text-align:center;">
							{L_PREVIEW}
						</td>
						<td class="row2" style="text-align:center;">
							{L_DESC}
						</td>
						<td class="row2" style="width:100px;text-align:center;">
							{L_ACTIV}
						</td>
						<td class="row2" style="width:100px;text-align:center;">
							{L_RANK}
						</td>
						<td class="row2" style="width:100px;text-align:center;">
							{L_UNINSTALL}
						</td>
					</tr>
					# END main.theme #
					
					# START main.list #
					<tr> 	
						<td class="row2" style="text-align:center;">					
							<span id="t{main.list.IDTHEME}"><strong>{main.list.THEME}</strong> <em>({main.list.VERSION})</em>				
						</td>
						<td class="row2">					
							<img src="../templates/{main.list.ICON}/images/theme.jpg" alt="" />
						</td>
						<td class="row2" style="vertical-align:top">	
							<strong>{L_AUTHOR}:</strong> {main.list.AUTHOR} {main.list.AUTHOR_WEBSITE}<br />
							<strong>{L_DESC}:</strong> {main.list.DESC}<br />
							<strong>{L_COMPAT}:</strong> PHPBoost {main.list.COMPAT}<br />
							<strong>{L_XHTML}:</strong> {main.list.HTML_VERSION}<br />
							<strong>{L_CSS}:</strong> {main.list.CSS_VERSION}<br />
							<strong>{L_MAIN_COLOR}:</strong> {main.list.MAIN_COLOR}<br />
							<strong>{L_VARIABLE_WIDTH}:</strong> {main.list.VARIABLE_WIDTH}<br />
							<strong>{L_WIDTH}:</strong> {main.list.WIDTH}				
						</td>
						# START main.list.not_default #
						{main.list.not_default.VALUE}
						# END main.list.not_default #
						# START main.list.default #
						<td class="row2" style="text-align:center;">	
							{L_YES}
						</td>
						<td class="row2" style="text-align:center;">	
							{L_GUEST}
						</td>
						<td class="row2" style="text-align:center;">
							-
						</td>
						# END main.list.default #
					</tr>
					# END main.list #	
					<tr> 
						<th colspan="6">
							<noscript>
								<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
							</noscript>
							&nbsp;
						</th>
					</tr>	
				</table>
			</form>
			# END main #
		
			# START del #
			<form action="admin_themes.php?uninstall=1" method="post" class="fieldset_content">
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
					<input type="hidden" name="idtheme" value="{del.IDTHEME}" />					
				</fieldset>	
			</form>
			# END del #
		</div>
