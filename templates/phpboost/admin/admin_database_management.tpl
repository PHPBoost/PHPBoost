		<div id="admin_quick_menu">
				<ul>
					<li class="title_menu">{L_DATABASE_MANAGEMENT}</li>
				<li>
					<a href="admin_database.php"><img src="../templates/{THEME}/images/admin/database.png" alt="" /></a>
					<br />
					<a href="admin_database.php" class="quick_link">{L_DB_TOOLS}</a>
				</li>
				<li>
					<a href="admin_database.php?query=1"><img src="../templates/{THEME}/images/admin/database.png" alt="" /></a>
					<br />
					<a href="admin_database.php?query=1" class="quick_link">{L_QUERY}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			# START index #

			<table class="module_table">
				<tr>
					<th colspan="2" style="text-align:center;">
						{L_DATABASE_MANAGEMENT}
					</th>
				</tr>
				<tr>
					<td colspan="2" class="row1">
						{L_EXPLAIN}
					</td>
				</tr>
				<tr>
					<th colspan="2" style="text-align:center;">
						{L_DB_RESTORE}
					</th>
				</tr>
				<tr>
					<td class="row1" style="text-align:center;">
						{L_RESTORE_FROM_SERVER}
						<br /><br />
						<a href="admin_database.php?action=restore">{L_FILE_LIST}</a>
					</td>
					<td class="row1" style="text-align:center;">
						{L_RESTORE_FROM_UPLOADED_FILE}
						<br /><br />
						<form method="post" action="admin_database.php?action=restore" enctype="multipart/form-data" name="upload_file">
							<input type="file" class="submit" name="file_sql" />
							<input type="hidden" name="max_file_size" value="10485760" />
							<br /><br />
							<input type="submit" class="submit" value="{L_RESTORE_NOW}" />
						</form>
					</td>			
				</tr>
				# IF C_ERROR_HANDLER #
				<tr>
					<td class="row1" colspan="2" style="text-align:center;">
						<span id="errorh"></span>
						<div class="{ERRORH_CLASS}">
							<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
						</div>
					</td>
				</tr>
				# ENDIF #
			</table>

			<br />
				
			<form action="{index.TARGET}" method="post" name="table_list">
				<table class="module_table">
					<tr>
						<th colspan="7" style="text-align:center;">
							{L_TABLE_LIST}
						</td>
					</tr>
					<tr style="text-align:center;">			
						<td class="row3" style="width:140px;">
							{L_SELECTED_TABLES} <br />(<input type="checkbox" onClick="check_all(this.checked, 'id');" class="valign_middle" /> {L_ALL})
						</td>
						<td class="row3">
							{L_TABLE_NAME}
						</td>
						<td class="row3" style="width:70px;">
							{L_TABLE_ROWS}
						</td>
						<td class="row3" style="width:100px;">
							{L_TABLE_ENGINE}
						</td>
						<td class="row3" style="width:150px;">
							{L_TABLE_COLLATION}
						</td>
						<td class="row3" style="width:70px;">
							{L_TABLE_DATA}
						</td>
						<td class="row3" style="width:70px;">
							{L_TABLE_FREE}
						</td>
					</tr>
					# START index.table_list #
					<tr>			
						<td class="row1" style="text-align:center;">
							<input type="checkbox" id="id{index.table_list.I}" name="table_{index.table_list.TABLE_NAME}" />
						</td>
						<td class="row1">
							<strong>{index.table_list.TABLE_NAME}</strong>
						</td>			
						<td class="row1" style="text-align:right;">
							{index.table_list.TABLE_ROWS}
						</td>
						<td class="row1" style="text-align:center;">
							{index.table_list.TABLE_ENGINE}
						</td>
						<td class="row1" style="text-align:center;">
							{index.table_list.TABLE_COLLATION}
						</td>
						<td class="row1" style="text-align:right;">
							{index.table_list.TABLE_DATA}
						</td>
						<td class="row1" style="text-align:right;">
							{index.table_list.TABLE_FREE}
						</td>
					</tr>
					# END index.table_list #
					<tr style="text-align:center;"> 
						<td class="row3">
							( <input type="checkbox" onClick="check_all(this.checked, 'id');" class="valign_middle" /> {L_ALL})
						</td>
						<td class="row3">
							<strong>{NBR_TABLES}</strong>
						</td>
						<td class="row3" style="text-align:right;">
							<strong>{NBR_ROWS}</strong>
						</td>
						<td class="row3">
							--
						</td>
						<td class="row3">
							--
						</td>
						<td class="row3" style="text-align:right;">
							<strong>{NBR_DATA}</strong>
						</td>
						<td class="row3" style="text-align:right;">
							<strong>{NBR_FREE}</strong>
						</td>
					</tr>
				</table>
							
				<table class="module_table">
					<tr>
						<th colspan="7" style="text-align:center;">
							{ACTION_FOR_SELECTION}
						</th>
					</tr>
					<tr> 
						<td colspan="7" class="row1" style="text-align:center;">
						<table style="margin:auto;">
							<tr>
								<td>
									<img src="../templates/{THEME}/images/admin/database.png" alt="optimize" />
								</td>
								<td>
									<img src="../templates/{THEME}/images/admin/configuration.png" alt="repair" />
								</td>
								<td>
									<img src="../templates/{THEME}/images/admin/updater.png" alt="optimize" class="valign_middle" />
								</td>
								</tr>
								<tr>
									<td>
										<input type="submit" name="optimize" value="{L_OPTIMIZE}" class="submit" />
									</td>
									<td>
										<input type="submit" name="repair" value="{L_REPAIR}" class="submit" />
									</td>
									<td>
										<input type="submit" name="backup" value="{L_BACKUP}" class="submit" />
									</td>
								</tr>
							</table>		
						</td>
					</tr>
				</table>
				<script>
					<!--
					function check_all(status, id)
					{
						var i;
						for(i = 0; i < {NBR_TABLES}; i++)
							document.getElementById(id + i).checked = status;
					}	
					-->
				</script>
			</form>

			# END index #

			# START backup #

			<form action="admin_database.php?action=backup" method="post" name="table_list">
				<script type="text/javascript">
					<!--
						function check_select_multiple(status)
						{
							var i;

							for(i = 0; i < {NBR_TABLES}; i++)
							{
								if( document.getElementById(i) )
									document.getElementById(i).selected = status;
							}
						}
					-->
				</script>
				
				<table class="module_table">
					<tr>
						<th colspan="2" style="text-align:center;">
							{L_BACKUP_DATABASE}
						</th>
					</tr>
					<tr>
						<td class="row1" style="text-align:center;">
							{L_SELECTION}
							<br /><br />
							<select name="table_list[]" size="8" multiple="multiple">
							# START backup.table_list #
								<option value="{backup.table_list.NAME}" name="table_{backup.table_list.NAME}" id="{backup.table_list.I}" {backup.table_list.SELECTED}>{backup.table_list.NAME}</option>
							# END backup.table_list #
							</select>
							<br /><br />
							<a href="javascript:check_select_multiple(false);">{SELECT_NONE}</a> /
							<a href="javascript:check_select_multiple(true);">{SELECT_ALL}</a>
						</td>
						<td class="row1" style="text-align:center;">
							{L_EXPLAIN_BACKUP}
							<br /><br />
							<img src="../templates/{THEME}/images/admin/updater.png" alt="backup" /><br />
							<label><input type="radio" name="backup_type" checked="checked" value="all"/> {L_BACKUP_ALL}</label>
							<label><input type="radio" name="backup_type" value="struct" /> {L_BACKUP_STRUCT}</label>
							<label><input type="radio" name="backup_type" value="data"/> {L_BACKUP_DATA}</label>
							<br /><br />
							<input type="submit" value="{L_BACKUP}" class="submit" />
						</td>
					</tr>
					<tr>
						<td>
						</td>
					</tr>
				</table>
			</form>

			# END backup #

			# START query #

				<form action="admin_database.php?query=1#query" method="post">
					<table class="module_table">
						<tr>
							<th style="text-align:center;">
								{L_QUERY}
							</th>
						</tr>
						<tr>
							<td class="row1">
								{L_EXPLAIN_QUERY}
							</td>
						</tr>
						<tr>
							<td class="row1" style="text-align:center;">
								<span id="query">&nbsp;</span>
								<textarea class="post" style="width:40%; height: 150px;" name="query">{QUERY}</textarea>
							</td>
						</tr>
					</table>
				
					<br /><br />
					
					<fieldset class="fieldset_submit">
						<legend>{L_EXECUTE}</legend>
						<input type="submit" class="submit" value="{L_EXECUTE}" />		
					</fieldset>	
				</form>
				
				# START query.select_result #
				<table class="module_table">
					<tr>
						<th>
							{L_RESULT}
						</th>
					</tr>
					<tr>
						<td class="row2">				
							<fieldset style="background-color:white;">
								<legend><strong>{L_EXECUTED_QUERY}:</strong></legend>
								<div style="color:black;font-size:10px;">{query.select_result.QUERY}</div>
								<br />
							</fieldset>
						</td>
					</tr>
					<tr>
						<td>
							<table style="width:auto;">
								# START query.select_result.line #
								<tr>
									# START query.select_result.line.field #
									<td class="{query.select_result.line.field.CLASS}" style="{query.select_result.line.field.STYLE}">
										{query.select_result.line.field.FIELD}
									</td>
									# END query.select_result.line.field #
								</tr>
								# END query.select_result.line #
							</table>
						</td>
					</tr>
				</table>
				<br /><br />
				# END query.select_result #
				
			# END query #

			# START list_files #
				<script type="text/javascript">
				<!--
				function Confirm_del() {
					return confirm("{L_CONFIRM_DEL}");
				}
				-->	
				</script>
				<table class="module_table">
					<tr> 
						<th colspan="4">
							{L_LIST_FILES}
						</th>
					</tr>
					# IF C_ERROR_HANDLER #
					<tr>
						<td class="row1" colspan="4" style="text-align:center;">
							<span id="errorh"></span>
							<div class="{ERRORH_CLASS}">
								<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
							</div>
						</td>
					</tr>
					# ENDIF #
					
					<tr>
						<td class="row1" colspan="4" style="text-align:center;">
							{L_INFO}
						</td>
					</tr>
					<tr>
						<td class="row1" style="padding-left:20px;">
							{L_NAME}
						</td>
						<td class="row1" style="text-align:center;width:120px;">
							{L_WEIGHT}
						</td>
						<td class="row1" style="text-align:center;width:140px;">
							{L_DATE}
						</td>
						<td class="row1" style="text-align:center;width:120px;">
							{L_DELETE}
						</td>
					</tr>
					# START list_files.file #
					<tr>
						<td class="row1" style="padding-left:20px;">
							<a href="admin_database.php?action=restore&amp;file={list_files.file.FILE_NAME}" onclick="javascript:return confirm('{L_CONFIRM_RESTORE}');"><img src="../templates/{THEME}/images/admin/database_mini.png" alt="" style="vertical-align:middle" /></a> <a href="admin_database.php?action=restore&amp;file={list_files.file.FILE_NAME}" onclick="javascript:return confirm('{L_CONFIRM_RESTORE}');">{list_files.file.FILE_NAME}</a>
						</td>
						<td class="row1" style="text-align:center;width:120px;">
							{list_files.file.WEIGHT}
						</td>
						<td class="row1" style="text-align:center;width:120px;">
							{list_files.file.FILE_DATE}
						</td>
						<td class="row1" style="text-align:center;width:120px;">
							<a href="admin_database.php?action=restore&amp;del={list_files.file.FILE_NAME}" onclick="javascript:return Confirm_del()"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="del" /></a>
						</td>
					</tr>
					# END list_files.file #
				</table>

			# END list_files #
		</div>
		