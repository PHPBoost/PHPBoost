<table>
	<tbody>
		<tr>
			<td class="no-separator">
				# IF C_REFERER #<a href="{FULL_URL}">{RELATIVE_URL}</a># ELSE #{FULL_URL}# ENDIF #
			</td>
			<td class="no-separator" style="width:70px;">
				{TOTAL_VISIT}
			</td>
			<td class="no-separator" style="width:60px;">
				{AVERAGE}
			</td>
			<td class="no-separator" style="width:96px;">
				{LAST_UPDATE}
			</td>
			<td class="no-separator" style="width:95px;">
				# IF C_PICTURE #<i class="fa fa-trend-{PICTURE}"></i> # ENDIF #({SIGN}{TREND}%)
			</td>
		</tr>
	</tbody>
</table>
