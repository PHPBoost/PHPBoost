	# IF C_CONTRIBUTION_LIST #
		<table class="module_table">
			<tr>
				<th>
					{L_ENTITLED}Intitulé
				</th>
				<th>
					{L_MODULE}Module
				</th>
				<th>
					{L_STATUS}Statut
				</th>
				<th>
					{L_CREATION_DATE}Date de création
				</th>
				<th>
					{L_FIXING_DATE}Date de traitement
				</th>
				<th>
					{L_POSTER}Contributeur
				</th>
				<th>
					{L_FIXER}Responsable
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
		# IF C_WRITE_AUTH #
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="editer" />
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="editer" />
		# ENDIF #
		<fieldset>
			<legend>Contribution</legend>
			<dl>
				<dt>
					Intitulé
				</dt>
				<dd>
					{ENTITLED}
				</dd>
			</dl>
			<dl>
				<dt>Description</dt>
				<dd>{DESCRIPTION}</dd>
			</dl>
			<dl>
				<dt>Statut</dt>
				<dd>{STATUS}</dd>
			</dl>
			<dl>
				<dt>Contributeur</dt>
				<dd><a href="{U_CONTRIBUTOR_PROFILE}">{CONTRIBUTER}</a></dd>
			</dl>
			<dl>
				<dt>Date de création</dt>
				<dd>{CREATION_DATE}</dd>
			</dl>
			# IF C_CONTRIBUTION_FIXED #
			<dl>
				<dt>Responsable</dt>
				<dd><a href="{U_FIXER_PROFILE}">{FIXER}</a></dd>
			</dl>
			<dl>
				<dt>Date de clôture</dt>
				<dd>{FIXING_DATE}</dd>
			</dl>
			# ENDIF #
			<dl>
				<dt>Module</dt>
				<dd>{MODULE}</dd>
			</dl>
		</fieldset>
		
		{COMMENTS}
	# ENDIF #