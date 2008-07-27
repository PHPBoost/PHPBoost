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
		</fieldset>
	# ENDIF #