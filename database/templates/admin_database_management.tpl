		<div id="admin_quick_menu">
				<ul>
					<li class="title_menu">{L_DATABASE_MANAGEMENT}</li>
				<li>
					<a href="admin_database.php"><img src="database.png" alt="" /></a>
					<br />
					<a href="admin_database.php" class="quick_link">{L_DB_TOOLS}</a>
				</li>
				<li>
					<a href="admin_database.php?query=1"><img src="database.png" alt="" /></a>
					<br />
					<a href="admin_database.php?query=1" class="quick_link">{L_QUERY}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			# IF C_DATABASE_INDEX #

			<form method="post" action="admin_database.php?action=restore&amp;token={TOKEN}" enctype="multipart/form-data" name="upload_file">
			<div class="block_container">
				<div class="block_top" style="text-align:center;">
					{L_DATABASE_MANAGEMENT}
				</div>
				<div class="block_contents1">
					{L_EXPLAIN}
				</div>
				<div class="block_top" style="text-align:center;">
					{L_DB_RESTORE}
				</div>
				<div class="block_contents1">
					<div style="float:left;width:50%">
						{L_RESTORE_FROM_SERVER}
						<br /><br />
						<a href="admin_database.php?action=restore">{L_FILE_LIST}</a>
					</div>
					<div style="float:left;width:50%">
						{L_RESTORE_FROM_UPLOADED_FILE}
						<br /><br />
						<input type="file" class="file" name="file_sql" />
						<input type="hidden" name="max_file_size" value="10485760" />
						<br /><br />
						<input type="submit" class="submit" value="{L_RESTORE_NOW}" />
					</div>
					<div class="spacer"></div>
				</div>
				# INCLUDE message_helper #
			</div>
			</form>

			<br />
				
			<form action="{TARGET}" method="post">
				<table class="module_table" id="tables">
					<tr>
						<th colspan="7" style="text-align:center;">
							{L_TABLE_LIST}
						</th>
					</tr>
					<tr style="text-align:center;">			
						<td class="row3" style="width:140px;">
							{L_SELECTED_TABLES} <br />(<input type="checkbox" onclick="check_all(this.checked, 'id');" class="valign_middle" /> {L_ALL})
						</td>
						<td class="row2">
							{L_TABLE_NAME}
						</td>
						<td class="row2" style="width:70px;">
							{L_TABLE_ROWS}
						</td>
						<td class="row2" style="width:100px;">
							{L_TABLE_ENGINE}
						</td>
						<td class="row2" style="width:150px;">
							{L_TABLE_COLLATION}
						</td>
						<td class="row2" style="width:70px;">
							{L_TABLE_DATA}
						</td>
						<td class="row2" style="width:70px;">
							{L_TABLE_FREE}
						</td>
					</tr>
					# START table_list #
					<tr>			
						<td class="row2" style="text-align:center;">
							<input type="checkbox" id="id{table_list.I}" name="table_{table_list.TABLE_NAME}" />
						</td>
						<td class="row2">
							<a href="admin_database_tools.php?table={table_list.TABLE_NAME}">{table_list.TABLE_NAME}</a>
						</td>			
						<td class="row2" style="text-align:right;">
							{table_list.TABLE_ROWS}
						</td>
						<td class="row2" style="text-align:center;">
							{table_list.TABLE_ENGINE}
						</td>
						<td class="row2" style="text-align:center;">
							{table_list.TABLE_COLLATION}
						</td>
						<td class="row2" style="text-align:right;">
							{table_list.TABLE_DATA}
						</td>
						<td class="row2" style="text-align:right;">
							{table_list.TABLE_FREE}
						</td>
					</tr>
					# END table_list #
					<tr style="text-align:center;"> 
						<td class="row3">
							( <input type="checkbox" onclick="check_all(this.checked, 'id');" class="valign_middle" /> {L_ALL})
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
							
				<div class="block_container">
					<div class="block_top text_center">
						{ACTION_FOR_SELECTION}
					</div>
					<div class="block_contents1 text_center">
						<table style="margin:auto;">
							<tr>
								<td><img src="./database.png" alt="optimize" /></td>
								<td>&nbsp;&nbsp;<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/configuration.png" alt="repair" /></td>
								<td>&nbsp;&nbsp;<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/updater.png" alt="optimize" class="valign_middle" /></td>
							</tr>
							<tr>
								<td><input type="submit" name="optimize" value="{L_OPTIMIZE}" class="submit" /></td>
								<td>&nbsp;&nbsp;<input type="submit" name="repair" value="{L_REPAIR}" class="submit" /></td>
								<td>&nbsp;&nbsp;<input type="submit" name="backup" value="{L_BACKUP}" class="submit" /></td>
							</tr>
						</table>
					</div>
				</div>
				
				<script type="text/javascript">
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

			# ENDIF #
			
			
			# IF C_DATABASE_BACKUP #
			# IF TABLE_NAME #
			<script type="text/javascript">
			<!--
			function Confirm_del_table() {
				return confirm("{L_CONFIRM_DELETE_TABLE}");
			}
			function Confirm_truncate_table() {
				return confirm("{L_CONFIRM_TRUNCATE_TABLE}");
			}
			-->	
			</script>
			<div style="width:95%;margin:auto;">	
				<div class="block_contents1" style="padding:5px;padding-bottom:7px;margin-bottom:5px;">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/li.png" class="valign_middle" alt="" /> <a class="small_link" href="admin_database.php#tables">{L_DATABASE_MANAGEMENT}</a> <img src="{PATH_TO_ROOT}/templates/{THEME}/images/li.png" class="valign_middle" alt="" /> <a class="small_link" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=structure">{TABLE_NAME}</a>
				</div>
				<div class="dynamic_menu" style="z-index:0;float:none">
					<ul>
						<li>
							<h5 class="links" style=""><img src="./database_mini.png" class="valign_middle" alt="" /> <a href="admin_database_tools.php?table={TABLE_NAME}&amp;action=structure">{L_TABLE_STRUCTURE}</a></h5>
						</li>
						<li>
							<h5 class="links"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/themes_mini.png" class="valign_middle" alt="" /> <a href="admin_database_tools.php?table={TABLE_NAME}&amp;action=data">{L_TABLE_DISPLAY}</a></h5>
						</li>
						<li>
							<h5 class="links"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/tools_mini.png" class="valign_middle" alt="" /> <a href="admin_database_tools.php?table={TABLE_NAME}&amp;action=query">SQL</a></h5>
						</li>
						<li>
							<h5 class="links"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/extendfield_mini.png" class="valign_middle" alt="" /> <a href="admin_database_tools.php?table={TABLE_NAME}&amp;action=insert">{L_INSERT}</a></h5>
						</li>
						<li>
							<h5 class="links"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/cache_mini.png" class="valign_middle" alt="" /> <a href="admin_database.php?table={TABLE_NAME}&amp;action=backup_table">{L_BACKUP}</a></h5>
						</li>
						<li>
							<h5 class="links"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/trash_mini.png" class="valign_middle" alt="" /> <a onclick="javascript:return Confirm_truncate_table()" style="color:red" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=truncate">{L_TRUNCATE}</a></h5>
						</li>
						<li>
							<h5 class="links"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" class="valign_middle" alt="" /> <a onclick="javascript:return Confirm_del_table()" style="color:red" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=drop">{L_DELETE}</a></h5>
						</li>
					</ul>
				</div>
			</div>
			<br />
			<br />
			# ENDIF #
			
			<form action="admin_database.php?action=backup&amp;token={TOKEN}" method="post" name="table_list">
				<script type="text/javascript">
					<!--
						function check_select_multiple(status)
						{
							for(var i = 0; i < {NBR_TABLES}; i++)
							{
								if( document.getElementById(i) )
									document.getElementById(i).selected = status;
							}
						}
					-->
				</script>
				
				<table class="module_table" style="padding:0;margin-top:11px;">
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
							# START table_list #
								<option value="{table_list.NAME}" name="table_{table_list.NAME}" id="{table_list.I}" {table_list.SELECTED}>{table_list.NAME}</option>
							# END table_list #
							</select>
							<br /><br />
							<a class="small_link" href="javascript:check_select_multiple(true);">{SELECT_ALL}</a>/<a class="small_link" href="javascript:check_select_multiple(false);">{SELECT_NONE}</a>
						</td>
						<td class="row1" style="text-align:center;">
							{L_EXPLAIN_BACKUP}
							<br /><br />
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/updater.png" alt="backup" /><br />
							<label><input type="radio" name="backup_type" checked="checked" value="all"/> {L_BACKUP_ALL}</label>
							<label><input type="radio" name="backup_type" value="struct" /> {L_BACKUP_STRUCT}</label>
							<label><input type="radio" name="backup_type" value="data"/> {L_BACKUP_DATA}</label>
							<br /><br />
							<input type="submit" value="{L_BACKUP}" class="submit" />
						</td>
					</tr>
				</table>
			</form>
			# ENDIF #

			
			# IF C_DATABASE_QUERY #
				<script type="text/javascript">
				<!--
				function check_form(){
					var query = document.getElementById('query').value;
					var query_lowercase = query.toLowerCase();
					var check_query = false;
					var keyword = new Array('delete', 'drop', 'truncate');
					
					if( query == "" ) {
						alert("{L_REQUIRE}");
						return false;
				    }
					
					//V�rification de la requ�te => alerte si elle contient un des mots cl�s DELETE, DROP ou TRUNCATE.
					for(i = 0; i < keyword.length; i++)
					{
						if( typeof(strpos(query_lowercase, keyword[i])) != 'boolean' )
						{
							check_query = true;
							break;
						}
					}
					if( check_query )
					{
						return confirm("{L_CONFIRM_QUERY}\n" + query);
					}
					return true;
				}
				-->	
				</script>
				
				<form action="admin_database.php?query=1&amp;token={TOKEN}#executed_query" method="post" onsubmit="return check_form();">
				<div class="block_container">
					<div class="block_top">
						{L_QUERY}
					</div>
					<div class="block_contents2">
						<span id="errorh"></span>
						<div class="warning">
							{L_EXPLAIN_QUERY}
						</div>
					</div>
					<div class="block_top">
						* {L_EXECUTED_QUERY}
					</div>
					<div class="block_contents2">
						<textarea class="post" rows="12" id="query" name="query">{QUERY}</textarea>
					</div>
					<fieldset class="fieldset_submit" style="margin:0">
						<legend>{L_EXECUTE}</legend>
						<input type="submit" class="submit" value="{L_EXECUTE}" />		
					</fieldset>
				</div>
				</form>
				
				# IF C_QUERY_RESULT #
				<div class="block_container" style="width:98%;margin-top:0" id="executed_query">
					<div class="block_top">
						{L_RESULT}
					</div>
					<div class="block_contents2">
						<fieldset style="background-color:white;margin:0px">
							<legend><strong>{L_EXECUTED_QUERY}:</strong></legend>
							<p style="color:black;font-size:10px;">{QUERY_HIGHLIGHT}</p>
						</fieldset>
						
						<div style="width:99%;margin:auto;overflow:auto;padding:18px 2px">
							<table class="module_table">
								# START line #
								<tr>
									# START line.field #
									<td class="{line.field.CLASS}" style="{line.field.STYLE}">
										{line.field.FIELD}
									</td>
									# END line.field #
								</tr>
								# END line #
							</table>
						</div>
					</div>
				</div>
				# ENDIF #
			# ENDIF #

			
			# IF C_DATABASE_FILES #
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
					# INCLUDE message_helper #
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
					# START file #
					<tr>
						<td class="row2" style="padding-left:20px;">
							<a href="admin_database.php?action=restore&amp;file={file.FILE_NAME}&amp;token={TOKEN}" onclick="javascript:return confirm('{L_CONFIRM_RESTORE}');"><img src="./database_mini.png" alt="" style="vertical-align:middle" /></a> <a href="admin_database.php?action=restore&amp;file={file.FILE_NAME}&amp;token={TOKEN}" onclick="javascript:return confirm('{L_CONFIRM_RESTORE}');">{file.FILE_NAME}</a>
						</td>
						<td class="row2" style="text-align:center;width:120px;">
							{file.WEIGHT}
						</td>
						<td class="row2" style="text-align:center;width:120px;">
							{file.FILE_DATE}
						</td>
						<td class="row2" style="text-align:center;width:120px;">
							<a href="admin_database.php?action=restore&amp;del={file.FILE_NAME}&amp;token={TOKEN}" onclick="javascript:return Confirm_del()"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="del" /></a>
						</td>
					</tr>
					# END file #
				</table>

			# ENDIF #
		</div>
		