# IF redirections #
	{L_REDIRECTIONS}
	# START redirections #
	<table>
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
			# START redirections.no_redirection #
			<tr>
				<td colspan="3">	
					{redirections.no_redirection.MESSAGE}
				</td>
			</tr>
			# END redirections.no_redirection #
		</tbody>
	</table>
	# END redirections #
# ENDIF #

# IF redirection #
	{L_redirection}
	# START redirection #
	<table>
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
		<tfoot>
			<tr>
				<th colspan="2">
					<a href="{U_CREATE_REDIRECTION}">{L_CREATE_REDIRECTION}</a>
				</th>
			</tr>
		</tfoot>
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
			# START redirection.no_redirection #
			<tr>
				<td colspan="2">	
					{redirection.no_redirection.MESSAGE}
				</td>
			</tr>
			# END redirection.no_redirection #
		</tbody>
	</table>
	# END redirection #
# ENDIF #

# START rename #
# INCLUDE message_helper #
<form action="{TARGET}" method="post" class="fieldset_content">				
	<fieldset>
		<legend>{L_TITLE}</legend>
		<p>{L_EXPLAIN_RENAME}</p>
		<div class="form-element">
			<label for="new_title">{L_NEW_TITLE}</label>
			<div class="form-field">
				<input type="text" id="new_title" name="new_title" value="{FORMER_TITLE}" size="60" maxlength="250">
			</div>					
		</div>
		<div class="form-element">
			<label for="create_redirection">{L_CREATE_REDIRECTION}</label>
			<div class="form-field"><label><input type="checkbox" name="create_redirection" id="create_redirection"></label></div>					
		</div>
		<input type="hidden" name="id_rename" value="{ID_RENAME}">
	</fieldset>
	<fieldset class="fieldset_submit">
		<legend>{L_SUBMIT}</legend>
		<button type="submit" name="" value="true">{L_SUBMIT}</button>
	</fieldset>
</form>
# END rename #


# START new #
# INCLUDE message_helper #
<form action="{TARGET}" method="post" class="fieldset_content">					
	<fieldset>
		<legend>{L_TITLE}</legend>
		<div class="form-element">
			<label for="redirection_name">{L_REDIRECTION_NAME}</label>
			<div class="form-field">
				<input type="text" name="redirection_name" id="redirection_name" size="60" maxlength="250">
			</div>					
		</div>
		<input type="hidden" name="id_new" value="{ID_NEW}">
	</fieldset>	
	
	<fieldset class="fieldset_submit">
		<legend>{L_SUBMIT}</legend>
		<button type="submit" name="" value="true">{L_SUBMIT}</button>
	</fieldset>
</form>
# END new #


# START remove #
<script type="text/javascript">
<!--
	var path = '{PICTURES_DATA_PATH}';
	var selected_cat = {remove.SELECTED_CAT};
-->
</script>
<script type="text/javascript" src="{PICTURES_DATA_PATH}/images/pages.js"></script>

# INCLUDE message_helper #
<form action="action.php?token={TOKEN}" method="post" onsubmit="return confirm('{L_ALERT_REMOVING_CAT}');" class="fieldset_content">					
	<fieldset>
		<legend>{L_TITLE}</legend>
		<div class="form-element">
			<label for="action">{L_TITLE}</label>
			<div class="form-field">
				<label><input id="action" name="action" value="remove_all" type="radio"> {L_REMOVE_ALL_CONTENTS}</label>
				<label><input name="action" value="move_all" type="radio" checked="checked"> {L_MOVE_ALL_CONTENTS}</label>
			</div>					
		</div>
		<div class="form-element">
			<label>{L_FUTURE_CAT}</label>
			<div class="form-field">
				<input type="hidden" name="report_cat" value="{remove.ID_CAT}" id="id_cat">
				<span style="padding-left:17px;"><a href="javascript:select_cat(0);"><img src="{PICTURES_DATA_PATH}/images/cat_root.png" alt="" /> <span id="class_0" class="{remove.CAT_0}">{L_ROOT}</span></a></span>
				<br />
				{remove.CATS}
			</div>					
		</div>
	</fieldset>	
	
	<fieldset class="fieldset_submit">
		<legend>{L_SUBMIT}</legend>
		<input type="hidden" name="del_cat" value="{remove.ID_ARTICLE}">
		<button type="submit" value="true">{L_SUBMIT}</button>
	</fieldset>
</form>
# END remove #