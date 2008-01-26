		# START redirections #
		<table class="module_table">
			<tr>
				<th colspan="3">
					{L_REDIRECTIONS}
				</th>
			</tr>
			<tr>
				<td class="row1" style="text-align:center;">	
					{L_REDIRECTION_TITLE}
				</td>
				<td class="row1" style="text-align:center;">		
					{L_REDIRECTION_TARGET}
				</td>
				<td class="row1" style="text-align:center;">		
					{L_ACTIONS}
				</td>
			</tr>
			# START redirections.list #
			<tr>
				<td class="row2">	
					{redirections.list.REDIRECTION_TITLE}
				</td>
				<td class="row2">		
					{redirections.list.REDIRECTION_TARGET}
				</td>
				<td class="row2" style="text-align:center;">		
					{redirections.list.ACTIONS}
				</td>
			</tr>
			# END redirections.list #
			# START redirections.no_redirection #
			<tr>
				<td class="row2" colspan="3" style="text-align:center;">	
					{redirections.no_redirection.MESSAGE}
				</td>
			</tr>
			# END redirections.no_redirection #
		</table>
		# END redirections #

		
		# START redirection #
		<table class="module_table">
			<tr>
				<th colspan="2">
					{L_REDIRECTIONS}
				</th>
			</tr>
			<tr>
				<td class="row1" style="text-align:center;">	
					{L_REDIRECTION_TITLE}
				</td>
				<td class="row1" style="text-align:center;">		
					{L_ACTIONS}
				</td>
			</tr>
			# START redirection.list #
			<tr>
				<td class="row2">	
					{redirection.list.REDIRECTION_TITLE}
				</td>
				<td class="row2" style="text-align:center;">		
					{redirection.list.ACTIONS}
				</td>
			</tr>
			# END redirection.list #
			# START redirection.no_redirection #
			<tr>
				<td class="row2" colspan="2" style="text-align:center;">	
					{redirection.no_redirection.MESSAGE}
				</td>
			</tr>
			# END redirection.no_redirection #
			<tr>
				<td class="row2" style="text-align:center; padding:5px;" colspan="2">
					<a href="{U_CREATE_REDIRECTION}">{L_CREATE_REDIRECTION}</a>
				</td>
			</tr>
		</table>
		# END redirection #

		
		# START rename #
		# IF C_ERROR_HANDLER #
			<div class="{ERRORH_CLASS}">
				<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
			</div>
		# ENDIF #
		<form action="{TARGET}" method="post" class="fieldset_content">					
			<fieldset>
				<legend>{L_TITLE}</legend>
				<p>{L_EXPLAIN_RENAME}</p>
				<dl>
					<dt><label for="create_redirection">{L_NEW_TITLE}</label></dt>
					<dd>
						<label><input type="text" name="new_title" class="text" size="70" maxlength="250" /></label>
						<input type="hidden" name="id_rename" value="{ID_RENAME}" />
					</dd>					
				</dl>
				<dl>
					<dt><label for="create_redirection">{L_CREATE_REDIRECTION}</label></dt>
					<dd><label><input type="checkbox" name="create_redirection" id="create_redirection" /></label></dd>					
				</dl>
			</fieldset>	
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="submit" value="{L_SUBMIT}" class="submit" />
			</fieldset>
		</form>
		# END rename #

		
		# START new #
		# IF C_ERROR_HANDLER #
			<div class="{ERRORH_CLASS}">
				<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
			</div>
		# ENDIF #
		<form action="{TARGET}" method="post" class="fieldset_content">					
			<fieldset>
				<legend>{L_TITLE}</legend>
				<dl>
					<dt><label for="redirection_name">{L_REDIRECTION_NAME}</label></dt>
					<dd>
						<label><input type="text" name="redirection_name" id="redirection_name" class="text" size="70" maxlength="250" /></label>
						<input type="hidden" name="id_new" value="{ID_NEW}" />
					</dd>					
				</dl>
			</fieldset>	
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="submit" value="{L_SUBMIT}" class="submit" />
			</fieldset>
		</form>
		# END new #
