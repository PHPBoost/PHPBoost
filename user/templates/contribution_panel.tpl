	# IF C_CONTRIBUTION_LIST #
	<section id="module-user-contribution-list">
		<header>
			<h1>{L_CONTRIBUTION_PANEL}</h1>
		</header>
		<div class="content">
			{L_CONTRIBUTION_LIST}

			# IF C_NO_CONTRIBUTION #
				<div class="message-helper bgc success">{L_NO_CONTRIBUTION_TO_DISPLAY}</div>
			# ELSE #
				<table class="table">
					<thead>
						<tr>
							<th>
								# IF NOT C_ORDER_ENTITLED_ASC #
									<a href="{U_ORDER_ENTITLED_ASC}" class="fa fa-arrow-up"></a>
								# ENDIF #
								{L_ENTITLED}
								# IF NOT C_ORDER_ENTITLED_DESC #
									<a href="{U_ORDER_ENTITLED_DESC}" class="fa fa-arrow-down"></a>
								# ENDIF #
							</th>
							<th>
								# IF NOT C_ORDER_MODULE_ASC #
									<a href="{U_ORDER_MODULE_ASC}" class="fa fa-arrow-up"></a>
								# ENDIF #
								{L_MODULE}
								# IF NOT C_ORDER_MODULE_DESC #
									<a href="{U_ORDER_MODULE_DESC}" class="fa fa-arrow-down"></a>
								# ENDIF #
							</th>
							<th>
								# IF NOT C_ORDER_STATUS_ASC #
									<a href="{U_ORDER_STATUS_ASC}" class="fa fa-arrow-up"></a>
								# ENDIF #
								{L_STATUS}
								# IF NOT C_ORDER_STATUS_DESC #
									<a href="{U_ORDER_STATUS_DESC}" class="fa fa-arrow-down"></a>
								# ENDIF #
							</th>
							<th>
								# IF NOT C_ORDER_CREATION_DATE_ASC #
									<a href="{U_ORDER_CREATION_DATE_ASC}" class="fa fa-arrow-up"></a>
								# ENDIF #
								{L_CREATION_DATE}
								# IF NOT C_ORDER_CREATION_DATE_DESC #
									<a href="{U_ORDER_CREATION_DATE_DESC}" class="fa fa-arrow-down"></a>
								# ENDIF #
							</th>
							<th>
								# IF NOT C_ORDER_FIXING_DATE_ASC #
									<a href="{U_ORDER_FIXING_DATE_ASC}" class="fa fa-arrow-up"></a>
								# ENDIF #
								{L_FIXING_DATE}
								# IF NOT C_ORDER_FIXING_DATE_DESC #
									<a href="{U_ORDER_FIXING_DATE_DESC}" class="fa fa-arrow-down"></a>
								# ENDIF #
							</th>
							<th>
								# IF NOT C_ORDER_POSTER_ASC #
									<a href="{U_ORDER_POSTER_ASC}" class="fa fa-arrow-up"></a>
								# ENDIF #
								{L_POSTER}
								# IF NOT C_ORDER_POSTER_DESC #
									<a href="{U_ORDER_POSTER_DESC}" class="fa fa-arrow-down"></a>
								# ENDIF #
							</th>
							<th>
								# IF NOT C_ORDER_FIXER_ASC #
									<a href="{U_ORDER_FIXER_ASC}" class="fa fa-arrow-up"></a>
								# ENDIF #
								{L_FIXER}
								# IF NOT C_ORDER_FIXER_DESC #
									<a href="{U_ORDER_FIXER_DESC}" class="fa fa-arrow-down"></a>
								# ENDIF #
							</th>
						</tr>
					</thead>
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
								<td class="bgc success">
									{contributions.STATUS}
								</td>
							# ELSE #
								# IF contributions.C_PROCESSING #
									<td class="bgc question">
										{contributions.STATUS}
									</td>
								# ELSE #
									<td class="bgc error">
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
					# IF C_PAGINATION #
					<tfoot>
						<tr>
							<td colspan="7">
								# INCLUDE PAGINATION #
							</td>
						</tr>
					</tfoot>
					# ENDIF #
				</table>
			# ENDIF #

				<hr>

				<h2>{L_CONTRIBUTE}</h2>

				# IF NOT C_NO_MODULE_IN_WHICH_CONTRIBUTE #
					<p>{L_CONTRIBUTE_EXPLAIN}</p>

					# START row #
						# START row.module #
							<div class="contribution-module-container" style="width:{row.module.WIDTH}%;">
								<a class="infos-options" href="{row.module.U_MODULE_LINK}">
									<img src="{PATH_TO_ROOT}/{row.module.MODULE_ID}/{row.module.MODULE_ID}.png" alt="{row.module.LINK_TITLE}" />
								</a>
								<a href="{row.module.U_MODULE_LINK}">{row.module.MODULE_NAME}</a>
							</div>
						# END row.module #
						<div class="spacer"></div>
					# END row #
				# ELSE #
					<div class="message-helper bgc warning">{L_NO_MODULE_IN_WHICH_CONTRIBUTE}</div>
				# ENDIF #
			</div>
		<footer></footer>
	</section>
	# ENDIF #

	# IF C_CONSULT_CONTRIBUTION #
	<section id="module-user-consult-contribution">
		<header>
			<h1>
			{ENTITLED}
			</h1>
			# IF C_WRITE_AUTH #
				<div class="align-right">
					<a href="{U_UPDATE}" aria-label="{L_UPDATE}"><i class="fa fa-edit" aria-hidden="true"></i></a>
					<a href="{U_DELETE}" aria-label="{L_DELETE}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
				</div>
			# ENDIF #
		</header>
		<div class="content">
			# IF C_WRITE_AUTH #
				# IF C_UNPROCESSED_CONTRIBUTION #
				<div class="unprocessed-contribution">
					<div>
						<a href="{FIXING_URL}"><i class="fa fa-wrench fa-2x"aria-hidden="true"></i> {L_PROCESS_CONTRIBUTION}</a>
					</div>
					<div>
						<a href="{U_UPDATE}"><i class="fa fa-check fa-2x success"aria-hidden="true"></i> {L_UPDATE} {L_STATUS}</a>
					</div>
					<div class="spacer"></div>
				</div>
				# ENDIF #
			# ENDIF #

			<fieldset>
				<legend>{L_CONTRIBUTION}</legend>
				<div class="form-element">
					<label>{L_ENTITLED}</label>
					<div class="form-field">
						{ENTITLED}
					</div>
				</div>
				<div class="form-element">
					<label>{L_DESCRIPTION}</label>
					<div class="form-field">{DESCRIPTION}</div>
				</div>
				<div class="form-element">
					<label>{L_STATUS}</label>
					<div class="form-field">{STATUS}</div>
				</div>
				<div class="form-element">
					<label>{L_CONTRIBUTOR}</label>
					<div class="form-field"><a href="{U_CONTRIBUTOR_PROFILE}" class="{CONTRIBUTOR_LEVEL_CLASS}" # IF C_CONTRIBUTOR_GROUP_COLOR # style="color:{CONTRIBUTOR_GROUP_COLOR}" # ENDIF #>{CONTRIBUTOR}</a></div>
				</div>
				<div class="form-element">
					<label>{L_CREATION_DATE}</label>
					<div class="form-field">{CREATION_DATE}</div>
				</div>
				# IF C_CONTRIBUTION_FIXED #
				<div class="form-element">
					<label>{L_FIXER}</label>
					<div class="form-field"><a href="{U_FIXER_PROFILE}" class="{FIXER_LEVEL_CLASS}" # IF C_FIXER_GROUP_COLOR # style="color:{FIXER_GROUP_COLOR}" # ENDIF #>{FIXER}</a></div>
				</div>
				<div class="form-element">
					<label>{L_FIXING_DATE}</label>
					<div class="form-field">{FIXING_DATE}</div>
				</div>
				# ENDIF #
				<div class="form-element">
					<label>{L_MODULE}</label>
					<div class="form-field">{MODULE}</div>
				</div>
			</fieldset>

			{COMMENTS}
		</div>
		<footer></footer>
	</section>
	# ENDIF #

	# IF C_EDIT_CONTRIBUTION #
	<section id="module-user-edit-contribution">
		<header>
			<h1>{ENTITLED}</h1>
		</header>
		<div class="content">
			<form action="contribution_panel.php" method="post">
				<fieldset>
					<legend>{L_CONTRIBUTION}</legend>
					<div class="form-element">
						<label for="entitled">{L_ENTITLED}</label>
						<div class="form-field">
							<input type="text" name="entitled" id="entitled" value="{ENTITLED}">
						</div>
					</div>
					<div class="form-element form-element-textarea">
						<label for="contents">{L_DESCRIPTION}</label>
						{EDITOR}
						<div class="form-field-textarea">
							<textarea rows="15" id="contents" name="contents">{DESCRIPTION}</textarea>
						</div>
					</div>
					<div class="form-element">
						<label for="status">{L_STATUS}</label>
						<div class="form-field"><select name="status" id="status">
								<option value="0"{EVENT_STATUS_UNREAD_SELECTED}>{L_CONTRIBUTION_STATUS_UNREAD}</option>
								<option value="1"{EVENT_STATUS_BEING_PROCESSED_SELECTED}>{L_CONTRIBUTION_STATUS_BEING_PROCESSED}</option>
								<option value="2"{EVENT_STATUS_PROCESSED_SELECTED}>{L_CONTRIBUTION_STATUS_PROCESSED}</option>
							</select>
						</div>
					</div>
				</fieldset>
				<fieldset class="fieldset-submit">
					<input type="hidden" name="idedit" value="{CONTRIBUTION_ID}">
					<input type="hidden" name="token" value="{TOKEN}">
					<button type="submit" class="button submit" value="true">{L_SUBMIT}</button>
					<button type="button" class="button small" name="preview" onclick="XMLHttpRequest_preview();">{L_PREVIEW}</button>
					<button type="reset" class="button reset">{L_RESET}</button>
				</fieldset>
			</form>
		</div>
		<footer></footer>
	</section>
	# ENDIF #
