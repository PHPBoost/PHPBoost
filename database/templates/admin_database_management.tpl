		<nav id="admin-quick-menu">
				<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
					<i class="fa fa-bars" aria-hidden="true"></i> {L_DATABASE_MANAGEMENT}
				</a>
				<ul>
					<li>
						<a href="admin_database.php" class="quick-link">{L_DB_TOOLS}</a>
					</li>
					<li>
						<a href="admin_database.php?query=1" class="quick-link">{L_QUERY}</a>
					</li>
					<li>
						<a href="${relative_url(DatabaseUrlBuilder::configuration())}" class="quick-link">${LangLoader::get_message('configuration', 'admin-common')}</a>
					</li>
					<li>
						<a href="${relative_url(DatabaseUrlBuilder::documentation())}" class="quick-link">${LangLoader::get_message('module.documentation', 'admin-modules-common')}</a>
					</li>
				</ul>
		</nav>

		<div id="admin-contents">
			# IF C_DATABASE_INDEX #

				# INCLUDE message_helper #

				<form method="post" action="admin_database.php?action=restore&amp;token={TOKEN}" enctype="multipart/form-data" name="upload_file">

					<fieldset>
						<legend><h1>{L_DATABASE_MANAGEMENT}</h1></legend>
						<div class="fieldset-inset">
							{L_EXPLAIN_ACTIONS}
							<div class="spacer"></div>
							<div class="message-helper bgc question">{L_EXPLAIN_ACTIONS_QUESTION}</div>
						</div>
					</fieldset>
					<fieldset>
						<legend>{L_DB_RESTORE}</legend>
						<div class="fieldset-inset elements-container columns-2 no-style">
							<div class="db-restore block">
								{L_RESTORE_FROM_SERVER}
								<br /><br />
								<a href="admin_database.php?action=restore">{L_FILE_LIST}</a>
							</div>
							<div class="db-restore block">
								{L_RESTORE_FROM_UPLOADED_FILE}
								<br /><br />
								<label for="select-file-to-restore">
									<input id="select-file-to-restore" type="file" class="file" name="file_sql">
									<span class="sr-only">${LangLoader::get_message('db_select_db_for_restore', 'common', 'database')}</span>
								</label>
								<br /><br />
								<div class="align-center">
									<button type="submit" id="submit-file-to-restore" name="" value="true" class="button submit">{L_RESTORE_NOW}</button>
									<input type="hidden" name="token" value="{TOKEN}">
								</div>
							</div>
						</div>
					</fieldset>
				</form>

				<form action="{TARGET}" method="post">
					<table class="table">
						<caption>{L_TABLE_LIST}</caption>
						<thead>
							<tr>
								<th>
									<span class="text-strong">{L_SELECTED_TABLES}</span>
								</th>
								<th>
									<span class="text-strong">{L_TABLE_NAME}</span>
								</th>
								<th class="td70">
									<span class="text-strong">{L_TABLE_ROWS}</span>
								</th>
								<th class="td100">
									<span class="text-strong">{L_TABLE_ENGINE}</span>
								</th>
								<th class="td150">
									<span class="text-strong">{L_TABLE_COLLATION}</span>
								</th>
								<th class="td70">
									<span class="text-strong">{L_TABLE_DATA}</span>
								</th>
								<th class="td70">
									<span class="text-strong">{L_TABLE_FREE}</span>
								</th>
							</tr>
						</thead>
						<tbody>
							# START table_list #
								<tr class="align-center">
									<td class="mini-checkbox">
										<div class="form-field-checkbox">
											<label class="checkbox" for="id{table_list.I}">
												<input type="checkbox" id="id{table_list.I}" name="table_{table_list.TABLE_NAME}" />
												<span>&nbsp;</span>
												<span class="sr-only">{L_SELECTED_TABLES}</span>
											</label>
										</div>
									</td>
									<td>
										<a href="admin_database_tools.php?table={table_list.TABLE_NAME}">{table_list.TABLE_NAME}</a>
									</td>
									<td>{table_list.TABLE_ROWS}</td>
									<td>{table_list.TABLE_ENGINE}</td>
									<td>{table_list.TABLE_COLLATION}</td>
									<td>{table_list.TABLE_DATA}</td>
									<td>{table_list.TABLE_FREE}</td>
								</tr>
							# END table_list #
							<tr class="align-center">
								<td class="mini-checkbox">
									<div class="form-field-checkbox">
										<label class="checkbox" for="check-all">
											<input type="checkbox" id="check-all" onclick="check_all(this.checked, 'id');" class="valign-middle">
											<span class="valign-bottom">{L_ALL}</span>
										</label>
									</div>
								</td>
								<td><strong>{NBR_TABLES}</strong></td>
								<td><strong>{NBR_ROWS}</strong></td>
								<td>--</td>
								<td>--</td>
								<td><strong>{NBR_DATA}</strong></td>
								<td><strong>{NBR_FREE}</strong></td>
							</tr>
						</tbody>
					</table>

					<fieldset>
						<legend>{ACTION_FOR_SELECTION}</legend>
						<div class="fieldset-inset">
							<input type="hidden" name="token" value="{TOKEN}">
							<div class="cell-flex cell-columns-3 no-style">
								<div class="cell">
									<button type="submit" name="optimize" value="true" class="button submit">
										<i class="fa fa-chart-bar fa-fw" aria-hidden="true"></i>
										{L_OPTIMIZE}
									</button>
								</div>
								<div class="cell">

									<button type="submit" name="repair" value="true" class="button submit">
										<i class="fa fa-cogs fa-fw" aria-hidden="true"></i>
										{L_REPAIR}
									</button>
								</div>
								<div class="cell">

									<button type="submit" name="backup" value="true" class="button submit">
										<i class="fa fa-save fa-fw" aria-hidden="true"></i>
										{L_BACKUP}
									</button>
								</div>
							</div>
						</div>
					</fieldset>

					<script>
						<!--
						$('#submit-file-to-restore').on('click', function() {
							if ($('#select-file-to-restore')[0].files[0].size > {RESTORE_UPLOADED_FILE_MAX_SIZE})
							{
								alert("{L_RESTORE_UPLOADED_FILE_SIZE_EXCEEDED}");
								return false;
							}
							return true;
						});

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
				<div>
					<div class="block-contents1">
						<a class="small" href="admin_database.php#tables">{L_DATABASE_MANAGEMENT}</a> - <a class="small" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=structure">{TABLE_NAME}</a>
					</div>
					<nav id="cssmenu-database-backup" class="cssmenu cssmenu-group">
						<ul>
							<li>
								<a class="cssmenu-title" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=structure">
									<i class="fa fa-fw fa-code-branch" aria-hidden="true"></i> {L_TABLE_STRUCTURE}
								</a>
							</li>
							<li>
								<a class="cssmenu-title" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=data">
									<i class="fa fa-fw fa-laptop" aria-hidden="true"></i> {L_TABLE_DISPLAY}
								</a>
							</li>
							<li>
								<a class="cssmenu-title" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=query">
									<i class="fa fa-fw fa-wrench" aria-hidden="true"></i> SQL
								</a>
							</li>
							<li>
								<a class="cssmenu-title" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=insert">
									<i class="fa fa-fw fa-plus" aria-hidden="true"></i> {L_INSERT}
								</a>
							</li>
							<li>
								<a class="cssmenu-title" href="admin_database.php?table={TABLE_NAME}&amp;action=backup_table">
									<i class="fa fa-fw fa-save" aria-hidden="true"></i> {L_BACKUP}
								</a>
							</li>
							<li>
								<a class="cssmenu-title" style="color:red;" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=truncate&amp;token={TOKEN}" data-confirmation="{L_CONFIRM_TRUNCATE_TABLE}">
									<i class="fa fa-fw fa-share-square" aria-hidden="true"></i> {L_TRUNCATE}
								</a>
							</li>
							<li>
								<a class="cssmenu-title" style="color:red;" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=drop&amp;token={TOKEN}" data-confirmation="delete-element">
									<i class="fa fa-fw fa-trash-alt" aria-hidden="true"></i> {L_DELETE}
								</a>
							</li>
						</ul>
					</nav>
					<script type="text/javascript">
						$("#cssmenu-database-backup").menumaker({
							title: "&nbsp;  ",
							format: "multitoggle",
							breakpoint: 768
						});
					</script>
				</div>
				<div class="spacer"></div>
				# ENDIF #

				<form action="admin_database.php?action=backup" method="post" name="table_list" class="fieldset-content">
					<script>
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
					<fieldset>
						<legend>{L_BACKUP_DATABASE}</legend>

						<div class="fieldset-inset">
							<div class="form-element full-field">{L_EXPLAIN_BACKUP}
							</div>
							<div class="form-element third-field">
								<label for="table_list[]">${LangLoader::get_message('db_table_list', 'common', 'database')}</label>
								<select id="table_list[]" name="table_list[]" size="8" multiple="multiple">
								# START table_list #
									<option value="{table_list.NAME}" name="table_{table_list.NAME}" id="{table_list.I}" {table_list.SELECTED}>{table_list.NAME}</option>
								# END table_list #
								</select>
								<p class="align-center">
									<a href="javascript:check_select_all_tables(true);">{SELECT_ALL}</a> / <a href="javascript:check_select_all_tables(false);">{SELECT_NONE}</a>
								</p>

							</div>
							<div class="form-element third-field top-field align-center custom-radio">
								<label for="">{L_SELECTION}</label>
								<div class="form-field form-field-radio-button">
									<div class="form-field-radio">
										<label class="radio" for="backup_all">
											<input type="radio" id="backup_all" name="backup_type" checked="checked" value="all"/>
											<span>{L_BACKUP_ALL}</span>
										</label>
									</div>
									<div class="form-field-radio">
										<label class="radio" for="backup_struct">
											<input type="radio" id="backup_struct" name="backup_type" value="struct">
											<span>{L_BACKUP_STRUCT}</span>
										</label>
									</div>
									<div class="form-field-radio">
										<label class="radio" for="backup_data">
											<input type="radio" id="backup_data" name="backup_type" value="data"/>
											<span>{L_BACKUP_DATA}</span>
										</label>
									</div>
								</div>
							</div>
							<div class="form-element third-field top-field custom-checkbox">
								<label for="compress_file">{L_COMPRESS_FILE}</label>
								<div class="form-field">
									<div class="form-field-checkbox">
										<label class="checkbox" for="compress_file">
											<input type="checkbox" name="compress_file" id="compress_file" checked="checked">
											<span>&nbsp;</span>
										</label>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
					<fieldset class="fieldset-submit">
						<legend>{L_UPDATE}</legend>
						<div class="fieldset-inset">
							<button type="submit" name="" value="true" class="button submit">{L_BACKUP}</button>
							<input type="hidden" name="token" value="{TOKEN}">
						</div>
					</fieldset>
				</form>
			# ENDIF #

			# IF C_DATABASE_QUERY #
				<script>
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

						//Verification de la requete => alerte si elle contient un des mots cles DELETE, DROP ou TRUNCATE.
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
					<fieldset>
						<legend>{L_QUERY}</legend>
						<div class="fieldset-inset">
							<div class="content">
								<article>
									<header></header>
									<div class="content">
										<span id="errorh"></span>
										<div class="message-helper bgc warning">{L_EXPLAIN_QUERY}</div>
										<fieldset>
											<label for="query">* {L_EXECUTED_QUERY}</label>
											<textarea rows="12" id="query" name="query">{QUERY}</textarea>
										</fieldset>
										<fieldset class="fieldset-submit">
											<button type="submit" name="submit" value="true" class="button submit">{L_EXECUTE}</button>
											<input type="hidden" name="token" value="{TOKEN}">
										</fieldset>
									</div>
									<footer></footer>
								</article>
							</div>
						</div>
					</fieldset>
				</form>

				# IF C_QUERY_RESULT #
					<fieldset>
						<legend>{L_RESULT}</legend>
						<div class="fieldset-inset">
							<div class="content" id="executed_query">
								<article class="block">
									<header>{L_EXECUTED_QUERY}</header>
									<div class="content">
										<fieldset class="db-executed-query">
											<p>{QUERY_HIGHLIGHT}</p>
										</fieldset>

										<div class="db-query-result">
											<table class="table large-table">
												# IF C_HEAD #
												<thead>
													<tr>
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
														<td>
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
						</div>
					</fieldset>
				# ENDIF #
			# ENDIF #

			# IF C_DATABASE_FILES #
				# INCLUDE message_helper #
				<table class="table">
					<caption>{L_LIST_FILES}</caption>
					<thead>
						<tr>
							<th>{L_NAME}</th>
							<th>{L_WEIGHT}</th>
							<th>{L_DATE}</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="4">
								# IF C_FILES #{L_INFO}# ELSE #${LangLoader::get_message('no_item_now', 'common')}# ENDIF #
							</td>
						</tr>
						# START file #
							<tr>
								<td>
									<a href="admin_database.php?action=restore&amp;file={file.FILE_NAME}&amp;token={TOKEN}" aria-label="{L_RESTORE} {file.FILE_NAME}" data-confirmation="{L_CONFIRM_RESTORE}">
										<i class="fa fa-server" aria-hidden="true"></i>
										{file.FILE_NAME}
									</a>
								</td>
								<td>{file.WEIGHT}</td>
								<td>{file.FILE_DATE}</td>
								<td>
									<a href="admin_database.php?read_file={file.FILE_NAME}&amp;token={TOKEN}" aria-label="{L_DOWNLOAD}"><i class="fa fa-download" aria-hidden="true"></i></a>
									<a href="admin_database.php?action=restore&amp;del={file.FILE_NAME}&amp;token={TOKEN}" aria-label="{L_DELETE}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
								</td>
							</tr>
						# END file #
					</tbody>
				</table>
			# ENDIF #
		</div>
