<table class="table-no-header">
	<tbody>
		<tr>
			<td>
				# IF C_REFERER #<a class="offload" href="{FULL_URL}">{RELATIVE_URL}</a># ELSE #{FULL_URL}# ENDIF #
			</td>
			<td class="total-head">
				{TOTAL_VISIT}
			</td>
			<td class="average-head">
				{AVERAGE}
			</td>
			<td class="last-update-head">
				{LAST_UPDATE}
			</td>
			<td class="trend-head">
				# IF C_PICTURE #<i class="fa fa-trend-{PICTURE}" aria-hidden="true"></i> # ENDIF #({SIGN}{TREND}%)
			</td>
		</tr>
	</tbody>
</table>
