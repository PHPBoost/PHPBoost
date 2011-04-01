		# START auth #
		<form action="action.php?token={TOKEN}" method="post" class="fieldset_content">					
			<fieldset>
				<legend>{auth.L_TITLE}</legend>
				<p style="text-align:center">
					{L_EXPLAIN_DEFAULT}
					<br />
					<input type="submit" name="default" value="{L_DEFAULT}" class="reset" />
				</p>
				<br />
				<hr />
				<br />
				<p style="text-align:center">
					{EXPLAIN_WIKI_GROUPS}
				</p>
				<dl>
					<dt><label>{L_RESTORE_ARCHIVE}</label></dt>
					<dd><label>{SELECT_RESTORE_ARCHIVE}</label></dd>					
				</dl>
				<dl>
					<dt><label>{L_DELETE_ARCHIVE}</label></dt>
					<dd><label>{SELECT_DELETE_ARCHIVE}</label></dd>					
				</dl>
				<dl>
					<dt><label>{L_EDIT}</label></dt>
					<dd><label>{SELECT_EDIT}</label></dd>					
				</dl>
				<dl>
					<dt><label>{L_DELETE}</label></dt>
					<dd><label>{SELECT_DELETE}</label></dd>					
				</dl>
				<dl>
					<dt><label>{L_RENAME}</label></dt>
					<dd><label>{SELECT_RENAME}</label></dd>					
				</dl>
				<dl>
					<dt><label>{L_REDIRECT}</label></dt>
					<dd><label>{SELECT_REDIRECT}</label></dd>					
				</dl>
				<dl>
					<dt><label>{L_MOVE}</label></dt>
					<dd><label>{SELECT_MOVE}</label></dd>					
				</dl>
				<dl>
					<dt><label>{L_STATUS}</label></dt>
					<dd><label>{SELECT_STATUS}</label></dd>					
				</dl>
				<dl>
					<dt><label>{L_COM}</label></dt>
					<dd><label>{SELECT_COM}</label></dd>					
				</dl>
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
				&nbsp;&nbsp; 
				<input type="reset" value="{L_RESET}" class="reset" />
				<input type="hidden" name="id_auth" value="{auth.ID}" />
			</fieldset>
		</form>
		# END auth #

		
		# START status #
		<script type="text/javascript">
		<!--
			status = new Array();
			# START status.status_array #
			status[{status.status_array.ID}] = "{status.status_array.TEXT}";	
			# END status.status_array #
			
			function show_status()
			{
				if( document.getElementById('radio_undefined').checked )
					change_type(-1);
					
				//Si il s'agit d'un statut pr�d�fini
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
		-->
		</script>

		<form action="action.php?token={TOKEN}" method="post" class="fieldset_content">					
			<fieldset>
				<legend>{status.L_TITLE}</legend>				
				<p style="text-align:center" class="text_strong">{L_CURRENT_STATUS}</p>
				<div id="current_status" class="row3">{status.CURRENT_STATUS}</div>
				<br />
				<dl>
					<dt><label>{L_STATUS}</label></dt>
					<dd>
						<label><input type="radio" name="status" id="radio_defined" value="radio_defined" {status.DEFINED} onclick="javascript: change_type(0);" {status.SELECTED_DEFINED} />&nbsp;{L_DEFINED_STATUS}</label>
						<select id="id_status" name="id_status" {status.SELECTED_SELECT} class="nav" onchange="javascript:show_status();">
						# START status.list #
							<option value="{status.list.ID_STATUS}" {status.list.SELECTED}>{status.list.L_STATUS}</option>
						# END status.list #
						</select>
						<br /><br />
						<label><input type="radio" name="status" id="radio_undefined" value="radio_undefined" {status.UNDEFINED} onclick="javascript: change_type(-1);" {status.SELECTED_UNDEFINED} />&nbsp;{L_UNDEFINED_STATUS}</label> 
						
					</dd>					
				</dl>
				<br />
				{KERNEL_EDITOR}
				<label><textarea rows="15" cols="66" id="contents" name="contents" {status.SELECTED_TEXTAREA}>{status.UNDEFINED_STATUS}</textarea></label>
			</fieldset>
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="hidden" name="id_change_status" value="{status.ID_ARTICLE}" />
				<input type="submit" class="submit" value="{L_SUBMIT}" />
				<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();hide_div('xmlhttprequest_result')" class="submit" type="button" />
				<input value="{L_RESET}" class="reset" type="reset">
			</fieldset>
		</form>
		# END status #

		
		# START move #

		<script type="text/javascript">
		<!--
			var path = '{PICTURES_DATA_PATH}';
			var selected_cat = {move.SELECTED_CAT};
		-->
		</script>
		<script type="text/javascript" src="{PICTURES_DATA_PATH}/images/wiki.js"></script>

		# INCLUDE message_helper #
		
		<form action="action.php?token={TOKEN}" method="post" onsubmit="return check_form_post();" class="fieldset_content">					
			<fieldset>
				<legend>{move.L_TITLE}</legend>	
				<dl>
					<dt><label>{L_CURRENT_CAT}</label></dt>
					<dd>
						<input type="hidden" name="new_cat" id="id_cat" value="{move.ID_CAT}" />
						<div id="selected_cat">{move.CURRENT_CAT}</div>
					</dd>					
				</dl>
				<dl>
					<dt><label>{L_SELECT_CAT}</label></dt>
					<dd>
						<span style="padding-left:17px;"><a href="javascript:select_cat(0);"><img src="{PICTURES_DATA_PATH}/images/cat_root.png" alt="" /> <span id="class_0" class="{move.CAT_0}">{L_DO_NOT_SELECT_ANY_CAT}</span></a></span>
						<br />
						{move.CATS}
					</dd>					
				</dl>
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="hidden" name="id_to_move" value="{move.ID_ARTICLE}" />
				<input type="submit" class="submit" value="{L_SUBMIT}" />
			</fieldset>
		</form>
		# END move #

		
		# START rename #
		<script type="text/javascript">
		<!--
			function check_form_post(){
				if(document.getElementById('new_title').value == "") {
					alert("{L_ALERT_TITLE}");
					return false;
				}
				return true;
			}
		-->
		</script>
		# INCLUDE message_helper #
		
		<form action="action.php?token={TOKEN}" method="post" onsubmit="return check_form_post();" class="fieldset_content">					
			<fieldset>
				<legend>{rename.L_TITLE}</legend>				
				<p style="text-align:center;">
					{rename.L_RENAMING_ARTICLE}
				</p>
				<br />
				<dl>
					<dt><label for="new_title">{L_NEW_TITLE}</label></dt>
					<dd><input type="text" name="new_title" id="new_title" class="text" size="70" maxlength="250" value="{rename.FORMER_NAME}" /></dd>					
				</dl>
				<dl>
					<dt><label for="create_redirection_while_renaming">{rename.L_CREATE_REDIRECTION}</label></dt>
					<dd><label><input type="checkbox" name="create_redirection_while_renaming" id="create_redirection_while_renaming" checked="checked" /></label></dd>					
				</dl>
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="hidden" name="id_to_rename" value="{rename.ID_ARTICLE}" />
				<input type="submit" class="submit" value="{L_SUBMIT}" />
			</fieldset>
		</form>
		# END rename #


		# START redirect #
		<table class="module_table">
			<tr>
				<th colspan="2">
					{redirect.L_TITLE}
				</th>
			</tr>
			<tr>
				<td class="row1" style="text-align:center;">
					{L_REDIRECTION_NAME}
				</td>
				<td class="row2" style="text-align:center; width:100px;">
					{L_REDIRECTION_ACTIONS}
				</td>
			</tr>
			# START redirect.list #
			<tr>
				<td class="row1">
					{redirect.list.REDIRECTION_NAME}
				</td>
				<td class="row2" style="text-align:center;">
					<a href="{redirect.list.U_REDIRECTION_DELETE}" title="{REDIRECTION_DELETE}" onclick='javascript:return confirm("{L_ALERT_DELETE_REDIRECTION}");'><img src="{PICTURES_DATA_PATH}/images/delete_article.png" alt="{REDIRECTION_DELETE}" /></a>
				</td>
			</tr>
			# END redirect.list #
			# START redirect.no_redirection #
			<tr>
				<td class="row1" colspan="2" style="text-align:center;">
					{redirect.no_redirection.L_NO_REDIRECTION}
				</td>
			</tr>
			# END redirect.no_redirection #
		</table>
		<br />
		<table class="module_table">
			<tr>
				<td class="row1">
					{L_CREATE_REDIRECTION}
				</td>
				<td class="row2" style="text-align:center;">
					<a href="{U_CREATE_REDIRECTION}" title="{L_CREATE_REDIRECTION}"><img src="{PICTURES_DATA_PATH}/images/create_redirection.png" alt="{L_CREATE_REDIRECTION}" /></a>
				</td>
			</tr>
		</table>
		# END redirect #

		
		# START create #
		<script type="text/javascript">
		<!--
			function check_form_post(){
				if(document.getElementById('title').value == "") {
					alert("{L_ALERT_TITLE}");
					return false;
				}
				return true;
			}
		-->
		</script>
		# INCLUDE message_helper #
		
		<form action="action.php?token={TOKEN}" method="post" onsubmit="return check_form_post();" class="fieldset_content">					
			<fieldset>
				<legend>{create.L_TITLE}</legend>				
				<dl>
					<dt><label for="redirection_title">{L_REDIRECTION_NAME}</label></dt>
					<dd><label><input type="text" name="redirection_title" id="redirection_title" class="text" size="70" maxlength="250" value="" /></label></dd>					
				</dl>
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="hidden" name="create_redirection" value="{create.ID_ARTICLE}" />
				<input type="submit" class="submit" value="{L_SUBMIT}" />
			</fieldset>
		</form>
		# END create #

		# START remove #
		<script type="text/javascript">
		<!--
			var path = '{PICTURES_DATA_PATH}';
			var selected_cat = {remove.SELECTED_CAT};
		-->
		</script>
		<script type="text/javascript" src="{PICTURES_DATA_PATH}/images/wiki.js"></script>

		# INCLUDE message_helper #
				
		<form action="action.php?token={TOKEN}" method="post" onsubmit="return confirm('{L_ALERT_REMOVING_CAT}');" class="fieldset_content">					
			<fieldset>
				<legend>{remove.L_TITLE}</legend>				
				<dl>
					<dt><label for="action">{L_EXPLAIN_REMOVE_CAT}</label></dt>
					<dd>
						<label><input id="action" name="action" value="remove_all" type="radio" /><strong>&nbsp;{remove.L_REMOVE_ALL_CONTENTS}</strong></label>
						<label><input name="action" value="move_all" type="radio" checked="checked" /><strong>&nbsp;{remove.L_MOVE_ALL_CONTENTS}</strong></label>
					</dd>					
				</dl>
				<dl>
					<dt><label>{L_FUTURE_CAT}</label></dt>
					<dd>
						<input type="hidden" name="report_cat" value="{remove.ID_CAT}" id="id_cat" />
						<div id="selected_cat">{remove.CURRENT_CAT}</div>
					</dd>					
				</dl>
				<dl>
					<dt><label>{L_SELECT_CAT}</label></dt>
					<dd>
						<span style="padding-left:17px;"><a href="javascript:select_cat(0);"><img src="{PICTURES_DATA_PATH}/images/cat_root.png" alt="" /> <span id="class_0" class="{remove.CAT_0}">{L_DO_NOT_SELECT_ANY_CAT}</span></a></span>
						<br />
						{remove.CATS}
					</dd>					
				</dl>
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="hidden" name="id_to_remove" value="{remove.ID_ARTICLE}" />
				<input type="submit" class="submit" value="{L_SUBMIT}" />
			</fieldset>
		</form>
		# END remove #

		# IF C_COMMENTS #
		{COMMENTS}
		# ENDIF #
		