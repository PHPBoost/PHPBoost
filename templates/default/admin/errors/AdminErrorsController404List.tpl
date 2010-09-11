<form action="{U_CLEAR_404_ERRORS}" method="post" class="fieldset_content" onsubmit="javascript:return confirmClear();">
    <fieldset>
        <legend>${i18n('clear_404_list')}</legend>
        <dl>
            <dt><label>${i18n('clear_404_list')}</label><br /><span>${i18n('clear_404_list_explain')}</span></dt>
            <dd><label><input type="submit" name="clear" value="${i18n('clear_404_list')}" class="reset" /> </label></dd>
        </dl>
    </fieldset>
	<input type="hidden" name="token" value="{TOKEN}" />
</form>            
<br />
<table class="module_table">
	<thead>
		<tr>
            <th>${i18n('404_error_requested_url')}</th>
            <th>${i18n('404_error_from_url')}</th>
            <th style="width:50px;">${i18n('404_error_times')}</th>
            <th style="width:50px;">${i18n('404_error_delete')}</th>
		</tr>
	</thead>
	<tbody>
		# START errors #
		<tr>
			<td class="row1"><a href="{errors.REQUESTED_URL}" title="${i18n('404_error_requested_url')}">{errors.REQUESTED_URL}</a></td>
            <td class="row2"><a href="{errors.FROM_URL}" title="${i18n('404_error_from_url')}">{errors.FROM_URL}</a></td>
            <td class="row2" style="text-align: center;">{errors.TIMES}</td>
            <td class="row2" style="text-align: center;">
                <a href="{errors.U_DELETE}" title="${i18n('404_error_delete')}" onclick="javascript:return confirmDelete();">
                    <img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="${i18n('404_error_delete')}" />
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
    return confirm(${i18njs('404_errors_clear_confirmation')});
}
function confirmDelete() {
    return confirm(${i18njs('404_error_delete_confirmation')});
}
-->
</script>