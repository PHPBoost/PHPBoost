		<div id="admin-quick-menu">
				<ul>
					<li class="title-menu">{L_DATABASE_MANAGEMENT}</li>
				<li>
					<a href="admin_database.php"><img src="database.png" alt="" /></a>
					<br />
					<a href="admin_database.php" class="quick-link">{L_DB_TOOLS}</a>
				</li>
				<li>
					<a href="admin_database.php?query=1"><img src="database.png" alt="" /></a>
					<br />
					<a href="admin_database.php?query=1" class="quick-link">{L_QUERY}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin-contents">
			# IF C_DATABASE_INDEX #

			<form method="post" action="admin_database.php?action=restore&amp;token={TOKEN}" enctype="multipart/form-data" name="upload_file">
			
			<section>
				<header>
					<h1>{L_DATABASE_MANAGEMENT}</h1>
				</header>
				<div class="content">
					<article class="block">
						<header></header>
						<div class="content">
							{L_EXPLAIN_ACTIONS}
							<div class="spacer">&nbsp;</div>
							<div class="message-helper question">
								<i class="fa fa-question"></i>
								<div class="message-helper-content">{L_EXPLAIN_ACTIONS_QUESTION}</div>
							</div>
						</div>
						<footer></footer>
					</article>
				</div>
				<footer></footer>
			</section>
			
			<section>
				<header>
					<h1>{L_DB_RESTORE}</h1>
				</header>
				<div class="content">
					<article class="block" style="float:left;width:49%;min-height:238px;margin-right:7px;">
						<header></header>
						<div class="content">
							{L_RESTORE_FROM_SERVER}
							<br /><br />
							<a href="admin_database.php?action=restore">{L_FILE_LIST}</a>
						</div>
						<footer></footer>
					</article>
					<article class="block" style="float:left;width:49%;margin-left:7px;">
						<header></header>
						<div class="content">
							{L_RESTORE_FROM_UPLOADED_FILE}
							<br /><br />
							<input type="file" class="file" name="file_sql">
							<input type="hidden" name="max_file_size" value="10485760">
							<br /><br />
							<div class="center"><button type="submit" name="" value="true">{L_RESTORE_NOW}</button></div>
						</div>
						<footer></footer>
					</article>
				</div>
				<footer></footer>
			</section>
				# INCLUDE message_helper #
			</form>

			<form action="{TARGET}" method="post">
				<table id="tables">
					<caption>{L_TABLE_LIST}</caption>
					<thead>
						<tr class="center">
							<th style="width:140px;">
								<span class="text-strong">{L_SELECTED_TABLES} <br />( <input type="checkbox" onclick="check_all(this.checked, 'id');" class="valign-middle"> {L_ALL} )</span>
							</th>
							<th>
								<span class="text-strong">{L_TABLE_NAME}</span>
							</th>
							<th style="width:70px;">
								<span class="text-strong">{L_TABLE_ROWS}</span>
							</th>
							<th style="width:100px;">
								<span class="text-strong">{L_TABLE_ENGINE}</span>
							</th>
							<th style="width:150px;">
								<span class="text-strong">{L_TABLE_COLLATION}</span>
							</th>
							<th style="width:70px;">
								<span class="text-strong">{L_TABLE_DATA}</span>
							</th>
							<th style="width:70px;">
								<span class="text-strong">{L_TABLE_FREE}</span>
							</th>
						</tr>
					</thead>
					<tfoot>
						<tr class="center"> 
							<th>
								( <input type="checkbox" onclick="check_all(this.checked, 'id');" class="valign-middle"> {L_ALL} )
							</th>
							<th>
								<strong>{NBR_TABLES}</strong>
							</th>
							<th style="text-align:right;">
								<strong>{NBR_ROWS}</strong>
							</th>
							<th>
								--
							</th>
							<th>
								--
							</th>
							<th style="text-align:right;">
								<strong>{NBR_DATA}</strong>
							</th>
							<th style="text-align:right;">
								<strong>{NBR_FREE}</strong>
							</th>
						</tr>
					</tfoot>
					<tbody>
						# START table_list #
						<tr class="center">
							<td>
								<input type="checkbox" id="id{table_list.I}" name="table_{table_list.TABLE_NAME}">
							</td>
							<td>
								<a href="admin_database_tools.php?table={table_list.TABLE_NAME}">{table_list.TABLE_NAME}</a>
							</td>
							<td style="text-align:right;">
								{table_list.TABLE_ROWS}
							</td>
							<td>
								{table_list.TABLE_ENGINE}
							</td>
							<td>
								{table_list.TABLE_COLLATION}
							</td>
							<td style="text-align:right;">
								{table_list.TABLE_DATA}
							</td>
							<td style="text-align:right;">
								{table_list.TABLE_FREE}
							</td>
						</tr>
						# END table_list #
					</tbody>
				</table>
				
				<div class="spacer">&nbsp;</div>
				
				<section>
					<header>
						<h1>{ACTION_FOR_SELECTION}</h1>
					</header>
					<div class="content">
						<ul class="center" style="width:99%;margin:20px auto;">
							<li class="small-block" style="width:20%;">
								<img src="./database.png" alt="optimize" /><br/>
								<button type="submit" name="optimize" value="true">{L_OPTIMIZE}</button>
							</li>
							<li class="small-block" style="width:20%;">
								<img src="{PATH_TO_ROOT}/templates/default/images/admin/configuration.png" alt="repair" /><br/>
								<button type="submit" name="repair" value="true">{L_REPAIR}</button>
							</li>
							<li class="small-block" style="width:20%;">
								<img src="{PATH_TO_ROOT}/templates/default/images/admin/updater.png" alt="backup" class="valign-middle" /><br/>
								<button type="submit" name="backup" value="true">{L_BACKUP}</button>
							</li>
						</ul>
					</div>
					<footer></footer>
				</section>
				
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
			<div style="width:95%;margin:auto;">
				<div class="block_contents1" style="padding:5px;padding-bottom:7px;margin-bottom:5px;">
					- <a class="small" href="admin_database.php#tables">{L_DATABASE_MANAGEMENT}</a> - <a class="small" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=structure">{TABLE_NAME}</a>
				</div>
				<menu class="dynamic-menu group center">
					<ul>
						<li>
							<a href="admin_database_tools.php?table={TABLE_NAME}&amp;action=structure"><img src="./database_mini.png"/> {L_TABLE_STRUCTURE}</a>
						</li>
						<li>
							<a href="admin_database_tools.php?table={TABLE_NAME}&amp;action=data"><img src="{PATH_TO_ROOT}/templates/default/images/admin/themes_mini.png"/> {L_TABLE_DISPLAY}</a>
						</li>
						<li>
							<a href="admin_database_tools.php?table={TABLE_NAME}&amp;action=query"><img src="{PATH_TO_ROOT}/templates/default/images/admin/tools_mini.png"/> SQL</a>
						</li>
						<li>
							<a href="admin_database_tools.php?table={TABLE_NAME}&amp;action=insert"><img src="{PATH_TO_ROOT}/templates/default/images/admin/extendfield_mini.png"/> {L_INSERT}</a>
						</li>
						<li>
							<a href="admin_database.php?table={TABLE_NAME}&amp;action=backup_table"><img src="{PATH_TO_ROOT}/templates/default/images/admin/cache_mini.png"/> {L_BACKUP}</a>
						</li>
						<li>
							<a style="color:red;" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=truncate&amp;token={TOKEN}" data-confirmation="{L_CONFIRM_TRUNCATE_TABLE}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/trash_mini.png"/> {L_TRUNCATE}</a>
						</li>
						<li>
							<a style="color:red;" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=drop&amp" data-confirmation="delete-element"><i class="fa fa-delete"></i> {L_DELETE}</a>
						</li>
					</ul>
				</menu>
			</div>
			<div class="spacer">&nbsp;</div>
			# ENDIF #
			
			<form action="admin_database.php?action=backup&amp;token={TOKEN}" method="post" name="table_list">
				<script type="text/javascript">
					<!--
						function check_select_all_tables(status)
						{
							for(var i = 0; i < {NBR_TABLES}; i++)
							{
								if( document.getElementById(i) )
									document.getElementById(i).selected = status;
							}
						}
					-->
				</script>
				
				<table>
					<caption>{L_BACKUP_DATABASE}</caption>
					<thead>
						<tr>
							<th colspan="2" style="text-align:center;">
								{L_SELECTION}
							</th>
						</tr>
					</thead>
					<tbody>
						<tr class="center">
							<td>
								<select name="table_list[]" size="8" multiple="multiple">
								# START table_list #
									<option value="{table_list.NAME}" name="table_{table_list.NAME}" id="{table_list.I}" {table_list.SELECTED}>{table_list.NAME}</option>
								# END table_list #
								</select>
								<br />
								<a class="small" href="javascript:check_select_all_tables(true);">{SELECT_ALL}</a> / <a class="small" href="javascript:check_select_all_tables(false);">{SELECT_NONE}</a>
							</td>
							<td>
								{L_EXPLAIN_BACKUP}<br />
								<img src="{PATH_TO_ROOT}/templates/default/images/admin/updater.png" alt="backup" /><br />
								<label><input type="radio" name="backup_type" checked="checked" value="all"/> {L_BACKUP_ALL}</label>
								<label><input type="radio" name="backup_type" value="struct"> {L_BACKUP_STRUCT}</label>
								<label><input type="radio" name="backup_type" value="data"/> {L_BACKUP_DATA}</label>
								<br /><br />
								<button type="submit" name="" value="true">{L_BACKUP}</button>
							</td>
						</tr>
					</tbody>
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
				<section>
					<header>
						<h1>{L_QUERY}</h1>
					</header>
					<div class="content">
						<article>
							<header></header>
							<div class="content">
								<span id="errorh"></span>
								<div class="message-helper warning">
									<i class="fa fa-warning"></i>
									<div class="message-helper-content">{L_EXPLAIN_QUERY}</div>
								</div>
								<fieldset>
									<label for="query">* {L_EXECUTED_QUERY}</label>
									<textarea rows="12" id="query" name="query">{QUERY}</textarea>
								</fieldset>
								<fieldset class="fieldset-submit" style="margin:0">
									<button type="submit" name="submit" value="true">{L_EXECUTE}</button>
								</fieldset>
							</div>
							<footer></footer>
						</article>
					</div>
					<footer></footer>
				</section>
				</form>
				
				# IF C_QUERY_RESULT #
				<section>
					<header>
						<h1>{L_RESULT}</h1>
					</header>
					<div class="content" id="executed_query">
						<article class="block">
							<header>{L_EXECUTED_QUERY}</header>
							<div class="content">
								<fieldset style="background-color:white;margin:0px">
									<p style="color:black;font-size:10px;">{QUERY_HIGHLIGHT}</p>
								</fieldset>
								
								<div style="width:99%;margin:auto;overflow:auto;padding:18px 2px">
									<table>
										# IF C_HEAD #
										<thead>
											<tr class="center">
												# START head #
												<th>{head.FIELD_NAME}</th>
												# END head #
											</tr>
										</thead>
										# ENDIF #
										<tbody>
											# START line #
											<tr>
												# START line.field #
												<td style="{line.field.STYLE}">
													{line.field.FIELD_NAME}
												</td>
												# END line.field #
											</tr>
											# END line #
										</tbody>
									</table>
								</div>
							</div>
							<footer></footer>
						</article>
					</div>
					<footer></footer>
				</section>
				# ENDIF #
			# ENDIF #

			# IF C_DATABASE_FILES #
				<table>
					<caption>{L_LIST_FILES}</caption>
					<thead>
						<tr class="center">
							<th style="text-align:left;padding-left:20px;">
								<span class="text-strong">{L_NAME}</span>
							</th>
							<th style="width:120px;">
								<span class="text-strong">{L_WEIGHT}</span>
							</th>
							<th style="width:140px;">
								<span class="text-strong">{L_DATE}</span>
							</th>
							<th style="width:120px;">
								<span class="text-strong">{L_DELETE}</span>
							</th>
						</tr>
					</thead>
					<tbody>
						# START file #
						<tr class="center">
							<td style="text-align:left;padding-left:20px;">
								<a href="admin_database.php?action=restore&amp;file={file.FILE_NAME}&amp;token={TOKEN}" data-confirmation="{L_CONFIRM_RESTORE}"><img src="./database_mini.png" alt="" style="vertical-align:middle" /></a> <a href="admin_database.php?action=restore&amp;file={file.FILE_NAME}&amp;token={TOKEN}" data-confirmation="{L_CONFIRM_RESTORE}">{file.FILE_NAME}</a>
							</td>
							<td style="width:120px;">
								{file.WEIGHT}
							</td>
							<td style="width:120px;">
								{file.FILE_DATE}
							</td>
							<td style="width:120px;">
								<a href="admin_database.php?action=restore&amp;del={file.FILE_NAME}&amp;token={TOKEN}" class="fa fa-delete" data-confirmation="delete-element"></a>
							</td>
						</tr>
						# END file #
						<tr class="center">
							<td colspan="4">
								# INCLUDE message_helper #
								{L_INFO}
							</td>
						</tr>
					</tbody>
				</table>

			# ENDIF #
		</div>
		