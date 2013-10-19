	# IF C_CONTRIBUTION_LIST #
	<section>			
		<header>
			<h1>{L_CONTRIBUTION_PANEL}</h1>
		</header>
		<div class="content">
			<h1>{L_CONTRIBUTION_LIST}</h1>
			<br />
			# IF C_NO_CONTRIBUTION #
			<div class="warning">{L_NO_CONTRIBUTION_TO_DISPLAY}</div>
			# ELSE #						
			<table>
				<thead>
					<tr>
						<th>
							# IF NOT C_ORDER_ENTITLED_ASC #
								<a href="{U_ORDER_ENTITLED_ASC}" class="pbt-icon-table-sort-up"></a>
							# ENDIF #
							{L_ENTITLED}
							# IF NOT C_ORDER_ENTITLED_DESC #
								<a href="{U_ORDER_ENTITLED_DESC}" class="pbt-icon-table-sort-down"></a>
							# ENDIF #
						</th>
						<th>
							# IF NOT C_ORDER_MODULE_ASC #
								<a href="{U_ORDER_MODULE_ASC}" class="pbt-icon-table-sort-up"></a>
							# ENDIF #
							{L_MODULE}
							# IF NOT C_ORDER_MODULE_DESC #
								<a href="{U_ORDER_MODULE_DESC}" class="pbt-icon-table-sort-down"></a>
							# ENDIF #
						</th>
						<th>
							# IF NOT C_ORDER_STATUS_ASC #
								<a href="{U_ORDER_STATUS_ASC}" class="pbt-icon-table-sort-up"></a>
							# ENDIF #
							{L_STATUS}
							# IF NOT C_ORDER_STATUS_DESC #
								<a href="{U_ORDER_STATUS_DESC}" class="pbt-icon-table-sort-down"></a>
							# ENDIF #
						</th>
						<th>
							# IF NOT C_ORDER_CREATION_DATE_ASC #
								<a href="{U_ORDER_CREATION_DATE_ASC}" class="pbt-icon-table-sort-up"></a>
							# ENDIF #
							{L_CREATION_DATE}
							# IF NOT C_ORDER_CREATION_DATE_DESC #
								<a href="{U_ORDER_CREATION_DATE_DESC}" class="pbt-icon-table-sort-down"></a>
							# ENDIF #
						</th>
						<th>
							# IF NOT C_ORDER_FIXING_DATE_ASC #
								<a href="{U_ORDER_FIXING_DATE_ASC}" class="pbt-icon-table-sort-up"></a>
							# ENDIF #
							{L_FIXING_DATE}
							# IF NOT C_ORDER_FIXING_DATE_DESC #
								<a href="{U_ORDER_FIXING_DATE_DESC}" class="pbt-icon-table-sort-down"></a>
							# ENDIF #
						</th>
						<th>
							# IF NOT C_ORDER_POSTER_ASC #
								<a href="{U_ORDER_POSTER_ASC}" class="pbt-icon-table-sort-up"></a>
							# ENDIF #
							{L_POSTER}
							# IF NOT C_ORDER_POSTER_DESC #
								<a href="{U_ORDER_POSTER_DESC}" class="pbt-icon-table-sort-down"></a>
							# ENDIF #
						</th>
						<th>
							# IF NOT C_ORDER_FIXER_ASC #
								<a href="{U_ORDER_FIXER_ASC}" class="pbt-icon-table-sort-up"></a>
							# ENDIF #
							{L_FIXER}
							# IF NOT C_ORDER_FIXER_DESC #
								<a href="{U_ORDER_FIXER_DESC}" class="pbt-icon-table-sort-down"></a>
							# ENDIF #
						</th>
					</tr>
				</thead>
				# IF PAGINATION #
				<tfoot>
					<tr>
						<th colspan="7">
							{PAGINATION}
						</td>
					</tr>
				</tfoot>
				# ENDIF #
				<tbody>
					# START contributions #
					<tr>
						<td>
							<a href="{contributions.U_CONSULT}">{contributions.ENTITLED}</a>
						</td>
						<td >
							{contributions.MODULE}
						</td>
						# IF contributions.C_FIXED #
							<td style="background-color:#7FFF9C;">
								{contributions.STATUS}
							</td>
						# ELSE #
							# IF contributions.C_PROCESSING #
							<td style="background-color:#FFD86F;">
								{contributions.STATUS}
							</td>
							# ELSE #
							<td style="background-color:#FF796F;">
								{contributions.STATUS}
							</td>
							# ENDIF #
						# ENDIF #
						<td >
							{contributions.CREATION_DATE}
						</td>
						<td >
							# IF contributions.C_FIXED #
							{contributions.FIXING_DATE}
							# ELSE #
							-
							# ENDIF #
						</td>
						<td >
							<a href="{contributions.U_POSTER_PROFILE}" class="{contributions.POSTER_LEVEL_CLASS}" # IF contributions.C_POSTER_GROUP_COLOR # style="color:{contributions.POSTER_GROUP_COLOR}" # ENDIF #>{contributions.POSTER}</a>
						</td>
						<td >
							# IF contributions.C_FIXED #
							<a href="{contributions.U_FIXER_PROFILE}" class="{contributions.FIXER_LEVEL_CLASS}" # IF contributions.C_FIXER_GROUP_COLOR # style="color:{contributions.FIXER_GROUP_COLOR}" # ENDIF #>{contributions.FIXER}</a>
							# ELSE #
							-
							# ENDIF #
						</td>
					</tr>	
					# END contributions #
				</tbody>
			</table>
			# ENDIF #
			
				<hr style="margin:20px 0;" />
				
				<h1>{L_CONTRIBUTE}</h1>
				<br />
				# IF NOT C_NO_MODULE_IN_WHICH_CONTRIBUTE #
					<p>{L_CONTRIBUTE_EXPLAIN}</p>
					
					# START row #
						# START row.module #
							<div style="float:left;width:{row.module.WIDTH}%;text-align:center;margin:20px 0px;">
								<a href="{row.module.U_MODULE_LINK}" title="{row.module.LINK_TITLE}"><img src="{PATH_TO_ROOT}/{row.module.MODULE_ID}/{row.module.MODULE_ID}.png" alt="{row.module.LINK_TITLE}" /></a>
								<br />							
								<a href="{row.module.U_MODULE_LINK}" title="{row.module.LINK_TITLE}">{row.module.MODULE_NAME}</a>
							</div>
						# END row.module #
						<div class="spacer">&nbsp;</div>
					# END row #
				# ELSE #
					<div class="warning">{L_NO_MODULE_IN_WHICH_CONTRIBUTE}</div>
				# ENDIF #
			</div>
		<footer></footer>
	</section>
	# ENDIF #

	# IF C_CONSULT_CONTRIBUTION #
	<section>			
		<header>
			<h1>{ENTITLED}</h1>
			# IF C_WRITE_AUTH #
			<span style="float:right;">
				<a href="{U_UPDATE}" title="{L_UPDATE}" class="pbt-icon-edit"></a>
				<a href="{U_DELETE}" title="{L_DELETE}" class="pbt-icon-delete" data-confirmation="delete-element"></a>
			</span>
			# ENDIF #
		</header>
		<div class="content">
			# IF C_WRITE_AUTH #
				# IF C_UNPROCESSED_CONTRIBUTION #
				<div style="text-align:center;margin:auto;width:300px">
					<div style="float:left;width:50%">
						<a href="{FIXING_URL}" title="{L_PROCESS_CONTRIBUTION}"><img src="../templates/{THEME}/images/process-contribution.png" alt="{L_PROCESS_CONTRIBUTION}" /></a>
						<br />
						<a href="{FIXING_URL}" title="{L_PROCESS_CONTRIBUTION}">{L_PROCESS_CONTRIBUTION}</a>
					</div>
					<div style="float:left;width:50%">
						<a href="{U_UPDATE}" title="{L_UPDATE} {L_STATUS}"><img src="../templates/{THEME}/images/success.png" alt="{L_UPDATE} {L_STATUS}" /></a>
						<br />
						<a href="{U_UPDATE}" title="{L_UPDATE} {L_STATUS}">{L_UPDATE} {L_STATUS}</a>
					</div>
					<div class="spacer"></div>
				</div>
				# ENDIF #
			# ENDIF #
			
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
					<dd><a href="{U_CONTRIBUTOR_PROFILE}" class="{CONTRIBUTOR_LEVEL_CLASS}" # IF C_CONTRIBUTOR_GROUP_COLOR # style="color:{CONTRIBUTOR_GROUP_COLOR}" # ENDIF #>{CONTRIBUTOR}</a></dd>
				</dl>
				<dl>
					<dt>{L_CREATION_DATE}</dt>
					<dd>{CREATION_DATE}</dd>
				</dl>
				# IF C_CONTRIBUTION_FIXED #
				<dl>
					<dt>{L_FIXER}</dt>
					<dd><a href="{U_FIXER_PROFILE}" class="{FIXER_LEVEL_CLASS}" # IF C_FIXER_GROUP_COLOR # style="color:{FIXER_GROUP_COLOR}" # ENDIF #>{FIXER}</a></dd>
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
			
			{COMMENTS}
		</div>
		<footer></footer>
	</section>
	# ENDIF #
	
	# IF C_EDIT_CONTRIBUTION #
	<section>			
		<header>
			<h1>{ENTITLED}</h1>
		</header>
		<div class="content">
			<form action="{U_TARGET}" method="post">
				<fieldset>
					<legend>{L_CONTRIBUTION}</legend>
					<dl>
						<dt>
							<label for="entitled">{L_ENTITLED}</label>
						</dt>
						<dd>
							<input type="text" name="entitled" id="entitled" value="{ENTITLED}" size="40">
						</dd>
					</dl>
					<label for="contents">{L_DESCRIPTION}</label>
					{EDITOR}
					<textarea rows="15" cols="66" id="contents" name="contents">{DESCRIPTION}</textarea>
					<br />
					<dl>
						<dt><label for="status">{L_STATUS}</label></dt>
						<dd><select name="status" id="status">
								<option value="0"{EVENT_STATUS_UNREAD_SELECTED}>{L_CONTRIBUTION_STATUS_UNREAD}</option>
								<option value="1"{EVENT_STATUS_BEING_PROCESSED_SELECTED}>{L_CONTRIBUTION_STATUS_BEING_PROCESSED}</option>
								<option value="2"{EVENT_STATUS_PROCESSED_SELECTED}>{L_CONTRIBUTION_STATUS_PROCESSED}</option>
							</select>
						</dd>
					</dl>
				</fieldset>
				<fieldset class="fieldset_submit">
					<input type="hidden" name="idedit" value="{CONTRIBUTION_ID}">
					<input type="submit" class="submit" value="{L_SUBMIT}">
					<input type="button" class="submit" value="{L_PREVIEW}" name="preview" onclick="XMLHttpRequest_preview();">
					<input value="{L_RESET}" class="reset" type="reset">
				</fieldset>
			</form>
		</div>
		<footer></footer>
	</section>
	# ENDIF #