<form action="{U_CLEAR_404_ERRORS}" method="post" class="fieldset_content" onsubmit="javascript:return confirmClear();">
    <fieldset>
        <legend>{EL_CLEAR_404_LIST}</legend>
        <dl>
            <dt><label>{EL_CLEAR_404_LIST}</label><br /><span>{EL_CLEAR_404_LIST_EXPLAIN}</span></dt>
            <dd><label><input type="submit" name="clear" value="{EL_CLEAR_404_LIST}" class="reset" /> </label></dd>
        </dl>
    </fieldset>
</form>            
<br />
<table class="module_table">
	<thead>
		<tr>
            <th>{EL_404_ERROR_REQUESTED_URL}</th>
            <th>{EL_404_ERROR_FROM_URL}</th>
            <th style="width:50px;">{EL_404_ERROR_TIMES}</th>
            <th style="width:50px;">{EL_404_ERROR_DELETE}</th>
		</tr>
	</thead>
	<tbody>
		# START errors #
		<tr>
			<td class="row1"><a href="{errors.REQUESTED_URL}" title="{EL_404_ERROR_REQUESTED_URL}">{errors.REQUESTED_URL}</a></td>
            <td class="row2"><a href="{errors.FROM_URL}" title="{EL_404_ERROR_FROM_URL}">{errors.FROM_URL}</a></td>
            <td class="row2" style="text-align: center;">{errors.TIMES}</td>
            <td class="row2" style="text-align: center;">
                <a href="{errors.U_DELETE}" title="{L_404_ERROR_DELETE}" onclick="javascript:return confirmDelete();">
                    <img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_404_ERROR_DELETE}" />
                </a>
            </td>
		</tr>
		# END errors #
	</tbody>
</table>
<br />
<script type="text/javascript">
<!--
function confirmClear() {
    return confirm({JL_404_ERRORS_CLEAR_CONFIRMATION});
}
function confirmDelete() {
    return confirm({JL_404_ERROR_DELETE_CONFIRMATION});
}
-->
</script>