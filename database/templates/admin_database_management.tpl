<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars" aria-hidden="true"></i> {@database.management}
	</a>
	<ul>
		<li>
			<a href="admin_database.php" class="quick-link">{@database.management}</a>
		</li>
		<li>
			<a href="admin_database.php?query=1" class="quick-link">{@database.sql.queries}</a>
		</li>
		<li>
			<a href="${relative_url(DatabaseUrlBuilder::configuration())}" class="quick-link">{@form.configuration}</a>
		</li>
		<li>
			<a href="${relative_url(DatabaseUrlBuilder::documentation())}" class="quick-link">{@form.documentation}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	# IF C_DATABASE_INDEX #

		# INCLUDE MESSAGE_HELPER #

		<form method="post" action="admin_database.php?action=restore&amp;token={TOKEN}" enctype="multipart/form-data" name="upload_file">
			<fieldset id="database-restore">
				<legend>{@database.management}</legend>
				<div class="fieldset-inset cell-flex cell-columns-2">
					<div class="cell">
						<div class="cell-body">
							<div class="cell-content">{@H|database.management.description}</div>
						</div>
					</div>
					<div class="cell">
						<div class="cell-body">
							<div class="cell-content">
								<div class="message-helper bgc question">{@H|database.management.question}</div>
							</div>
						</div>
					</div>
				</div>
			</fieldset>
			<fieldset>
				<legend>{@database.restore}</legend>
				<div class="fieldset-inset cell-flex cell-columns-2">
					<div class="db-restore cell">
						<div class="cell-body">
							<div class="cell-content">{@database.restore.from.server}</div>
							<p class="align-center">
								<a class="button" href="admin_database.php?action=restore"><i class="fa fa-server" aria-hidden="true"></i> {@H|database.view.file.list}</a>
							</p>
						</div>
					</div>
					<div class="db-restore cell">
						<div class="cell-body">
							<div class="cell-content">
								{L_RESTORE_FROM_UPLOADED_FILE}
							</div>
						</div>
						<div class="cell-body">
							<div class="dnd-area">
								<div class="dnd-dropzone">
									<label for="select-file-to-restore" class="dnd-label">{@upload.drag.and.drop.files} <span class="d-block"></span></label>
									<input type="file" name="upload_file" id="select-file-to-restore" class="ufiles" />
								</div>
								<input type="hidden" name="max_file_size" value="{MAX_FILE_SIZE}">
								<div class="ready-to-load">
									<button type="button" class="button clear-list">{@upload.clear.list}</button>
									<span class="fa-stack fa-lg">
										<i class="far fa-file fa-stack-2x"></i>
										<strong class="fa-stack-1x files-nbr"></strong>
									</span>
								</div>
								<div class="modal-container">
									<button class="button upload-help" data-modal data-target="upload-helper" aria-label="{@upload.upload.helper}"><i class="fa fa-question" aria-hidden="true"></i></button>
									<div id="upload-helper" class="modal modal-animation">
										<div class="close-modal" aria-label="{@common.close}"></div>
										<div class="content-panel">
											<h3>{@upload.upload.helper}</h3>
											<p><strong>{@upload.max.file.size} :</strong> {MAX_FILE_SIZE}</p>
											<p><strong>{@upload.allowed.extensions} :</strong> "{ALLOWED_EXTENSIONS}"</p>
										</div>
									</div>
								</div>
							</div>
							<ul class="ulist"></ul>
						</div>
						<script>
							jQuery('#select-file-to-restore').dndfiles({
								multiple: true,
								maxFileSize: '{MAX_FILE_SIZE}',
								allowedExtensions: ["{ALLOWED_EXTENSIONS}"],
								warningText: ${escapejs(@H|upload.warning.disabled)},
								warningExtension: ${escapejs(@H|upload.warning.extension)},
								warningFileSize: ${escapejs(@H|upload.warning.file.size)},
								warningFilesNbr: ${escapejs(@H|upload.warning.files.number)},
							});
						</script>
						<div class="cell-body">
							<div class="cell-content align-center">
								<button type="submit" id="submit-file-to-restore" name="submit-file-to-restore" value="true" class="button submit">{@database.restore}</button>
								<input type="hidden" name="token" value="{TOKEN}">
							</div>
						</div>
					</div>
				</div>
			</fieldset>
		</form>

		<form action="{TARGET}" method="post">
			<table class="table">
				<caption>{@database.table.list}</caption>
				<thead>
					<tr>
						<th>{@database.select}</th>
						<th>{@database.table.name}</th>
						<th class="td70">{@database.table.rows}</th>
						<th class="td100">{@database.table.engine}</th>
						<th class="td150">{@database.table.collation}</th>
						<th class="td70">{@database.table.data}</th>
						<th class="td70">{@database.table.free}</th>
					</tr>
				</thead>
				<tbody>
					# START table_list #
						<tr>
							<td class="mini-checkbox">
								<div class="form-field-checkbox">
									<label class="checkbox" for="id{table_list.I}">
										<input type="checkbox" id="id{table_list.I}" name="table_{table_list.TABLE_NAME}" />
										<span>&nbsp;</span>
										<span class="sr-only">{@database.select}</span>
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
									<span> {@database.select.all}</span>
								</label>
							</div>
						</td>
						<td class="notice"><strong>{NBR_TABLES}</strong></td>
						<td class="notice"><strong>{NBR_ROWS}</strong></td>
						<td>--</td>
						<td>--</td>
						<td class="notice"><strong>{NBR_DATA}</strong></td>
						<td class="notice"><strong>{NBR_FREE}</strong></td>
					</tr>
				</tbody>
			</table>

			<fieldset class="sub-section">
				<legend>{@database.selected.tables.action}</legend>
				<div class="fieldset-inset">
					<input type="hidden" name="token" value="{TOKEN}">
					<div class="flex-database">
						<button type="submit" name="optimize" value="true" class="button submit big">
							<i class="fa fa-chart-bar fa-fw" aria-hidden="true"></i>
							{@database.optimize}
						</button>
						<button type="submit" name="repair" value="true" class="button submit big">
							<i class="fa fa-cogs fa-fw" aria-hidden="true"></i>
							{@database.repair}
						</button>
						<button type="submit" name="backup" value="true" class="button submit big">
							<i class="fa fa-save fa-fw" aria-hidden="true"></i>
							{@database.backup}
						</button>
					</div>
				</div>
			</fieldset>

			<script>
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
			</script>
		</form>
	# ENDIF #

	# IF C_DATABASE_BACKUP #
		# IF TABLE_NAME #
			<div>
				<nav id="breadcrumb" itemprop="breadcrumb">
					<ol itemscope itemtype="https://schema.org/BreadcrumbList">
						<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
							<a href="admin_database.php#tables" itemprop="item">
								<span itemprop="name">{@database.management}</span>
								<meta itemprop="position" content="1" />
							</a>
						</li>
						<li class="current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
							<a href="admin_database_tools.php?table={TABLE_NAME}&amp;action=structure" itemprop="item">
								<span itemprop="name">{TABLE_NAME}</span>
								<meta itemprop="position" content="2" />
							</a>
						</li>
					</ol>
				</nav>
				<nav id="cssmenu-database-backup" class="cssmenu cssmenu-group">
					<ul>
						<li>
							<a class="cssmenu-title" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=structure">
								<i class="fa fa-fw fa-code-branch" aria-hidden="true"></i> <span>{@database.table.structure}</span>
							</a>
						</li>
						<li>
							<a class="cssmenu-title" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=data">
								<i class="fa fa-fw fa-laptop" aria-hidden="true"></i> <span>{@common.display}</span>
							</a>
						</li>
						<li>
							<a class="cssmenu-title" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=query">
								<i class="fa fa-fw fa-wrench" aria-hidden="true"></i> <span>SQL</span>
							</a>
						</li>
						<li>
							<a class="cssmenu-title" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=insert">
								<i class="fa fa-fw fa-plus" aria-hidden="true"></i> <span>{@database.insert}</span>
							</a>
						</li>
						<li>
							<a class="cssmenu-title" href="admin_database.php?table={TABLE_NAME}&amp;action=backup_table">
								<i class="fa fa-fw fa-save" aria-hidden="true"></i> <span>{@database.backup}</span>
							</a>
						</li>
						<li>
							<a class="cssmenu-title error" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=truncate&amp;token={TOKEN}" data-confirmation="{@database.confirm.empty.table}">
								<i class="fa fa-fw fa-share-square" aria-hidden="true"></i> <span>{@form.empty}</span>
							</a>
						</li>
						<li>
							<a class="cssmenu-title error" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=drop&amp;token={TOKEN}" data-confirmation="delete-element">
								<i class="far fa-fw fa-trash-alt" aria-hidden="true"></i> <span>{@common.delete}</span>
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
				function check_select_all_tables(status)
				{
					for(var i = 0; i < {NBR_TABLES}; i++)
					{
						if( document.getElementById(i) )
							document.getElementById(i).selected = status;
					}
				}
			</script>
			<fieldset id="databse-backup">
				<legend>{@database.backup.database}</legend>
				<div class="fieldset-inset">
					<div class="form-element full-field">{@H|database.backup.description}</div>
					<div class="form-element third-field">
						<label for="table_list[]">{@database.table.list}</label>
						<div class="form-field form-field-select">
							<select id="table_list[]" name="table_list[]" size="8" multiple="multiple">
								# START table_list #
									<option value="{table_list.NAME}" name="table_{table_list.NAME}" id="{table_list.I}" {table_list.SELECTED}>{table_list.NAME}</option>
								# END table_list #
							</select>
						</div>
						<p class="align-center">
							<a href="javascript:check_select_all_tables(true);">{@common.select.all}</a>
							/
							<a href="javascript:check_select_all_tables(false);">{@common.deselect.all}</a>
						</p>
					</div>
					<div class="form-element third-field top-field align-center custom-radio">
						<label for="">{@database.select}</label>
						<div class="form-field form-field-radio-button">
							<div class="form-field-radio">
								<label class="radio" for="backup_all">
									<input type="radio" id="backup_all" name="backup_type" checked="checked" value="all"/>
									<span>{@database.backup.all}</span>
								</label>
							</div>
							<div class="form-field-radio">
								<label class="radio" for="backup_struct">
									<input type="radio" id="backup_struct" name="backup_type" value="struct">
									<span>{@database.backup.structure}</span>
								</label>
							</div>
							<div class="form-field-radio">
								<label class="radio" for="backup_data">
									<input type="radio" id="backup_data" name="backup_type" value="data"/>
									<span>{@database.backup.datas}</span>
								</label>
							</div>
						</div>
					</div>
					<div class="form-element third-field top-field custom-checkbox">
						<label for="compress_file">{@database.compress.file}</label>
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
				<legend>{@database.backup}</legend>
				<div class="fieldset-inset">
					<button type="submit" name="" value="true" class="button submit">{@database.backup}</button>
					<input type="hidden" name="token" value="{TOKEN}">
				</div>
			</fieldset>
		</form>
	# ENDIF #

	# IF C_DATABASE_QUERY #
		<script>
			function check_form(){
				var query = document.getElementById('query').value;
				var query_lowercase = query.toLowerCase();
				var check_query = false;
				var keyword = new Array('delete', 'drop', 'truncate');

				if( query == "" ) {
					alert("{@form.required.fields}");
					return false;
				}

				// Query check => Alert if it's contain keywords DELETE, DROP or TRUNCATE.
				for(i = 0; i < keyword.length; i++)
				{
					if( typeof(strpos(query_lowercase, keyword[i])) != 'boolean' )
					{
						check_query = true;
						break;
					}
				}
				if( check_query )
					return confirm("{@database.confirm.query}\n" + query);

				return true;
			}
		</script>

		<form action="admin_database.php?query=1&amp;token={TOKEN}#executed_query" method="post" onsubmit="return check_form();">
			<div class="message-helper bgc warning">{@H|database.query.description}</div>
			<fieldset id="database-query">
				<legend>{@database.query.execute}</legend>
				<div class="fieldset-inset">
					<span id="errorh"></span>
					<div class="form-element form-element-textarea">
						<label for="query">* {@database.sql.queries}</label>
						<div class="form-field form-field-textarea">
							<textarea rows="12" id="query" name="query">{QUERY}</textarea>
						</div>
					</div>
				</div>
			</fieldset>
			<fieldset class="fieldset-submit">
				<legend>{@database.submit.query}</legend>
				<button type="submit" name="submit" value="true" class="button submit">{@database.submit.query}</button>
				<input type="hidden" name="token" value="{TOKEN}">
			</fieldset>
		</form>

		# IF C_QUERY_RESULT #
			<fieldset id="executed_query">
				<legend>{@database.query.result}</legend>
				<div class="fieldset-inset">
					<div class="responsive-table">
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
											<td>{line.field.FIELD_NAME}</td>
										# END line.field #
									</tr>
								# END line #
							</tbody>
						</table>
					</div>
				</div>
			</fieldset>
		# ENDIF #
	# ENDIF #

	# IF C_DATABASE_FILES #
		# INCLUDE MESSAGE_HELPER #
		<table class="table">
			<caption>{@database.file.list}</caption>
			<thead>
				<tr>
					<th>{@database.file.name}</th>
					<th>{@database.file.weight}</th>
					<th>{@date.date}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="4">
						# IF C_FILES #{@database.restore.file.clue}# ELSE #{@database.empty.directory}# ENDIF #
					</td>
				</tr>
				# START file #
					<tr>
						<td>
							<a href="admin_database.php?action=restore&amp;file={file.FILE_NAME}&amp;token={TOKEN}" aria-label="{@database.restore} {file.FILE_NAME}" data-confirmation="{@database.confirm.restoration}">
								<i class="fa fa-server" aria-hidden="true"></i>
								{file.FILE_NAME}
							</a>
						</td>
						<td>{file.WEIGHT}</td>
						<td>{file.FILE_DATE}</td>
						<td>
							<a href="admin_database.php?read_file={file.FILE_NAME}&amp;token={TOKEN}" aria-label="{@common.download}"><i class="fa fa-fw fa-download" aria-hidden="true"></i></a>
							<a href="admin_database.php?action=restore&amp;del={file.FILE_NAME}&amp;token={TOKEN}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
						</td>
					</tr>
				# END file #
			</tbody>
		</table>
	# ENDIF #
</div>
