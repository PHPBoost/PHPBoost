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
				<th>
					{L_ACTIONS}Actions
				</th>
			</tr>
			# START contributions #
			<tr>
				<td class="row1">
					{contributions.ENTITLED}
				</td>
				<td class="row1">
					{contributions.MODULE}
				</td>
				<td class="row1">
					{contributions.STATUS}
				</td>
				<td class="row1">
					{contributions.CREATION_DATE}
				</td>
				<td class="row1">
					{contributions.FIXING_DATE}
				</td>
				<td class="row1">
					{contributions.POSTER}
				</td>
				<td class="row1">
					{contributions.FIXER}
				</td>
				<td class="row1">
					{contributions.ACTIONS}
				</td>
			</tr>	
			# END contributions_list.item #
		</table>
	# ENDIF #

	# START contribution #
		<table class="module_table">
			<tr>
				<th colspan="2">
					{L_CONTRIBUTION}
				</th>
			</tr>
			# IF C_ERROR_HANDLER #
			<tr>
				<td colspan="2">
					<span id="errorh"></span>
					<div class="{ERRORH_CLASS}">
						<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					</div>
				</td>
			</tr>
			# ENDIF #
			# START contribution.properties #
			<tr>
				<td class="row3">
					{L_TITLE}
				</td>
				<td class="row1">
					{contribution.properties.TITLE}
				</td>
			</tr>
			<tr>
				<td class="row3">
					{L_MODULE}
				</td>
				<td class="row1">
					{contribution.properties.MODULE} - <a href="{contribution.properties.URL}">{L_VIEW_CONTRIBUTION}</a>
				</td>
			</tr>
			<tr>
				<td class="row3">
					{L_NOTIFICATIONS}
				</td>
				<td class="row1">
					{contribution.properties.NOTIFICATIONS}
				</td>
			</tr>
			<tr>
				<td class="row3">
					{L_STATUS}
				</td>
				<td class="row1">
					{contribution.properties.STATUS}
					<br />
					{contribution.properties.TREAT}
					<br />{L_EXPLAIN_TREAT}
				</td>
			</tr>
			# END contribution.properties #
			<tr>
				<td class="row2" colspan="2">
					&nbsp;
				</td>
			</tr>
		</table>
		
		<br /><br /><br />
	# END contribution #