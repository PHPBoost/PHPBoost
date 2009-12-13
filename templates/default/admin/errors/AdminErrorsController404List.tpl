<table class="module_table">
	<thead>
		<tr>
            <th>{EL_404_ERROR_REQUESTED_URL}</th>
            <th>{EL_404_ERROR_FROM_URL}</th>
            <th style="width:50px;">{EL_404_ERROR_TIMES}</th>
		</tr>
	</thead>
	<tbody>
		# START errors #
		<tr>
			<td class="row1"><a href="{errors.REQUESTED_URL}" title="{EL_404_ERROR_REQUESTED_URL}">{errors.REQUESTED_URL}</a></td>
            <td class="row2"><a href="{errors.FROM_URL}" title="{EL_404_ERROR_FROM_URL}">{errors.FROM_URL}</a></td>
            <td class="row2">{errors.TIMES}</td>
		</tr>
		# END errors #
	</tbody>
</table>
<br />
<span><a href="{U_CLEAR_404_ERRORS}" title="{EL_CLEAR_404_LIST}">{EL_CLEAR_404_LIST}</a></span>