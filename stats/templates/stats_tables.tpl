<table>
	<tbody>
		<tr>
			<td class="no-separator">
				# IF C_REFERER #<a href="{FULL_URL}">{RELATIVE_URL}</a># ELSE #{FULL_URL}# ENDIF #
			</td>
			<td class="no-separator total-head">
				{TOTAL_VISIT}
			</td>
			<td class="no-separator average-head">
				{AVERAGE}
			</td>
			<td class="no-separator last-update-head">
				{LAST_UPDATE}
			</td>
			<td class="no-separator trend-head">
				# IF C_PICTURE #<i class="fa fa-trend-{PICTURE}"></i> # ENDIF #({SIGN}{TREND}%)
			</td>
		</tr>
	</tbody>
</table>
