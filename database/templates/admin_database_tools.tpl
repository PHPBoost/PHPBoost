<nav id="admin-quick-menu">
		<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
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
		</ul>
</nav>

<div id="admin-contents">
	<div class="database-menu">
		<nav id="breadcrumb" itemprop="breadcrumb">
			<ol>
				<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
					<a href="admin_database.php#tables">{L_DATABASE_MANAGEMENT}</a>
				</li>
				<li class="current" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
					<a href="admin_database_tools.php?table={TABLE_NAME}&amp;action=structure">{TABLE_NAME}</a>
				</li>
			</ol>
		</nav>
		<nav id="cssmenu-database-tools" class="cssmenu cssmenu-group">
			<ul>
				<li>
					<a class="cssmenu-title" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=structure">
						<i class="fa fa-fw fa-code-branch" aria-hidden="true"></i> <span>{L_TABLE_STRUCTURE}</span>
					</a>
				</li>
				<li>
					<a class="cssmenu-title" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=data">
						<i class="fa fa-fw fa-laptop" aria-hidden="true"></i> <span>{L_TABLE_DISPLAY}</span>
					</a>
				</li>
				<li>
					<a class="cssmenu-title" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=query">
						<i class="fa fa-fw fa-wrench" aria-hidden="true"></i> <span>SQL</span>
					</a>
				</li>
				<li>
					<a class="cssmenu-title" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=insert">
						<i class="fa fa-fw fa-plus" aria-hidden="true"></i> <span>{L_INSERT}</span>
					</a>
				</li>
				<li>
					<a class="cssmenu-title" href="admin_database.php?table={TABLE_NAME}&amp;action=backup_table">
						<i class="fa fa-fw fa-save" aria-hidden="true"></i> <span>{L_BACKUP}</span>
					</a>
				</li>
				<li>
					<a class="cssmenu-title" style="color:red;" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=truncate&amp;token={TOKEN}" data-confirmation="{L_CONFIRM_TRUNCATE_TABLE}">
						<i class="fa fa-fw fa-share-square" aria-hidden="true"></i> <span>{L_TRUNCATE}</span>
					</a>
				</li>
				<li>
					<a class="cssmenu-title" style="color:red;" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=drop&amp;token={TOKEN}" data-confirmation="delete-element">
						<i class="far fa-fw fa-trash-alt" aria-hidden="true"></i> <span>{L_DELETE}</span>
					</a>
				</li>
			</ul>
		</nav>
		<script type="text/javascript">
			$("#cssmenu-database-tools").menumaker({
				title: "{L_DB_TOOLS}",
				format: "multitoggle",
				breakpoint: 768
			});
		</script>
	</div>

	# IF C_DATABASE_TABLE_STRUCTURE #
		<section>
			<header>
				<h1>{L_TABLE_STRUCTURE}</h1>
			</header>
			<article>
				<table class="table">
					<caption>{TABLE_NAME}</caption>
					<thead>
						<tr class="align-center">
							<th>{L_TABLE_FIELD}</th>
							<th>{L_TABLE_TYPE}</th>
							<th>{L_TABLE_ATTRIBUTE}</th>
							<th>{L_TABLE_NULL}</th>
							<th>{L_TABLE_DEFAULT}</th>
							<th>{L_TABLE_EXTRA}</th>
						</tr>
					</thead>
					<tbody>
						# START field #
							<tr>
								<td>{field.FIELD_NAME}</td>
								<td>{field.FIELD_TYPE}</td>
								<td>{field.FIELD_ATTRIBUTE}</td>
								<td>{field.FIELD_NULL}</td>
								<td>{field.FIELD_DEFAULT}</td>
								<td>{field.FIELD_EXTRA}</td>
							</tr>
						# END field #
					</tbody>
				</table>

				<table class="table">
					<caption>{L_TABLE_INDEX}</caption>
					<thead>
						<tr>
							<th>{L_INDEX_NAME}</th>
							<th>{L_TABLE_TYPE}</th>
							<th>{L_TABLE_FIELD}</th>
						</tr>
					</thead>
					<tbody>
						# START index #
							<tr>
								<td>{index.INDEX_NAME}</td>
								<td>{index.INDEX_TYPE}</td>
								<td>{index.INDEX_FIELDS}</td>
							</tr>
						# END index #
					</tbody>
				</table>

				<table class="table">
					<caption>{L_SIZE}</caption>
					<thead>
						<tr class="align-center">
							<th>{L_TABLE_DATA}</th>
							<th>{L_TABLE_INDEX}</th>
							<th>{L_TABLE_FREE}</th>
							<th>{L_TABLE_TOTAL}</th>
							# IF TABLE_FREE #
								<th></th>
							# ENDIF #
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>{TABLE_DATA}</td>
							<td>{TABLE_INDEX}</td>
							<td>{TABLE_FREE}</td>
							<td>{TABLE_TOTAL_SIZE}</td>
							# IF TABLE_FREE #
								<td>
									<a href="admin_database_tools.php?table={TABLE_NAME}&amp;action=optimize">
										<i class="fa fa-chart-bar" aria-hidden="true"></i> {L_OPTIMIZE}
									</a>
							    </td>
							# ENDIF #
						</tr>
					</tbody>
				</table>

				<table class="table">
					<caption>{L_STATISTICS}</caption>
					<thead>
						<tr>
							<th>{L_TABLE_ROWS_FORMAT}</th>
							<th>{L_TABLE_ROWS}</th>
							<th>{L_TABLE_ENGINE}</th>
							<th>{L_TABLE_COLLATION}</th>
							<th>{L_SIZE}</th>
							# IF C_AUTOINDEX #
								<th>{L_AUTOINCREMENT}</th>
							# ENDIF #
							<th>{L_CREATION_DATE}</th>
							<th>{L_LAST_UPDATE}</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>{TABLE_ROW_FORMAT}</td>
							<td>{TABLE_ROWS}</td>
							<td>{TABLE_ENGINE}</td>
							<td>{TABLE_COLLATION}</td>
							<td>{TABLE_TOTAL_SIZE}</td>
							# IF C_AUTOINDEX #
								<td>{TABLE_AUTOINCREMENT}</td>
							# ENDIF #
							<td>{TABLE_CREATION_DATE}</td>
							<td>{TABLE_LAST_UPDATE}</td>
						</tr>
					</tbody>
				</table>
			</article>
			<footer></footer>
		</section>
	# ENDIF #

	# IF C_DATABASE_TABLE_DATA #
		<section>
			<header><h1>{L_TABLE_DISPLAY}</h1></header>
			<article id="executed_query">
				<header>{L_EXECUTED_QUERY}</header>
				<fieldset class="db-executed-query">
					<p>{QUERY_HIGHLIGHT}</p>
				</fieldset>

				# IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #
				<div class="responsive-table">
					<table class="table large-table">
						<thead>
							<tr>
								<th>&nbsp;</th>
								# START head #
									<th>{head.FIELD_NAME}</th>
								# END head #
							</tr>
						</thead>
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
				<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
			</article>
			<footer></footer>
		</section>
	# ENDIF #

	# IF C_DATABASE_TABLE_QUERY #
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

				//Vérification de la requète => alerte si elle contient un des mots clés DELETE, DROP ou TRUNCATE.
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


		<section>
			<header>
				<h1>SQL</h1>
			</header>
			<article>
				<form action="admin_database_tools.php?table={TABLE_NAME}&action=query&amp;token={TOKEN}#executed_query" method="post" onsubmit="return check_form();">
					<fieldset>
						<legend>{L_QUERY}</legend>
						<div class="fieldset-inset">
							<div class="content">
								<span id="errorh"></span>
								<div class="message-helper bgc warning">{L_EXPLAIN_QUERY}</div>
								<fieldset>
									<label for="query">* {L_EXECUTED_QUERY}</label>
									<textarea rows="12" id="query" name="query">{QUERY}</textarea>
								</fieldset>
								<fieldset class="fieldset-submit" style="margin:0">
									<button type="submit" name="submit" value="true" class="button submit">{L_EXECUTE}</button>
									<input type="hidden" name="token" value="{TOKEN}">
								</fieldset>
							</div>
						</div>
					</fieldset>
				</form>

				# IF C_QUERY_RESULT #
					<fieldset>
						<legend>{L_RESULT}</legend>
						<div id="executed_query" class="fieldset-inset">
							<fieldset class="db-executed-query">
								<p>{QUERY_HIGHLIGHT}</p>
							</fieldset>

							<div class="responsive-table">
								<table class="table">
									<thead>
										<tr class="align-center">
											# START head #
												<th>{head.FIELD_NAME}</th>
											# END head #
										</tr>
									</thead>
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
					</fieldset>
				# ENDIF #
			</article>
			<footer></footer>
		</section>
	# ENDIF #

	# IF C_DATABASE_UPDATE_FORM #
		<section>
			<header>
				<h1>{L_INSERT}</h1>
			</header>
			<article><form action="admin_database_tools.php?table={TABLE_NAME}&amp;field={FIELD_NAME}&amp;value={FIELD_VALUE}&amp;action={ACTION}&amp;token={TOKEN}#executed_query" method="post" onsubmit="return check_form();">
				<table class="table">
					<thead>
						<tr class="align-center">
							<th>{L_FIELD_FIELD}</th>
							<th>{L_FIELD_TYPE}</th>
							<th>{L_FIELD_NULL}</th>
							<th>{L_FIELD_VALUE}</th>
						</tr>
					</thead>
					<tbody>
						# START fields #
							<tr>
								<td>{fields.FIELD_NAME}</td>
								<td>{fields.FIELD_TYPE}</td>
								<td>{fields.FIELD_NULL}</td>
								<td>
									# IF fields.C_FIELD_FORM_EXTEND #
										<textarea rows="6" cols="37" name="{fields.FIELD_NAME}">{fields.FIELD_VALUE}</textarea>
									# ELSE #
										<input type="text" size="30" name="{fields.FIELD_NAME}" value="{fields.FIELD_VALUE}">
									# ENDIF #
								</td>
							</tr>
						# END fields #
					</tbody>
				</table>
				<fieldset class="fieldset-submit">
					<legend>{L_EXECUTE}</legend>
					<button type="submit" name="submit" value="true" class="button submit">{L_EXECUTE}</button>
					<input type="hidden" name="token" value="{TOKEN}">
				</fieldset>
			</form></article>
			<footer></footer>
		</section>
	# ENDIF #
</div>
