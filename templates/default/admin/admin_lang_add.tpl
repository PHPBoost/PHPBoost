		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_LANG_MANAGEMENT}</li>
				<li>
					<a href="admin_lang.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/languages.png" alt="" /></a>
					<br />
					<a href="admin_lang.php" class="quick_link">{L_LANG_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_lang_add.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/languages.png" alt="" /></a>
					<br />
					<a href="admin_lang_add.php" class="quick_link">{L_LANG_ADD}</a>
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
			
			<form action="admin_lang_add.php?token={TOKEN}" method="post" enctype="multipart/form-data" class="fieldset_content">
				<fieldset>
					<legend>{L_UPLOAD_LANG}</legend>
					<dl>
						<dt><label for="upload_lang">{L_EXPLAIN_ARCHIVE_UPLOAD}</label></dt>
						<dd><label><input type="file" name="upload_lang" id="upload_lang" size="30" class="file" />
						<input type="hidden" name="max_file_size" value="2000000" /></label></dd>
					</dl>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_UPLOAD}</legend>
					<input type="submit" value="{L_UPLOAD}" class="submit" />				
				</fieldset>	
			</form>
			
			<form action="admin_lang_add.php?install=1" method="post">
				<table class="module_table">
					<tr> 
						<th colspan="5">
							{L_LANG_ADD}
						</th>
					</tr>
					
					# IF C_NO_LANG_PRESENT #
					<tr> 
						<td class="row1" style="text-align:center;">
							{L_NO_LANG_ON_SERV}
						</td>
					</tr>
					# ENDIF #
					# IF C_LANG_PRESENT #
					<tr>
						<td class="row2" style="width:120px;text-align:center;">
							{L_LANG}
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
							{L_INSTALL}
						</td>
					</tr>
					# ENDIF #
					
					# START list #
					<tr> 	
						<td class="row2" style="text-align:center;">					
							<img src="{PATH_TO_ROOT}/images/stats/countries/{list.IDENTIFIER}.png" alt="" class="valign_middle" /> <strong>{list.LANG}</strong>			
						</td>
						<td class="row2" style="vertical-align:top">	
							<strong>{L_AUTHOR}:</strong> {list.AUTHOR} {list.AUTHOR_WEBSITE}<br />
							<strong>{L_COMPAT}:</strong> PHPBoost {list.COMPAT}			
						</td>
						<td class="row2" style="text-align:center;">	
							<input type="radio" name="{list.IDLANG}activ" value="1" checked="checked" /> {L_YES}
							<input type="radio" name="{list.IDLANG}activ" value="0" /> {L_NO}
						</td>
						<td class="row2" style="text-align:center;">	
							<select name="{list.IDLANG}secure">								
								{list.OPTIONS}
							</select>
						</td>
						<td class="row2" style="text-align:center;">
							<input type="submit" name="{list.IDLANG}" value="{L_INSTALL}" class="submit" />
						</td>
					</tr>
					# END list #
				</table>
			</form>
		</div>
		