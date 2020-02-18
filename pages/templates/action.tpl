# IF redirections #
	{L_REDIRECTIONS}
	# START redirections #
		<table class="table">
			<thead>
				<tr>
					<th>
						{L_REDIRECTION_TITLE}
					</th>
					<th>
						{L_REDIRECTION_TARGET}
					</th>
					<th>
						{L_ACTIONS}
					</th>
				</tr>
			</thead>
			<tbody>
				# START redirections.list #
					<tr>
						<td>
							{redirections.list.REDIRECTION_TITLE}
						</td>
						<td>
							{redirections.list.REDIRECTION_TARGET}
						</td>
						<td>
							{redirections.list.ACTIONS}
						</td>
					</tr>
				# END redirections.list #
				# IF C_NO_REDIRECTION #
					<tr>
						<td colspan="3">
							{L_NO_REDIRECTION}
						</td>
					</tr>
				# ENDIF #
			</tbody>
		</table>
	# END redirections #
# ENDIF #

# IF redirection #
	{L_redirection}
	# START redirection #
	<table class="table">
		<thead>
			<tr>
				<th>
					{L_REDIRECTION_TITLE}
				</th>
				<th>
					{L_ACTIONS}
				</th>
			</tr>
		</thead>
		<tbody>
			# START redirection.list #
				<tr>
					<td>
						{redirection.list.REDIRECTION_TITLE}
					</td>
					<td>
						{redirection.list.ACTIONS}
					</td>
				</tr>
			# END redirection.list #
			# IF C_NO_REDIRECTION #
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
					<a href="{U_CREATE_REDIRECTION}">{L_CREATE_REDIRECTION}</a>
				</td>
			</tr>
		</tfoot>
	</table>
	# END redirection #
# ENDIF #

# START rename #
	# INCLUDE message_helper #
	<form action="action.php" method="post" class="fieldset-content">
		<fieldset>
			<legend><h1>{L_TITLE}</h1></legend>
			<p>{L_EXPLAIN_RENAME}</p>
			<div class="form-element">
				<label for="new_title">{L_NEW_TITLE}</label>
				<div class="form-field">
					<input type="text" id="new_title" name="new_title" value="{FORMER_TITLE}">
				</div>
			</div>
			<div class="form-element">
				<label for="create_redirection">{L_CREATE_REDIRECTION}</label>
				<div class="form-field">
					<label class="checkbox">
						<input type="checkbox" name="create_redirection" id="create_redirection">
						<span>&nbsp;</span>
					</label>
				</div>
			</div>
			<input type="hidden" name="id_rename" value="{ID_RENAME}">
		</fieldset>
		<fieldset class="fieldset-submit">
			<legend>{L_SUBMIT}</legend>
			<input type="hidden" name="token" value="{TOKEN}">
			<button type="submit" name="" value="true" class="button submit">{L_SUBMIT}</button>
		</fieldset>
	</form>
# END rename #

# START new #
	# INCLUDE message_helper #
	<form action="action.php" method="post" class="fieldset-content">
		<fieldset>
			<legend><h1>{L_TITLE}</h1></legend>
			<div class="form-element">
				<label for="redirection_name">{L_REDIRECTION_NAME}</label>
				<div class="form-field">
					<input type="text" name="redirection_name" id="redirection_name">
				</div>
			</div>
			<input type="hidden" name="id_new" value="{ID_NEW}">
		</fieldset>

		<fieldset class="fieldset-submit">
			<legend>{L_SUBMIT}</legend>
			<input type="hidden" name="token" value="{TOKEN}">
			<button type="submit" name="" value="true" class="button submit">{L_SUBMIT}</button>
		</fieldset>
	</form>
# END new #

# START remove #
	<script>
		var path = '{PICTURES_DATA_PATH}';
		var selected_cat = {remove.SELECTED_CAT};
	</script>
	<script src="{PATH_TO_ROOT}/pages/templates/js/pages.js"></script>

	# INCLUDE message_helper #
	<form action="action.php" method="post" onsubmit="return confirm('{L_ALERT_REMOVING_CAT}');" class="fieldset-content">
		<fieldset>
			<legend><h1>{L_TITLE}</h1></legend>
			<div class="form-element">
				<label for="action">{L_TITLE}</label>
				<div class="form-field">
					<label class="radio"><input id="action" name="action" value="remove_all" type="radio"> <span>{L_REMOVE_ALL_CONTENTS}</span></label>
					<label class="radio"><input name="action" value="move_all" type="radio" checked> <span>{L_MOVE_ALL_CONTENTS}</span></label>
				</div>
			</div>
			<div class="form-element">
				<label>{L_FUTURE_CAT}</label>
				<div class="form-field">
					<input type="hidden" name="report_cat" value="{remove.ID_CAT}" id="id_cat">
					<span class="futur-cat-pages"><a href="javascript:select_cat(0);"><img src="{PICTURES_DATA_PATH}/images/cat_root.png" alt="cat-root" /> <span id="class-0" class="{remove.CAT_0}">{L_ROOT}</span></a></span>
					<div class="spacer"></div>
					{remove.CATS}
				</div>
			</div>
		</fieldset>

		<fieldset class="fieldset-submit">
			<legend>{L_SUBMIT}</legend>
			<input type="hidden" name="token" value="{TOKEN}">
			<input type="hidden" name="del_cat" value="{remove.ID_ARTICLE}">
			<button type="submit" value="true" class="button submit">{L_SUBMIT}</button>
		</fieldset>
	</form>
# END remove #
