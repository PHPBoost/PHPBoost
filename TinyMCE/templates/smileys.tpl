<table>
	<thead>
		<tr> 
			<th colspan="{COLSPAN}">{L_SMILEY}</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			# START smiley #
				{smiley.TR_START}
					<td>
						<a href="javascript:EmotionsDialog.insert('{smiley.URL}', '{smiley.CODE}')">{smiley.IMG}</a>&nbsp;&nbsp;
					</td>
					# START smiley.td #
					{smiley.td.TD}
					# END smiley.td #
				{smiley.TR_END}
			# END smiley #
		</tr>
	</tbody>
</table>