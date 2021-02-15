# START auth #
	<section>
		<header class="section-header">
			<h1>{auth.L_TITLE}</h1>
			<div class="align-center"></div>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					<form action="action.php" method="post" class="fieldset-content">
						<fieldset>
							<legend class="sr-only">{auth.L_TITLE}</legend>
							<div class="fieldset-inset">
								{L_EXPLAIN_DEFAULT}
								<button type="submit" name="default" value="true" class="button default">{L_DEFAULT}</button>
								<hr />
								<p class="align-center">
									{EXPLAIN_WIKI_GROUPS}
								</p>
								<div class="form-element form-element-auth">
									<label>{L_RESTORE_ARCHIVE}</label>
									<div class="form-field">{SELECT_RESTORE_ARCHIVE}</div>
								</div>
								<div class="form-element form-element-auth">
									<label>{L_DELETE_ARCHIVE}</label>
									<div class="form-field">{SELECT_DELETE_ARCHIVE}</div>
								</div>
								<div class="form-element form-element-auth">
									<label>{L_EDIT}</label>
									<div class="form-field">{SELECT_EDIT}</div>
								</div>
								<div class="form-element form-element-auth">
									<label>{L_DELETE}</label>
									<div class="form-field">{SELECT_DELETE}</div>
								</div>
								<div class="form-element form-element-auth">
									<label>{L_RENAME}</label>
									<div class="form-field">{SELECT_RENAME}</div>
								</div>
								<div class="form-element form-element-auth">
									<label>{L_REDIRECT}</label>
									<div class="form-field">{SELECT_REDIRECT}</div>
								</div>
								<div class="form-element form-element-auth">
									<label>{L_MOVE}</label>
									<div class="form-field">{SELECT_MOVE}</div>
								</div>
								<div class="form-element form-element-auth">
									<label>{L_STATUS}</label>
									<div class="form-field">{SELECT_STATUS}</div>
								</div>
								<div class="form-element form-element-auth">
									<label>{L_COM}</label>
									<div class="form-field">{SELECT_COM}</div>
								</div>
							</div>
						</fieldset>

						<fieldset class="fieldset-submit">
							<legend>{L_SUBMIT}</legend>
							<button type="submit" name="valid" value="true" class="button submit">{L_UPDATE}</button>
							<button type="reset" class="button reset-button" value="true">{L_RESET}</button>
							<input type="hidden" name="id_auth" value="{auth.ID}">
							<input type="hidden" name="token" value="{TOKEN}">
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		<footer></footer>
	</section>
# END auth #


# START status #
	<script>
		status = new Array();
		# START status.status_array #
			status[{status.status_array.ID}] = "{status.status_array.TEXT}";
		# END status.status_array #

		function show_status()
		{
			if( document.getElementById('radio_undefined').checked )
				change_type(-1);

			// If it's a predefine status
			if( document.getElementById('id_status').value > 0 && status[document.getElementById('id_status').value] != "" )
			{
				document.getElementById('current_status').innerHTML = status[parseInt(document.getElementById('id_status').value)];
			}
			else if( document.getElementById('id_status').value == 0 )
			{
				document.getElementById('current_status').innerHTML = "{status.NO_STATUS}";
			}
		}
		function change_type(id)
		{
			if( id < 0 )
			{
				document.getElementById('current_status').innerHTML = "{L_UNDEFINED_STATUS}";
				document.getElementById('radio_undefined').checked = true;
				document.getElementById('radio_defined').checked = false;
				document.getElementById('contents').disabled = false;
				document.getElementById('contents').style.color = "";
				document.getElementById('id_status').disabled = true;
			}
			else
			{
				show_status();
				document.getElementById('radio_undefined').checked = false;
				document.getElementById('radio_defined').checked = true;
				document.getElementById('contents').disabled = true;
				document.getElementById('contents').style.color = "grey";
				document.getElementById('id_status').disabled = false;
			}
		}
	</script>

	<section>
		<header class="section-header">
			<h1>{status.L_TITLE}</h1>
			<div class="align-center"></div>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					<form action="action.php" method="post" class="fieldset-content">
						<fieldset>
							<legend class="sr-only">{status.L_TITLE}</legend>
							<div class="fieldset-inset">
								<div class="form-element">
									<label for="current_status">{L_CURRENT_STATUS}</label>
									<div id="current_status" class="form-field form-field-free">{status.CURRENT_STATUS}</div>
								</div>
								<div class="form-element">
									<label>{L_STATUS}</label>
									<div class="form-field form-field-radio-button">
										<div class="form-field-radio">
											<label class="radio" for="radio_defined">
												<input type="radio" name="status" id="radio_defined" value="radio_defined" {status.DEFINED} onclick="javascript: change_type(0);" {status.SELECTED_DEFINED}>
												<span>{L_DEFINED_STATUS}</span>
											</label>
											<select id="id_status" name="id_status" {status.SELECTED_SELECT} class="nav" onchange="javascript:show_status();">
											# START status.list #
												<option value="{status.list.ID_STATUS}" {status.list.SELECTED}>{status.list.L_STATUS}</option>
											# END status.list #
											</select>
										</div>
										<div class="form-field-radio">
											<label class="radio" for="radio_undefined">
												<input type="radio" name="status" id="radio_undefined" value="radio_undefined" {status.UNDEFINED} onclick="javascript: change_type(-1);" {status.SELECTED_UNDEFINED}>
												<span>{L_UNDEFINED_STATUS}</span>
											</label>
										</div>
									</div>
								</div>
								<div class="form-element form-element-textarea">
									{KERNEL_EDITOR}
									<div class="form-field form-field-textarea">
										<textarea rows="15" cols="66" id="contents" name="contents" {status.SELECTED_TEXTAREA}>{status.UNDEFINED_STATUS}</textarea>
									</div>
								</div>
							</div>
						</fieldset>
						<fieldset class="fieldset-submit">
							<legend>{L_SUBMIT}</legend>
							<div class="fieldset-inset">
								<input type="hidden" name="id_change_status" value="{status.ID_ARTICLE}">
								<input type="hidden" name="token" value="{TOKEN}">
								<button type="submit" class="button submit" value="true">{L_SUBMIT}</button>
								<button type="button" class="button preview-button" onclick="XMLHttpRequest_preview();jQuery('#xmlhttprequest_result').fadeOut();">{L_PREVIEW}</button>
								<button type="reset" class="button reset-button">{L_RESET}</button>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		<footer></footer>
	</section>
