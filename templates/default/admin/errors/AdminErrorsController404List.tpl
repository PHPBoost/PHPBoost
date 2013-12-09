<form action="{U_CLEAR_404_ERRORS}" method="post" class="fieldset-content" onsubmit="javascript:return confirmClear();">
    <fieldset>
        <legend>{@clear_404_list}</legend>
        <div class="form-element">
            <label>{@clear_404_list}</label><br /><span>{@clear_404_list_explain}</span>
            <div class="form-field"><label><button type="submit" name="clear" value="true">{@clear_404_list}</button> </label></div>
        </div>
    </fieldset>
	<input type="hidden" name="token" value="{TOKEN}">
</form>            
<br />
<table class="module-table">
	<thead>
		<tr>
            <th>{@404_error_requested_url}</th>
            <th>{@404_error_from_url}</th>
            <th style="width:50px;">{@404_error_times}</th>
            <th style="width:50px;">{@404_error_delete}</th>
		</tr>
	</thead>
	<tbody>
		# START errors #
		<tr>
			<td class="row1"><a href="{errors.REQUESTED_URL}" title="{@404_error_requested_url}">{errors.REQUESTED_URL}</a></td>
            <td class="row2"><a href="{errors.FROM_URL}" title="{@404_error_from_url}">{errors.FROM_URL}</a></td>
            <td class="row2" style="text-align: center;">{errors.TIMES}</td>
            <td class="row2" style="text-align: center;">
                <a href="{errors.U_DELETE}" title="{@404_error_delete}" class="icon-delete" data-confirmation="delete-element"></a>
            </td>
		</tr>
		# END errors #
	</tbody>
</table>
<br />
<script type="text/javascript">
<!--
function confirmClear() {
    return confirm(${i18njs('404_errors_clear_confirmation')});
}
-->
</script>