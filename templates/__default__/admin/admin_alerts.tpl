<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;" aria-label="{@admin.alerts}">
		<i class="fa fa-bars" aria-hidden="true"></i> {@admin.alerts}
	</a>
	<ul>
		<li>
			<a href="admin_alerts.php" class="quick-link">{@admin.alerts.list}</a>
		</li>
	</ul>
</nav>

<script>
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
					document.getElementById("status_" + id).setAttribute('aria-label',${escapejs(@admin.unfix.alert)});
				}
				else
				{
					document.getElementById("status_" + id).innerHTML = "<i class=\"fa fa-times error\"></i>";
					document.getElementById("status_" + id).href = "javascript:change_alert_status('" + id + "', '0');";
					document.getElementById("status_" + id).setAttribute('aria-label',${escapejs(@admin.fix.alert)});
				}
			}
		}

		xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr_object.send(data);
	}

	function delete_alert(id)
	{
		if( !confirm("{@admin.warning.delete.alert}") )
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
</script>

<div id="admin-contents">
	<fieldset>
		<legend>{@admin.alerts}</legend>
		<div class="fieldset-inset">
		# IF C_EXISTING_ALERTS #
			<table class="table">
				<thead>
					<tr>
						<th>
							<span class="html-table-header-sortable"><a href="{U_ORDER_ENTITLED_ASC}" aria-label="{@common.sort.asc}"><i class="fa fa-caret-up" aria-hidden="true"></i></a></span>
							{@common.type}
							<span class="html-table-header-sortable"><a href="{U_ORDER_ENTITLED_DESC}" aria-label="{@common.sort.desc}"><i class="fa fa-caret-down" aria-hidden="true"></i></a></span>
						</th>
						<th>
							<span class="html-table-header-sortable"><a href="{U_ORDER_CREATION_DATE_ASC}" aria-label="{@common.sort.asc}"><i class="fa fa-caret-up" aria-hidden="true"></i></a></span>
							{@common.creation.date}
							<span class="html-table-header-sortable"><a href="{U_ORDER_CREATION_DATE_DESC}" aria-label="{@common.sort.desc}"><i class="fa fa-caret-down" aria-hidden="true"></i></a></span>
						</th>
						<th>
							<span class="html-table-header-sortable"><a href="{U_ORDER_PRIORITY_ASC}" aria-label="{@common.sort.asc}"><i class="fa fa-caret-up" aria-hidden="true"></i></a></span>
							{@admin.priority}
							<span class="html-table-header-sortable"><a href="{U_ORDER_PRIORITY_DESC}" aria-label="{@common.sort.desc}"><i class="fa fa-caret-down" aria-hidden="true"></i></a></span>
						</th>
						<th>
							<span class="html-table-header-sortable"><a href="{U_ORDER_STATUS_ASC}" aria-label="{@common.sort.asc}"><i class="fa fa-caret-up" aria-hidden="true"></i></a></span>
							{@common.actions}
							<span class="html-table-header-sortable"><a href="{U_ORDER_STATUS_DESC}" aria-label="{@common.sort.desc}"><i class="fa fa-caret-down" aria-hidden="true"></i></a></span>
						</th>
					</tr>
				</thead>
				<tbody>
					# START alerts #
					<tr id="delete_{alerts.ID}">
						<td>
							# IF alerts.C_ICON # <i class="{alerts.ICON}"></i># ENDIF # <a href="{alerts.FIXING_URL}">{alerts.NAME}</a>
						</td>
						<td>
							{alerts.DATE}
						</td>
						<td class="alert-priority {alerts.PRIORITY_CSS_CLASS}">
							{alerts.PRIORITY}
						</td>
						<td class="controls">
							{alerts.ACTIONS}
							<a href="javascript:change_alert_status('{alerts.ID}', '{alerts.STATUS}');" aria-label="# IF alerts.C_PROCESSED #{@admin.unfix.alert}# ELSE #{@admin.fix.alert}# ENDIF #" id="status_{alerts.ID}"><i class="fa fa-fw # IF alerts.C_PROCESSED #fa-check success# ELSE #fa-times error# ENDIF #"></i></a>
							<a href="javascript:delete_alert('{alerts.ID}');" aria-label="{@common.delete}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
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
			<div class="message-helper bgc question message-helper-small">{@admin.no.alert}</div>
		# ENDIF #
		</div>
	</fieldset>
</div>
<script>
	// Highlight current order
	if(window.location.search)
		jQuery('.html-table-header-sortable a[href*="' + window.location.search + '"]').parent().addClass('sort-active');
	else
		jQuery('.html-table-header-sortable').first().addClass('sort-active');
</script>
