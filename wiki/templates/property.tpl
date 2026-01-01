# START auth #
	<section>
		<header class="section-header">
			<h1>{auth.L_PAGE_TITLE}</h1>
			<h3 class="align-center">{auth.TITLE}</h3>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					<form action="action.php" method="post" class="fieldset-content">
						<fieldset>
							<legend class="sr-only">{auth.L_PAGE_TITLE}</legend>
							<div class="fieldset-inset">
								<span class="field-description">{@wiki.default.authorizations.clue}</span>
								<p class="align-center">
									<button type="submit" name="default" value="true" class="button submit">{@wiki.default.authorizations}</button>
								</p>
								<hr />
								<span class="field-description">{@wiki.authorizations.clue}</span>
								<div class="form-element form-element-auth">
									<label>{@wiki.authorizations.restore.archive}</label>
									<div class="form-field">{SELECT_RESTORE_ARCHIVE}</div>
								</div>
								<div class="form-element form-element-auth">
									<label>{@wiki.authorizations.delete.archive}</label>
									<div class="form-field">{SELECT_DELETE_ARCHIVE}</div>
								</div>
								<div class="form-element form-element-auth">
									<label>{@wiki.authorizations.edit}</label>
									<div class="form-field">{SELECT_EDIT}</div>
								</div>
								<div class="form-element form-element-auth">
									<label>{@wiki.authorizations.delete}</label>
									<div class="form-field">{SELECT_DELETE}</div>
								</div>
								<div class="form-element form-element-auth">
									<label>{@wiki.authorizations.rename}</label>
									<div class="form-field">{SELECT_RENAME}</div>
								</div>
								<div class="form-element form-element-auth">
									<label>{@wiki.authorizations.redirect}</label>
									<div class="form-field">{SELECT_REDIRECT}</div>
								</div>
								<div class="form-element form-element-auth">
									<label>{@wiki.authorizations.move}</label>
									<div class="form-field">{SELECT_MOVE}</div>
								</div>
								<div class="form-element form-element-auth">
									<label>{@wiki.authorizations.status}</label>
									<div class="form-field">{SELECT_STATUS}</div>
								</div>
								<div class="form-element form-element-auth">
									<label>{@wiki.authorizations.comment}</label>
									<div class="form-field">{SELECT_COM}</div>
								</div>
							</div>
						</fieldset>

						<fieldset class="fieldset-submit">
							<legend>{@form.submit}</legend>
							<button type="submit" name="valid" value="true" class="button submit">{@form.submit}</button>
							<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
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
			status[{status.status_array.ID}] = "{status.status_array.L_TEXT}";
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
				document.getElementById('current_status').innerHTML = "{@wiki.no.status}";
			}
		}
		function change_type(id)
		{
			if( id < 0 )
			{
				document.getElementById('current_status').innerHTML = "{@wiki.undefined.status}";
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
			<h1>{status.L_PAGE_TITLE}</h1>
			<h3 class="align-center">{status.TITLE}</h3>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					<form action="action.php" method="post" class="fieldset-content">
						<fieldset>
							<legend class="sr-only">{status.L_PAGE_TITLE}</legend>
							<div class="fieldset-inset">
								<div class="form-element">
									<label for="current_status">{@wiki.current.status}</label>
									<div id="current_status" class="form-field form-field-free">{status.L_CURRENT_STATUS}</div>
								</div>
								<div class="form-element">
									<label>{@wiki.authorizations.status}</label>
									<div class="form-field form-field-radio-button">
										<div class="form-field-radio">
											<label class="radio" for="radio_defined">
												<input type="radio" name="status" id="radio_defined" value="radio_defined" {status.DEFINED} onclick="javascript: change_type(0);" {status.SELECTED_DEFINED}>
												<span>{@wiki.defined.status}</span>
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
												<span>{@wiki.undefined.status}</span>
											</label>
										</div>
									</div>
								</div>
								<div class="form-element form-element-textarea">
									<div class="form-field form-field-textarea bbcode-sidebar">
										{KERNEL_EDITOR}
										<textarea rows="15" cols="66" id="contents" name="contents" {status.SELECTED_TEXTAREA}>{status.UNDEFINED_STATUS}</textarea>
									</div>
									<button type="button" class="button preview-button" onclick="XMLHttpRequest_preview();jQuery('#xmlhttprequest_result').fadeOut();">{@form.preview}</button>
								</div>
							</div>
						</fieldset>
						<fieldset class="fieldset-submit">
							<legend>{@form.submit}</legend>
							<div class="fieldset-inset">
								<button type="submit" class="button submit" value="true">{@form.submit}</button>
								<input type="hidden" name="id_change_status" value="{status.ITEM_ID}">
								<input type="hidden" name="token" value="{TOKEN}">
								<button type="reset" class="button reset-button">{@form.reset}</button>
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
		var selected_cat = {move.SELECTED_CAT};
	</script>
	<script src="{PATH_TO_ROOT}/wiki/templates/js/wiki# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>

	<section>
		<header class="section-header">
			<h1>{move.L_PAGE_TITLE}</h1>
			<h3 class="align-center">{move.TITLE}</h3>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					# INCLUDE MESSAGE_HELPER #
					<form action="action.php" method="post" onsubmit="return check_form_post();" class="fieldset-content">
						<fieldset>
							<legend class="sr-only">{move.L_PAGE_TITLE}</legend>
							<div class="fieldset-inset">
								<div class="form-element">
									<label>{@wiki.current.category}</label>
									<div class="form-field form-field-free">
										<input type="hidden" name="new_cat" id="id_cat" value="{move.ID_CATEGORY}">
										<div id="selected_cat">{move.CURRENT_CATEGORY}</div>
									</div>
								</div>
								<div class="form-element explorer">
									<label>{@wiki.change.category}</label>
									<div class="form-field cats">
										<div class="content no-list">
											<ul>
												<li>
													<a id="class-0" class="{move.ROOT_CATEGORY}" href="javascript:select_cat(0);"><i class="fa fa-folder" aria-hidden="true"></i> {@wiki.no.category}</a>
													{move.CATEGORIES_LIST}
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</fieldset>

						<fieldset class="fieldset-submit">
							<legend>{@form.submit}</legend>
							<div class="fieldset-inset">
								<input type="hidden" name="id_to_move" value="{move.ITEM_ID}">
								<input type="hidden" name="token" value="{TOKEN}">
								<button type="submit" value="true" class="button submit">{@form.submit}</button>
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
				alert("{@warning.title}");
				return false;
			}
			return true;
		}
	</script>
	<section>
		<header class="section-header">
			<h1>{rename.L_PAGE_TITLE}</h1>
			<h3 class="align-center">{rename.TITLE}</h3>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					# INCLUDE MESSAGE_HELPER #
					<form action="action.php" method="post" onsubmit="return check_form_post();" class="fieldset-content">
						<fieldset>
							<legend class="sr-only">{rename.L_PAGE_TITLE}</legend>
							<div class="fieldset-inset">
								<p class="message-helper bgc notice">{@wiki.renaming.clue}</p>
								<div class="form-element">
									<label for="new_title">{@wiki.renaming.new.title}</label>
									<div class="form-field form-field-text"><input type="text" name="new_title" id="new_title" value="{rename.FORMER_NAME}"></div>
								</div>
								<div class="form-element form-element-checkbox">
									<label for="create_redirection_while_renaming">{@wiki.renaming.redirection}</label>
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
							<legend>{@form.submit}</legend>
							<div class="fieldset-inset">
								<input type="hidden" name="id_to_rename" value="{rename.ITEM_ID}">
								<input type="hidden" name="token" value="{TOKEN}">
								<button type="submit" value="true" class="button submit">{@form.submit}</button>
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
			<h1>{redirect.L_PAGE_TITLE}</h1>
			<h3 class="align-center">{redirect.TITLE}</h3>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					<table class="table">
						<thead>
							<tr>
								<th>
									{@wiki.redirection.name}
								</th>
								<th class="col-large">
									{@wiki.actions}
								</th>
							</tr>
						</thead>
						<tbody>
							# IF C_REDIRECTIONS #
								# START redirect.list #
									<tr>
										<td>
											{redirect.list.REDIRECTION_NAME}
										</td>
										<td>
											<a href="{redirect.list.U_REDIRECTION_DELETE}" data-confirmation="{@wiki.alert.delete.redirection}" aria-label="{@wiki.redirection.delete}"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
										</td>
									</tr>
								# END redirect.list #
							# ELSE #
								<tr>
									<td colspan="2">
										{@wiki.no.redirection}
									</td>
								</tr>
							# ENDIF #
						</tbody>
					</table>
					<p class="align-center">
						<a href="{U_CREATE_REDIRECTION}" class="button submit offload"><i class="fa fa-fast-forward" aria-hidden="true"></i> {@wiki.create.redirection}</a>
					</p>
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
				alert("{@warning.title}");
				return false;
			}
			return true;
		}
	</script>
	<section>
		<header class="section-header">
			<h1>{create.L_PAGE_TITLE}</h1>
			<h3 class="align-center">{create.TITLE}</h3>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					# INCLUDE MESSAGE_HELPER #
					<form action="action.php" method="post" onsubmit="return check_form_post();" class="fieldset-content">
						<fieldset>
							<legend class="sr-only">{create.L_PAGE_TITLE}</legend>
							<div class="fieldset-inset">
								<div class="form-element">
									<label for="redirection_title">{@wiki.redirection.name}</label>
									<div class="form-field"><input type="text" name="redirection_title" id="redirection_title" value=""></div>
								</div>
							</div>
						</fieldset>

						<fieldset class="fieldset-submit">
							<legend>{@form.submit}</legend>
							<input type="hidden" name="create_redirection" value="{create.ITEM_ID}">
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="submit" value="true" class="button submit">{@form.submit}</button>
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
		var selected_cat = {remove.SELECTED_CAT};
	</script>
	<script src="{PATH_TO_ROOT}/wiki/templates/js/wiki# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
	<section>
		<header class="section-header">
			<h1>{remove.L_PAGE_TITLE}</h1>
			<h3 class="align-center">{remove.TITLE}</h3>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					# INCLUDE MESSAGE_HELPER #
					<form action="action.php" method="post" onsubmit="return confirm('{L_ALERT_REMOVING_CAT}');" class="fieldset-content">
						<fieldset>
							<legend class="sr-only">{remove.L_PAGE_TITLE}</legend>
							<div class="fieldset-inset">
								<span class="field-description">{@wiki.remove.category.clue}</span>
								<div class="form-element">
									<label for="action">{@wiki.remove.category.choice}</label>
									<div class="form-field form-field-radio-button">
										<div class="form-field-radio">
											<label class="radio"><input id="action" name="action" value="remove_all" type="radio">
												<span>{@wiki.remove.all.contents}</span>
											</label>
										</div>
										<div class="form-field-radio">
											<label class="radio"><input name="action" value="move_all" type="radio" checked="checked">
												<span>{@wiki.move.all.contents}</span>
											</label>
										</div>
									</div>
								</div>
								<div class="form-element">
									<label for="id_cat">{@wiki.selected.category}</label>
									<div class="form-field">
										<input type="hidden" name="report_cat" value="{remove.ID_CATEGORY}" id="id_cat">
										<div id="selected_cat">{remove.CURRENT_CAT}</div>
									</div>
								</div>
								<div class="form-element">
									<label>{@wiki.select.category}</label>
									<div class="form-field cats">
										<div class="content no-list">
											<ul>
												<li>
													<a href="javascript:select_cat(0);"><i class="fa fa-folder" aria-hidden="true"></i> <span id="class-0" class="{remove.ROOT_CATEGORY}">{@wiki.no.category}</span></a>
													{remove.CATEGORIES_LIST}
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</fieldset>

						<fieldset class="fieldset-submit">
							<legend>{@form.submit}</legend>
							<div class="fieldset-inset">
								<input type="hidden" name="id_to_remove" value="{remove.ITEM_ID}">
								<input type="hidden" name="token" value="{TOKEN}">
								<button type="submit" value="true" class="button submit">{@form.submit}</button>
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
	<section>
		<header class="section-header">
			<h1>{@wiki.comments.management}</h1>
			<h3 class="align-center">{TITLE}</h3>
		</header>
		<div class="sub-section">
			<div class="content-container">
				{COMMENTS}
			</div>
		</div>
		<footer></footer>
	</section>
# ENDIF #
