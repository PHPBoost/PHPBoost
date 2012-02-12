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
			# IF C_LANG_MAIN #
			<form action="admin_lang.php?uninstall=1" method="post">
				<table class="module_table">
					<tr> 
						<th colspan="5">
							{L_LANG_MANAGEMENT}
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
					<tr>
						<td class="row2" colspan="5">
							<strong>{L_EXPLAIN_DEFAULT_LANG}</strong>
						</td>
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
							{L_UNINSTALL}
						</td>
					</tr>
					# ENDIF #
					
					
					# START list #
					<tr> 	
						<td class="row2" style="text-align:center;">					
							<img src="{PATH_TO_ROOT}/images/stats/countries/{list.IDENTIFIER}.png" alt="" class="valign_middle" /> <span id="t{list.IDLANG}"><strong>{list.LANG}</strong></span>	
						</td>
						<td class="row2" style="vertical-align:top">	
							<strong>{L_AUTHOR}:</strong> {list.AUTHOR} {list.AUTHOR_WEBSITE}<br />
							<strong>{L_COMPAT}:</strong> PHPBoost {list.COMPAT}<br />
						</td>
						# IF list.C_LANG_NOT_DEFAULT #
						<td class="row2" style="text-align:center;">	
							<input type="radio" name="{list.IDLANG}activ" value="1" {list.LANG_ACTIV} onchange="document.location = 'admin_lang.php?activ=1&amp;id={list.IDLANG}'" /> {L_YES}
							<input type="radio" name="{list.IDLANG}activ" value="0" {list.LANG_UNACTIV} onchange="document.location = 'admin_lang.php?activ=0&amp;id={list.IDLANG}'" /> {L_NO}
						</td>
						<td class="row2" style="text-align:center;">	
							<select name="{list.IDLANG}secure" onchange="document.location = 'admin_lang.php?secure=' + this.options[this.selectedIndex].value + '&amp;id={list.IDLANG}'">'; 
								{list.OPTIONS}
							</select>
						</td>
						<td class="row2" style="text-align:center;">
							<input type="submit" name="{list.IDLANG}" value="{L_UNINSTALL}" class="submit" />
						</td>
						# ENDIF #
						
						# IF list.C_LANG_DEFAULT #
						<td class="row2" style="text-align:center;">	
							{L_YES}
						</td>
						<td class="row2" style="text-align:center;">	
							{L_GUEST}
						</td>
						<td class="row2" style="text-align:center;">
							-
						</td>
						# ENDIF #
					</tr>
					# END list #	
					<tr> 
						<th colspan="5">
							<input type="submit" name="valid" id="submit_lang" value="{L_SUBMIT}" class="submit" />
							<script type="text/javascript">
							<!--				
							document.getElementById('submit_lang').style.display = 'none';
							-->
							</script>
							&nbsp;
						</th>
					</tr>	
				</table>
			</form>
			# ENDIF #
			
			# IF C_DEL_LANG #
			<form action="admin_lang.php?uninstall=1" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_DEL_LANG}</legend>
					<dl>
						<dt><label for="drop_files">{L_DEL_LANG}</label></dt>
						<dd><label><input type="radio" name="drop_files" value="1" /> {L_YES}</label>
						<label><input type="radio" name="drop_files" id="drop_files" value="0" checked="checked" /> {L_NO}</label></dd>
					</dl>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_DELETE}</legend>
					<input type="submit" name="valid_del" value="{L_DELETE}" class="submit" />	
					<input type="hidden" name="idlang" value="{IDLANG}" />					
				</fieldset>	
			</form>
			# ENDIF #
		</div>
		