# END status #


# START move #
	<script>
		var path = '{PICTURES_DATA_PATH}';
		var selected_cat = {move.SELECTED_CAT};
	</script>
	<script src="{PICTURES_DATA_PATH}/js/wiki.js"></script>

	<section>
		<header class="section-header">
			<h1>{move.L_TITLE}</h1>
			<div class="align-center"></div>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					# INCLUDE message_helper #
					<form action="action.php" method="post" onsubmit="return check_form_post();" class="fieldset-content">
						<fieldset>
							<legend class="sr-only">{move.L_TITLE}</legend>
								<div class="fieldset-inset">
									<div class="form-element">
										<label>{L_CURRENT_CAT}</label>
										<div class="form-field form-field-free">
											<input type="hidden" name="new_cat" id="id_cat" value="{move.ID_CAT}">
											<div id="selected_cat">{move.CURRENT_CAT}</div>
										</div>
									</div>
									<div class="form-element explorer">
										<label>{L_SELECT_CAT}</label>
										<div class="form-field cats">
											<div class="content no-list">
												<ul>
													<li>
														<a id="class-0" class="{move.CAT_0}" href="javascript:select_cat(0);"><i class="fa fa-folder" aria-hidden="true"></i> {L_DO_NOT_SELECT_ANY_CAT}</a>
														{move.CATS}
													</li>
												</ul>
											</div>
										</div>
									</div>
							</div>
						</fieldset>

						<fieldset class="fieldset-submit">
							<legend>{L_SUBMIT}</legend>
							<div class="fieldset-inset">
								<input type="hidden" name="id_to_move" value="{move.ID_ARTICLE}">
								<input type="hidden" name="token" value="{TOKEN}">
								<button type="submit" value="true" class="button submit">{L_SUBMIT}</button>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		<footer></footer>
	</section>
# END move #

# START rename #
	<script>
		function check_form_post(){
			if(document.getElementById('new_title').value == "") {
				alert("{L_ALERT_TITLE}");
				return false;
			}
			return true;
		}
	</script>
	<section>
		<header class="section-header">
			<h1>{rename.L_TITLE}</h1>
			<div class="align-center"></div>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					# INCLUDE message_helper #
					<form action="action.php" method="post" onsubmit="return check_form_post();" class="fieldset-content">
						<fieldset>
							<legend class="sr-only">{rename.L_TITLE}</legend>
							<div class="fieldset-inset">
								<p class="align-center">
									{rename.L_RENAMING_ARTICLE}
								</p>
								<div class="form-element">
									<label for="new_title">{L_NEW_TITLE}</label>
									<div class="form-field form-field-text"><input type="text" name="new_title" id="new_title" value="{rename.FORMER_NAME}"></div>
								</div>
								<div class="form-element form-element-checkbox">
									<label for="create_redirection_while_renaming">{rename.L_CREATE_REDIRECTION}</label>
									<div class="form-field form-field-checkbox">
										<label class="checkbox">
											<input type="checkbox" name="create_redirection_while_renaming" id="create_redirection_while_renaming" checked="checked">
											<span>&nbsp;</span>
										</label>
									</div>
								</div>
							</div>
						</fieldset>

						<fieldset class="fieldset-submit">
							<legend>{L_SUBMIT}</legend>
							<div class="fieldset-inset">
								<input type="hidden" name="id_to_rename" value="{rename.ID_ARTICLE}">
								<input type="hidden" name="token" value="{TOKEN}">
								<button type="submit" value="true" class="button submit">{L_SUBMIT}</button>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		<footer></footer>
	</section>
