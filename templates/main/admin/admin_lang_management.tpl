		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_LANG_MANAGEMENT}</li>
				<li>
					<a href="admin_lang.php"><img src="../templates/{THEME}/images/admin/languages.png" alt="" /></a>
					<br />
					<a href="admin_lang.php" class="quick_link">{L_LANG_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_lang_add.php"><img src="../templates/{THEME}/images/admin/languages.png" alt="" /></a>
					<br />
					<a href="admin_lang_add.php" class="quick_link">{L_LANG_ADD}</a>
				</li>
			</ul>
		</div>
			
		<div id="admin_contents">		
			# START main #
			<form action="admin_lang.php?uninstall=1" method="post">
				<table class="module_table">
					<tr> 
						<th colspan="5">
							{L_LANG_MANAGEMENT}
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
					<tr>
						<td class="row2" colspan="5">
							<strong>{L_EXPLAIN_DEFAULT_LANG}</strong>
						</td>
					</tr>
					# START no_lang #
					<tr> 
						<td class="row1" style="text-align:center;">
							{L_NO_LANG_ON_SERV}
						</td>
					</tr>
					# END no_lang #
					# START lang #
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
					# END lang #
					
					
					# START list #
					<tr> 	
						<td class="row2" style="text-align:center;">					
							<img src="../images/stats/countries/{main.list.IDENTIFIER}.png" alt="" class="valign_middle" /> <span id="t{main.list.IDLANG}"><strong>{main.list.LANG}</strong>		
						</td>
						<td class="row2" style="vertical-align:top">	
							<strong>{L_AUTHOR}:</strong> {main.list.AUTHOR} {main.list.AUTHOR_WEBSITE}<br />
							<strong>{L_COMPAT}:</strong> PHPBoost {main.list.COMPAT}<br />
						</td>
						# START not_default #
						{main.list.not_default.VALUE}
						# END not_default #
						# START default #
						<td class="row2" style="text-align:center;">	
							{L_YES}
						</td>
						<td class="row2" style="text-align:center;">	
							{L_GUEST}
						</td>
						<td class="row2" style="text-align:center;">
							-
						</td>
						# END default #
					</tr>
					# END list #	
					<tr> 
						<th colspan="5">
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
					<input type="hidden" name="idlang" value="{del.IDLANG}" />					
				</fieldset>	
			</form>
			# END del #
		</div>
		