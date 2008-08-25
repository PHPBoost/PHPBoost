	# IF C_CONTRIBUTION_LIST #
		<table class="module_table">
			<tr>
				<th>
					{L_ENTITLED}
				</th>
				<th>
					{L_MODULE}
				</th>
				<th>
					{L_STATUS}
				</th>
				<th>
					{L_CREATION_DATE}
				</th>
				<th>
					{L_FIXING_DATE}
				</th>
				<th>
					{L_POSTER}
				</th>
				<th>
					{L_FIXER}
				</th>
			</tr>
			# START contributions #
			<tr>
				<td class="row1" style="text-align:center;">
					<a href="{contributions.U_CONSULT}">{contributions.ENTITLED}</a>
				</td>
				<td class="row1" style="text-align:center;">
					{contributions.MODULE}
				</td>
				<td class="row1" style="text-align:center;">
					{contributions.STATUS}
				</td>
				<td class="row1" style="text-align:center;">
					{contributions.CREATION_DATE}
				</td>
				<td class="row1" style="text-align:center;">
					# IF contributions.C_FIXED #
					{contributions.FIXING_DATE}
					# ELSE #
					-
					# ENDIF #
				</td>
				<td class="row1" style="text-align:center;">
					<a href="{contributions.U_POSTER_PROFILE}">{contributions.POSTER}</a>
				</td>
				<td class="row1" style="text-align:center;">
					# IF contributions.C_FIXED #
					<a href="{contributions.U_FIXER_PROFILE}">{contributions.FIXER}</a>
					# ELSE #
					-
					# ENDIF #
				</td>
			</tr>	
			# END contributions_list.item #
			<tr>
				<td class="row2" style="text-align:center;" colspan="7">
					# IF C_NO_CONTRIBUTION #
						{L_NO_CONTRIBUTION_TO_DISPLAY}
					# ELSE #
						{PAGINATION}
					# ENDIF #
				</td>
			</td>
		</table>
	# ENDIF #

	# IF C_CONSULT_CONTRIBUTION #
	<div class="module_position">			
		<div class="module_top_l"></div>		
		<div class="module_top_r"></div>
		<div class="module_top">
			<span style="float:left;">
				{ENTITLED}
			</span>
			# IF C_WRITE_AUTH #
			<span style="float:right;">
				<a href="{U_UPDATE}" title="{L_UPDATE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" /></a>
				<a href="{U_DELETE}" onclick="return confirm('{L_CONFIRM_DELETE_CONTRIBUTION}');" title="{L_DELETE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" /></a>
			</span>
			# ENDIF #
		</div>
		<div class="module_contents">
			<fieldset>
				<legend>{L_CONTRIBUTION}</legend>
				<dl>
					<dt>
						{L_ENTITLED}
					</dt>
					<dd>
						{ENTITLED}
					</dd>
				</dl>
				<dl>
					<dt>{L_DESCRIPTION}</dt>
					<dd>{DESCRIPTION}</dd>
				</dl>
				<dl>
					<dt>{L_STATUS}</dt>
					<dd>{STATUS}</dd>
				</dl>
				<dl>
					<dt>{L_CONTRIBUTOR}</dt>
					<dd><a href="{U_CONTRIBUTOR_PROFILE}">{CONTRIBUTER}</a></dd>
				</dl>
				<dl>
					<dt>{L_CREATION_DATE}</dt>
					<dd>{CREATION_DATE}</dd>
				</dl>
				# IF C_CONTRIBUTION_FIXED #
				<dl>
					<dt>{L_FIXER}</dt>
					<dd><a href="{U_FIXER_PROFILE}">{FIXER}</a></dd>
				</dl>
				<dl>
					<dt>{L_FIXING_DATE}</dt>
					<dd>{FIXING_DATE}</dd>
				</dl>
				# ENDIF #
				<dl>
					<dt>{L_MODULE}</dt>
					<dd>{MODULE}</dd>
				</dl>
			</fieldset>
			
			<div style="text-align:center;">
				<a href="{FIXING_URL}" title="{L_PROCESS_CONTRIBUTION}"><img src="../templates/{THEME}/images/process-contribution.png" alt="{L_PROCESS_CONTRIBUTION}" /></a>
				<br />
				<a href="{FIXING_URL}" title="{L_PROCESS_CONTRIBUTION}">{L_PROCESS_CONTRIBUTION}</a>
			</div>
			
			{COMMENTS}
		</div>
		<div class="module_bottom_l"></div>		
		<div class="module_bottom_r"></div>
		<div class="module_bottom"></div>
	</div>
	# ENDIF #
	
	# IF C_EDIT_CONTRIBUTION #
	<div class="module_position">			
		<div class="module_top_l"></div>		
		<div class="module_top_r"></div>
		<div class="module_top">
			{ENTITLED}
		</div>
		<div class="module_contents">
			<form action="{U_TARGET}" method="post">
				<fieldset>
					<legend>{L_CONTRIBUTION}</legend>
					<dl>
						<dt>
							<label for="entitled">{L_ENTITLED}</label>
						</dt>
						<dd>
							<input type="text" name="entitled" id="entitled" value="{ENTITLED}" size="40" />
						</dd>
					</dl>
					<label for="contents">{L_DESCRIPTION}</label>
					{EDITOR}
					<textarea rows="15" cols="66" id="contents" name="contents">{DESCRIPTION}</textarea>
					<br />
					<dl>
						<dt><label for="status">{L_STATUS}</label></dt>
						<dd><select name="status" id="status">
								<option value="0"{CONTRIBUTION_STATUS_UNREAD_SELECTED}>{L_CONTRIBUTION_STATUS_UNREAD}</option>
								<option value="1"{CONTRIBUTION_STATUS_BEING_PROCESSED_SELECTED}>{L_CONTRIBUTION_STATUS_BEING_PROCESSED}</option>
								<option value="2"{CONTRIBUTION_STATUS_PROCESSED_SELECTED}>{L_CONTRIBUTION_STATUS_PROCESSED}</option>
							</select>
						</dd>
					</dl>
				</fieldset>
				<fieldset class="fieldset_submit">
					<input type="hidden" name="idedit" value="{CONTRIBUTION_ID}" />
					<input type="submit" class="submit" value="{L_SUBMIT}" />
					<input type="submit" class="submit" value="{L_PREVIEW}" name="preview" onclick="XMLHttpRequest_preview(this.form);" />
					<input value="{L_RESET}" class="reset" type="reset" />
				</fieldset>
			</form>
		</div>
		<div class="module_bottom_l"></div>		
		<div class="module_bottom_r"></div>
		<div class="module_bottom"></div>
	</div>
	# ENDIF #