# END rename #

# START redirect #
	<section>
		<header class="section-header">
			<h1>{redirect.L_TITLE}</h1>
			<div class="align-center"></div>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					<table class="table">
						<thead>
							<tr>
								<th>
									{L_REDIRECTION_NAME}
								</th>
								<th>
									{L_REDIRECTION_ACTIONS}
								</th>
							</tr>
						</thead>
						<tbody>
							# START redirect.list #
								<tr>
									<td>
										{redirect.list.REDIRECTION_NAME}
									</td>
									<td>
										<a href="{redirect.list.U_REDIRECTION_DELETE}" data-confirmation="{L_ALERT_DELETE_REDIRECTION}" aria-label="{REDIRECTION_DELETE}"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
									</td>
								</tr>
							# END redirect.list #
							# IF NO_REDIRECTION #
								<tr>
									<td colspan="2">
										{L_NO_REDIRECTION}
									</td>
								</tr>
							# ENDIF #
						</tbody>
						<tfoot>
							<tr>
								<td colspan="2">
									<a href="{U_CREATE_REDIRECTION}"><i class="fa fa-fast-forward" aria-hidden="true"></i> {L_CREATE_REDIRECTION}</a>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
		<footer></footer>
	</section>
# END redirect #

# START create #
	<script>
		function check_form_post(){
			if(document.getElementById('title').value == "") {
				alert("{L_ALERT_TITLE}");
				return false;
			}
			return true;
		}
	</script>
	<section>
		<header class="section-header">
			<h1>{create.L_TITLE}</h1>
			<div class="align-center"></div>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					# INCLUDE message_helper #
					<form action="action.php" method="post" onsubmit="return check_form_post();" class="fieldset-content">
						<fieldset>
							<legend class="sr-only">{create.L_TITLE}</legend>
							<div class="fieldset-inset">
								<div class="form-element">
									<label for="redirection_title">{L_REDIRECTION_NAME}</label>
									<div class="form-field"><input type="text" name="redirection_title" id="redirection_title" value=""></div>
								</div>
							</div>
						</fieldset>

						<fieldset class="fieldset-submit">
							<legend>{L_SUBMIT}</legend>
							<input type="hidden" name="create_redirection" value="{create.ID_ARTICLE}">
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="submit" value="true" class="button submit">{L_SUBMIT}</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		<footer></footer>
	</section>
# END create #

# START remove #
	<script>
		var path = '{PICTURES_DATA_PATH}';
		var selected_cat = {remove.SELECTED_CAT};
	</script>
	<script src="{PICTURES_DATA_PATH}/js/wiki.js"></script>
	<section>
		<header class="section-header">
			<h1>{remove.L_TITLE}</h1>
			<div class="align-center"></div>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					# INCLUDE message_helper #
					<form action="action.php" method="post" onsubmit="return confirm('{L_ALERT_REMOVING_CAT}');" class="fieldset-content">
						<fieldset>
							<legend class="sr-only">{remove.L_TITLE}</legend>
							<div class="fieldset-inset">
								<div class="form-element">
									<label for="action">{L_EXPLAIN_REMOVE_CAT}</label>
									<div class="form-field">
										<label class="radio"><input id="action" name="action" value="remove_all" type="radio"><span class="text-strong">{remove.L_REMOVE_ALL_CONTENTS}</span></label>
										<label class="radio"><input name="action" value="move_all" type="radio" checked="checked"><span class="text-strong">{remove.L_MOVE_ALL_CONTENTS}</span></label>
									</div>
								</div>
								<div class="form-element">
									<label for="id_cat">{L_FUTURE_CAT}</label>
									<div class="form-field">
										<input type="hidden" name="report_cat" value="{remove.ID_CAT}" id="id_cat">
										<div id="selected_cat">{remove.CURRENT_CAT}</div>
									</div>
								</div>
								<div class="form-element">
									<label>{L_SELECT_CAT}</label>
									<div class="form-field">
										<span class="futur-cat-pages"><a href="javascript:select_cat(0);"><i class="fa fa-folder" aria-hidden="true"></i> <span id="class-0" class="{remove.CAT_0}">{L_DO_NOT_SELECT_ANY_CAT}</span></a></span>
										<div class="spacer"></div>
										{remove.CATS}
									</div>
								</div>
							</div>
						</fieldset>

						<fieldset class="fieldset-submit">
							<legend>{L_SUBMIT}</legend>
							<div class="fieldset-inset">
								<input type="hidden" name="id_to_remove" value="{remove.ID_ARTICLE}">
								<input type="hidden" name="token" value="{TOKEN}">
								<button type="submit" value="true" class="button submit">{L_SUBMIT}</button>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		<footer></footer>
	</section>
# END remove #

# IF C_COMMENTS #
	<h1>{TITLE}</h1>
	{COMMENTS}
# ENDIF #
