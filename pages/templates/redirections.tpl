<section>
	<header>
		<h1>{L_REDIRECTIONS}</h1>
	</header>
	<div class="content">
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

		
		# START rename #
		# INCLUDE message_helper #
		<form action="{TARGET}" method="post" class="fieldset_content">					
			<fieldset>
				<legend>{L_TITLE}</legend>
				<p>{L_EXPLAIN_RENAME}</p>
				<div class="form-element">
					<label for="create_redirection">{L_NEW_TITLE}</label>
					<div class="form-field">
						<label><input type="text" name="new_title" class="text" size="70" maxlength="250"></label>
						<input type="hidden" name="id_rename" value="{ID_RENAME}">
					</div>					
				</div>
				<div class="form-element">
					<label for="create_redirection">{L_CREATE_REDIRECTION}</label>
					<div class="form-field"><label><input type="checkbox" name="create_redirection" id="create_redirection"></label></div>					
				</div>
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
						<input type="text" name="redirection_name" id="redirection_name" class="text" size="60" maxlength="250">
					</div>					
				</div>
				<input type="hidden" name="id_new" value="{ID_NEW}">
			</fieldset>	
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<button type="submit" value="true">{L_SUBMIT}</button>
			</fieldset>
		</form>
		# END new #
	</div>
	<footer></footer>
</section>