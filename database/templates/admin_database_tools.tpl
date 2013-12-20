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
			<div style="width:95%;margin:auto;">
				<div class="block_contents1" style="padding:5px;padding-bottom:7px;margin-bottom:5px">
					<a class="small" href="admin_database.php#tables">{L_DATABASE_MANAGEMENT}</a> / <a class="small" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=structure">{TABLE_NAME}</a>
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
							<a style="color:red;" href="admin_database_tools.php?table={TABLE_NAME}&amp;action=drop&amp;token={TOKEN}" data-confirmation="delete-element"><i class="icon-delete"></i> {L_DELETE}</a>
						</li>
					</ul>
				</menu>
			</div>
			<div class="spacer">&nbsp;</div>
			
			# IF C_DATABASE_TABLE_STRUCTURE #
			<table>
				<thead>
					<tr class="center">
						<th colspan="6">
							{TABLE_NAME}
						</th>
					</tr>
				</thead>
				<tbody>
					<tr class="center">
						<td>
							<span class="text-strong">{L_TABLE_FIELD}</span>
						</td>
						<td>
							<span class="text-strong">{L_TABLE_TYPE}</span>
						</td>
						<td>
							<span class="text-strong">{L_TABLE_ATTRIBUTE}</span>
						</td>
						<td>
							<span class="text-strong">{L_TABLE_NULL}</span>
						</td>
						<td>
							<span class="text-strong">{L_TABLE_DEFAULT}</span>
						</td>
						<td>
							<span class="text-strong">{L_TABLE_EXTRA}</span>
						</td>
					</tr>
					# START field #
					<tr>
						<td>
							{field.FIELD_NAME}
						</td>
						<td>
							{field.FIELD_TYPE}
						</td>
						<td>
							{field.FIELD_ATTRIBUTE}
						</td>
						<td>
							{field.FIELD_NULL}
						</td>
						<td>
							{field.FIELD_DEFAULT}
						</td>
						<td>
							{field.FIELD_EXTRA}
						</td>
					</tr>
					# END field #
				</tbody>
			</table>
			
			<div style="width:95%;margin:auto;">
				<table style="float:left;width:100px;margin-right:15px">
					<thead>
						<tr class="center">
							<th colspan="3">
								{L_TABLE_INDEX}
							</th>
						</tr>
					</thead>
					<tbody>
						<tr class="center">
							<td style="min-width:80px;">
								<span class="text-strong">{L_INDEX_NAME}</span>
							</td>
							<td>
								<span class="text-strong">{L_TABLE_TYPE}</span>
							</td>
							<td>
								<span class="text-strong">{L_TABLE_FIELD}</span>
							</td>
						</tr>
						# START index #
						<tr>
							<td>
								{index.INDEX_NAME}
							</td>
							<td>
								{index.INDEX_TYPE}
							</td>
							<td>
								{index.INDEX_FIELDS}
							</td>
						</tr>
						# END index #
					</tbody>
				</table>
				
				<table style="float:left;width:170px;margin-right:15px">
					<thead>
						<tr class="center">
							<th colspan="2">
								{L_SIZE}
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="min-width:60px;">
								<span class="text-strong">{L_TABLE_DATA}</span>
							</td>
							<td style="text-align:right;">
								{TABLE_DATA}
							</td>
						</tr>
						<tr>
							<td>
								<span class="text-strong">{L_TABLE_INDEX}</span>
							</td>
							<td style="text-align:right;">
								{TABLE_INDEX}
							</td>
						</tr>
						<tr>
							<td>
								<span class="text-strong">{L_TABLE_FREE}</span>
							</td>
							<td style="text-align:right;">
								{TABLE_FREE}
							</td>
						</tr>
						<tr>
							<td>
								<span class="text-strong">{L_TABLE_TOTAL}</span>
							</td>
							<td style="text-align:right;">
								{TABLE_TOTAL_SIZE}
							</td>
						</tr>
						# IF TABLE_FREE #
						<tr class="center">
							<td colspan="2">
								<img src="./database_mini.png" alt="" class="valign-middle" /> <a href="admin_database_tools.php?table={TABLE_NAME}&amp;action=optimize">{L_OPTIMIZE}</a>
							</td>
						</tr>
						# ENDIF #
					</tbody>
				</table>
				
				<table style="float:left;width:300px;">
					<thead>
						<tr class="center">
							<th colspan="2">
								{L_STATISTICS}
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="width:130px;">
								<span class="text-strong">{L_TABLE_ROWS_FORMAT}</span>
							</td>
							<td style="text-align:right;">
								{TABLE_ROW_FORMAT}
							</td>
						</tr>
						<tr>
							<td>
								<span class="text-strong">{L_TABLE_ROWS}</span>
							</td>
							<td style="text-align:right;">
								{TABLE_ROWS}
							</td>
						</tr>
						<tr>
							<td>
								<span class="text-strong">{L_TABLE_ENGINE}</span>
							</td>
							<td style="text-align:right;">
								{TABLE_ENGINE}
							</td>
						</tr>
						<tr>
							<td>
								<span class="text-strong">{L_TABLE_COLLATION}</span>
							</td>
							<td style="text-align:right;">
								{TABLE_COLLATION}
							</td>
						</tr>
						<tr>
							<td>
								<span class="text-strong">{L_SIZE}</span>
							</td>
							<td style="text-align:right;">
								{TABLE_TOTAL_SIZE}
							</td>
						</tr>
						# IF C_AUTOINDEX #
						<tr>
							<td>
								<span class="text-strong">{L_AUTOINCREMENT}</span>
							</td>
							<td style="text-align:right;">
								{TABLE_AUTOINCREMENT}
							</td>
						</tr>
						# ENDIF #
						<tr>
							<td>
								<span class="text-strong">{L_CREATION_DATE}</span>
							</td>
							<td style="text-align:right;">
								<span class="smaller">{TABLE_CREATION_DATE}</span>
							</td>
						</tr>
						<tr>
							<td>
								<span class="text-strong">{L_LAST_UPDATE}</span>
							</td>
							<td style="text-align:right;">
								<span class="smaller">{TABLE_LAST_UPDATE}</span>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="spacer"></div>
			</div>
			# ENDIF #
			
			
			# IF C_DATABASE_TABLE_DATA #
			<section>
					<header></header>
					<div class="content" id="executed_query">
						<article class="block">
							<header>{L_EXECUTED_QUERY}</header>
							<div class="content">
								<fieldset style="background-color:white;margin:0px">
									<p style="color:black;font-size:10px;">{QUERY_HIGHLIGHT}</p>
								</fieldset>
								
								<div class="spacer">&nbsp;</div>
								# IF PAGINATION #<strong>{L_PAGE}</strong> : {PAGINATION} # ENDIF #
								<div style="width:99%;margin:auto;overflow:auto;padding:0px 2px">
									<table>
										<thead>
											<tr class="center">
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
												<td style="{line.field.STYLE}">
													{line.field.FIELD_NAME}
												</td>
												# END line.field #
											</tr>
											# END line #
										</tbody>
									</table>
								</div>
								<div class="spacer">&nbsp;</div>
								# IF PAGINATION #<strong>{L_PAGE}</strong> : {PAGINATION} # ENDIF #
							<footer></footer>
						</article>
					</div>
					<footer></footer>
				</section>
			# ENDIF #
			
			
			# IF C_DATABASE_TABLE_QUERY #
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
			
			<form action="admin_database_tools.php?table={TABLE_NAME}&action=query&amp;token={TOKEN}#executed_query" method="post" onsubmit="return check_form();">
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
									<i class="icon-warning"></i>
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
										<thead>
											<tr class="center">
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
							<footer></footer>
						</article>
					</div>
					<footer></footer>
				</section>
				# ENDIF #
			# ENDIF #
			
			# IF C_DATABASE_UPDATE_FORM #
			<form action="admin_database_tools.php?table={TABLE_NAME}&amp;field={FIELD_NAME}&amp;value={FIELD_VALUE}&amp;action={ACTION}&amp;token={TOKEN}#executed_query" method="post" onsubmit="return check_form();">
				<table>
					<thead>
						<tr class="center">
							<th>
								{L_FIELD_FIELD}
							</th>
							<th>
								{L_FIELD_TYPE}
							</th>
							<th>
								{L_FIELD_NULL}
							</th>
							<th>
								{L_FIELD_VALUE}
							</th>
						</tr>
					</thead>
					<tbody>
						# START fields #
						<tr>
							<td>
								<span class="text-strong">{fields.FIELD_NAME}</span>
							</td>
							<td>
								<span class="text-strong">{fields.FIELD_TYPE}</span>
							</td>
							<td>
								{fields.FIELD_NULL}
							</td>
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
				<fieldset class="fieldset-submit" style="margin:0">
					<legend>{L_EXECUTE}</legend>
					<button type="submit" name="submit" value="true">{L_EXECUTE}</button>
				</fieldset>
			</form>
			# ENDIF #
		</div>
		