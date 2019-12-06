<nav id="admin-quick-menu">
	<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;" aria-label="{L_ADMIN_ALERTS}">
		<i class="fa fa-bars" aria-hidden="true"></i> {L_ADMIN_ALERTS}
	</a>
	<ul>
		<li>
			<a href="admin_alerts.php" class="quick-link">{L_ADMINISTRATOR_ALERTS_LIST}</a>
		</li>
	</ul>
</nav>

<script>
<!--
function change_alert_status(id, status)
{
	document.getElementById("status_" + id).innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

	var xhr_object = null;
	var data = null;
	var filename = PATH_TO_ROOT + '/kernel/framework/ajax/admin_alerts.php?change_status=' + id + '&token={TOKEN}';

	if(window.XMLHttpRequest) // Firefox
		xhr_object = new XMLHttpRequest();
	else if(window.ActiveXObject) // Internet Explorer
		xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
	else // XMLHttpRequest non supporté par le navigateur
		return;

	xhr_object.open('GET', filename, true);
	xhr_object.onreadystatechange = function()
	{
		if( xhr_object.readyState == 4 && xhr_object.responseText == "1" )
		{
			if( status == 0 )
			{
				document.getElementById("status_" + id).innerHTML = "<i class=\"fa fa-check success\"></i>";
				document.getElementById("status_" + id).href = "javascript:change_alert_status('" + id + "', '2');";
				document.getElementById("status_" + id).title = "{L_UNFIX}";
			}
			else
			{
				document.getElementById("status_" + id).innerHTML = "<i class=\"fa fa-times error\"></i>";
				document.getElementById("status_" + id).href = "javascript:change_alert_status('" + id + "', '0');";
				document.getElementById("status_" + id).title = "{L_FIX}";
			}
		}
	}

	xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr_object.send(data);
}

function delete_alert(id)
{
	if( !confirm("{L_CONFIRM_DELETE_ALERT}") )
		return;

	document.getElementById("status_" + id).innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

	var xhr_object = null;
	var data = null;
	var filename = PATH_TO_ROOT + '/kernel/framework/ajax/admin_alerts.php?delete=' + id + '&token={TOKEN}';

	if(window.XMLHttpRequest) // Firefox
	   xhr_object = new XMLHttpRequest();
	else if(window.ActiveXObject) // Internet Explorer
	   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
	else // XMLHttpRequest non support� par le navigateur
		return;

	xhr_object.open('GET', filename, true);
	xhr_object.onreadystatechange = function()
	{
		if( xhr_object.readyState == 4 && xhr_object.responseText == "1" )
		{
			document.getElementById("delete_" + id).style.display = "none";
		}
	}

	xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr_object.send(data);
}
-->
</script>

<div id="admin-contents">
	<fieldset>
		<legend>{L_ADMIN_ALERTS}</legend>
		<div class="fieldset-inset">
		# IF C_EXISTING_ALERTS #
		<table class="table">
			<caption>{L_ADMIN_ALERTS}</caption>
			<thead>
				<tr>
					<th>
						# IF NOT C_ORDER_ENTITLED_ASC #
							<a href="{U_ORDER_ENTITLED_ASC}" aria-label="${LangLoader::get_message('sort.asc', 'common')}"><i class="fa fa-arrow-up" aria-hidden="true" aria-label="${LangLoader::get_message('sort.asc', 'common')}"></i></a>
						# ENDIF #
						{L_TYPE}
						# IF NOT C_ORDER_ENTITLED_DESC #
							<a href="{U_ORDER_ENTITLED_DESC}" aria-label="${LangLoader::get_message('sort.desc', 'common')}"><i class="fa fa-arrow-down" aria-hidden="true" aria-label="${LangLoader::get_message('sort.desc', 'common')}"></i></a>
						# ENDIF #
					</th>
					<th>
						# IF NOT C_ORDER_CREATION_DATE_ASC #
							<a href="{U_ORDER_CREATION_DATE_ASC}" aria-label="${LangLoader::get_message('sort.asc', 'common')}"><i class="fa fa-arrow-up" aria-hidden="true" aria-label="${LangLoader::get_message('sort.asc', 'common')}"></i></a>
						# ENDIF #
						{L_DATE}
						# IF NOT C_ORDER_CREATION_DATE_DESC #
							<a href="{U_ORDER_CREATION_DATE_DESC}" aria-label="${LangLoader::get_message('sort.desc', 'common')}"><i class="fa fa-arrow-down" aria-hidden="true" aria-label="${LangLoader::get_message('sort.desc', 'common')}"></i></a>
						# ENDIF #
					</th>
					<th>
						# IF NOT C_ORDER_PRIORITY_ASC #
							<a href="{U_ORDER_PRIORITY_ASC}" aria-label="${LangLoader::get_message('sort.asc', 'common')}"><i class="fa fa-arrow-up" aria-hidden="true" aria-label="${LangLoader::get_message('sort.asc', 'common')}"></i></a>
						# ENDIF #
						{L_PRIORITY}
						# IF NOT C_ORDER_PRIORITY_DESC #
							<a href="{U_ORDER_PRIORITY_DESC}" aria-label="${LangLoader::get_message('sort.desc', 'common')}"><i class="fa fa-arrow-down" aria-hidden="true" aria-label="${LangLoader::get_message('sort.desc', 'common')}"></i></a>
						# ENDIF #
					</th>
					<th>
						# IF NOT C_ORDER_STATUS_ASC #
							<a href="{U_ORDER_STATUS_ASC}" aria-label="${LangLoader::get_message('sort.asc', 'common')}"><i class="fa fa-arrow-up" aria-hidden="true" aria-label="${LangLoader::get_message('sort.asc', 'common')}"></i></a>
						# ENDIF #
						{L_ACTIONS}
						# IF NOT C_ORDER_STATUS_DESC #
							<a href="{U_ORDER_STATUS_DESC}" aria-label="${LangLoader::get_message('sort.desc', 'common')}"><i class="fa fa-arrow-down" aria-hidden="true" aria-label="${LangLoader::get_message('sort.desc', 'common')}"></i></a>
						# ENDIF #
					</th>
				</tr>
			</thead>
			<tbody>
				# START alerts #
				<tr id="delete_{alerts.ID}">
					<td>
						{alerts.IMG} <a href="{alerts.FIXING_URL}">{alerts.NAME}</a>
					</td>
					<td>
						{alerts.DATE}
					</td>
					<td class="alert-priority {alerts.PRIORITY_CSS_CLASS}">
						{alerts.PRIORITY}
					</td>
					<td>
						{alerts.ACTIONS}
						<a href="javascript:change_alert_status('{alerts.ID}', '{alerts.STATUS}');" aria-label="# IF alerts.C_PROCESSED #{L_UNFIX}# ELSE #{L_FIX}# ENDIF #" id="status_{alerts.ID}"><i class="fa # IF alerts.C_PROCESSED #fa-check success# ELSE #fa-times error# ENDIF #"></i></a>
						<a href="javascript:delete_alert('{alerts.ID}');" aria-label="{L_DELETE}"><i class="fa fa-trash-alt" aria-hidden="true" aria-label="{L_DELETE}"></i></a>
					</td>
				</tr>
				# END alerts #
			</tbody>
			# IF C_PAGINATION #
			<tfoot>
				<tr>
					<td colspan="4">
						# INCLUDE PAGINATION #
					</td>
				</tr>
			</tfoot>
			# ENDIF #
		</table>
		# ELSE #
		<div class="message-helper bgc question message-helper-small">{L_NO_ALERT}</div>
		# ENDIF #
		</div>
	</fieldset>
</div>
