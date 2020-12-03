<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars" aria-hidden="true"></i> {@database.management}
	</a>
	<ul>
		<li>
			<a href="admin_database.php" class="quick-link">{@database.tools}</a>
		</li>
		<li>
			<a href="admin_database.php?query=1" class="quick-link">{@database.query.execute}</a>
		</li>
		<li>
			<a href="${relative_url(DatabaseUrlBuilder::configuration())}" class="quick-link">${LangLoader::get_message('configuration', 'admin-common')}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	<div class="database-menu">
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
		<nav id="cssmenu-database-tools" class="cssmenu cssmenu-group">
			<ul>
				<li>
					<a class="cssmenu-title" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=structure">
						<i class="fa fa-fw fa-code-branch" aria-hidden="true"></i> <span>{@database.table.structure}</span>
					</a>
				</li>
				<li>
					<a class="cssmenu-title" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=data">
						<i class="fa fa-fw fa-laptop" aria-hidden="true"></i> <span>${LangLoader::get_message('display', 'common')}</span>
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
						<i class="fa fa-fw fa-share-square" aria-hidden="true"></i> <span>${LangLoader::get_message('empty', 'main')}</span>
					</a>
				</li>
				<li>
					<a class="cssmenu-title error" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=drop&amp;token={TOKEN}" data-confirmation="delete-element">
						<i class="far fa-fw fa-trash-alt" aria-hidden="true"></i> <span>${LangLoader::get_message('delete', 'common')}</span>
					</a>
				</li>
			</ul>
		</nav>
		<script>
			$("#cssmenu-database-tools").menumaker({
				title: "{@database.tools}",
				format: "multitoggle",
				breakpoint: 768
			});
		</script>
	</div>

	# IF C_DATABASE_TABLE_STRUCTURE #
		<section>
			<header>
				<h1>{@database.table.structure}</h1>
			</header>
			<article>
				<table class="table">
					<caption>{TABLE_NAME}</caption>
					<thead>
						<tr>
							<th>{@database.table.field}</th>
							<th>${LangLoader::get_message('type', 'main')}</th>
							<th>{@database.table.attribute}</th>
							<th>{@database.table.null}</th>
							<th>${LangLoader::get_message('default', 'main')}</th>
							<th>{@database.table.extra}</th>
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
					<caption>{@database.table.index}</caption>
					<thead>
						<tr>
							<th>${LangLoader::get_message('name', 'main')}</th>
							<th>${LangLoader::get_message('type', 'main')}</th>
							<th>{@database.table.field}</th>
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
					<caption>${LangLoader::get_message('size', 'main')}</caption>
					<thead>
						<tr>
							<th>{@database.table.data}</th>
							<th>{@database.table.index}</th>
							<th>{@database.table.free}</th>
							<th>${LangLoader::get_message('total', 'main')}</th>
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
										<i class="fa fa-chart-bar" aria-hidden="true"></i> {@database.optimize}
									</a>
							    </td>
							# ENDIF #
						</tr>
					</tbody>
				</table>

				<table class="table">
					<caption>${LangLoader::get_message('stats', 'admin')}</caption>
					<thead>
						<tr>
							<th>{@database.table.rows.format}</th>
							<th>{@database.table.rows}</th>
							<th>{@database.table.engine}</th>
							<th>{@database.table.collation}</th>
							<th>${LangLoader::get_message('size', 'main')}</th>
							# IF C_AUTOINDEX #
								<th>{@database.autoincrement}</th>
							# ENDIF #
							<th>{@database.creation.date}</th>
							<th>${LangLoader::get_message('last_update', 'admin')}</th>
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
			<header><h1>${LangLoader::get_message('display', 'common')}</h1></header>
			<article id="executed_query">
				<header>{@database.executed.query}</header>
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
			function check_form(){
				var query = document.getElementById('query').value;
				var query_lowercase = query.toLowerCase();
				var check_query = false;
				var keyword = new Array('delete', 'drop', 'truncate');

				if(query == "") {
					alert("${LangLoader::get_message('form.explain_required_fields', 'status-messages-common')}");
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
				if(check_query)
					return confirm("{@database.confirm.query}\n" + query);

				return true;
			}
		</script>


		<section>
			<header>
				<h1>SQL</h1>
			</header>
			<article>
				<form action="admin_database_tools.php?table={TABLE_NAME}&action=query&amp;token={TOKEN}#executed_query" method="post" onsubmit="return check_form();">
					<fieldset>
						<legend>{@database.query.execute}</legend>
						<div class="fieldset-inset">
							<div class="content">
								<span id="errorh"></span>
								<div class="message-helper bgc warning">{@H|database.query.description}</div>
								<fieldset>
									<label for="query">* {@database.executed.query}</label>
									<textarea rows="12" id="query" name="query">{QUERY}</textarea>
								</fieldset>
								<fieldset class="fieldset-submit" style="margin:0">
									<button type="submit" name="submit" value="true" class="button submit">{@database.submit.query}</button>
									<input type="hidden" name="token" value="{TOKEN}">
								</fieldset>
							</div>
						</div>
					</fieldset>
				</form>

				# IF C_QUERY_RESULT #
					<fieldset>
						<legend>{@database.query.result}</legend>
						<div id="executed_query" class="fieldset-inset">
							<fieldset class="db-executed-query">
								<p>{QUERY_HIGHLIGHT}</p>
							</fieldset>

							<div class="responsive-table">
								<table class="table">
									<thead>
										<tr>
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
				<h1>{@database.insert}</h1>
			</header>
			<article><form action="admin_database_tools.php?table={TABLE_NAME}&amp;field={FIELD_NAME}&amp;value={FIELD_VALUE}&amp;action={ACTION}&amp;token={TOKEN}#executed_query" method="post" onsubmit="return check_form();">
				<table class="table">
					<thead>
						<tr>
							<th>{@database.table.field}</th>
							<th>${LangLoader::get_message('type', 'main')}</th>
							<th>{@database.table.null}</th>
							<th>{@database.table.value}</th>
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
					<legend>{@database.submit.query}</legend>
					<button type="submit" name="submit" value="true" class="button submit">{@database.submit.query}</button>
					<input type="hidden" name="token" value="{TOKEN}">
				</fieldset>
			</form></article>
			<footer></footer>
		</section>
	# ENDIF #
</div>
