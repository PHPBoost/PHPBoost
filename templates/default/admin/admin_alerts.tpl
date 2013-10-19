		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_ADMIN_ALERTS}</li>
				<li>
					<a href="admin_alerts.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/administrator_alert.png" alt="" /></a>
					<br />
					<a href="admin_alerts.php" class="quick_link">{L_ADMINISTRATOR_ALERTS_LIST}</a>
				</li>
			</ul>
		</div>
		
		<script type="text/javascript">
		<!--
		function change_alert_status(id, status)
		{
			document.getElementById("status_" + id).innerHTML = "<img src=\"{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif\" alt=\"\" />";
			
			var xhr_object = null;
			var data = null;
			var filename = PATH_TO_ROOT + '/kernel/framework/ajax/admin_alerts.php?change_status=' + id + '&token={TOKEN}';

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
					if( status == 0 )
					{
						document.getElementById("status_" + id).innerHTML = "<img src=\"{PATH_TO_ROOT}/templates/{THEME}/images/processed_mini.png\" alt=\"\" />";
						document.getElementById("status_" + id).href = "javascript:change_alert_status('" + id + "', '2');";
						document.getElementById("status_" + id).title = "{L_UNFIX}";
					}
					else
					{
						document.getElementById("status_" + id).innerHTML = "<img src=\"{PATH_TO_ROOT}/templates/{THEME}/images/not_processed_mini.png\" alt=\"\" />";
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
			
			document.getElementById("status_" + id).innerHTML = "<img src=\"{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif\" alt=\"\" />";
			
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
				
		<div id="admin_contents">
			{L_ADMIN_ALERTS}
			# IF C_EXISTING_ALERTS #
			<table>
				<thead>
					<tr> 
						<th>
							# IF NOT C_ORDER_ENTITLED_ASC #
								<a href="{U_ORDER_ENTITLED_ASC}" class="sort-up"></a>
							# ENDIF #
							{L_TYPE}
							# IF NOT C_ORDER_ENTITLED_DESC #
								<a href="{U_ORDER_ENTITLED_DESC}" class="sort-down"></a>
							# ENDIF #
						</th>
						<th>
							# IF NOT C_ORDER_CREATION_DATE_ASC #
								<a href="{U_ORDER_CREATION_DATE_ASC}" class="sort-up"></a>
							# ENDIF #
							{L_DATE}
							# IF NOT C_ORDER_CREATION_DATE_DESC #
								<a href="{U_ORDER_CREATION_DATE_DESC}" class="sort-down"></a>
							# ENDIF #
						</th>
						<th>
							# IF NOT C_ORDER_PRIORITY_ASC #
								<a href="{U_ORDER_PRIORITY_ASC}" class="sort-up"></a>
							# ENDIF #
							{L_PRIORITY}
							# IF NOT C_ORDER_PRIORITY_DESC #
								<a href="{U_ORDER_PRIORITY_DESC}" class="sort-down"></a>
							# ENDIF #
						</th>
						<th>
							# IF NOT C_ORDER_STATUS_ASC #
								<a href="{U_ORDER_STATUS_ASC}" class="sort-up"></a>
							# ENDIF #
							{L_ACTIONS}
							# IF NOT C_ORDER_STATUS_DESC #
								<a href="{U_ORDER_STATUS_DESC}" class="sort-down"></a>
							# ENDIF #
						</th>
					</tr>
				</thead>
				# IF C_PAGINATION #
				<tfoot>
					<tr> 
						<th colspan="4">
							{PAGINATION}
						</th>					
					</tr>
				</tfoot>
				# ENDIF #
				<tbody>
					# START alerts #
					<tr id="delete_{alerts.ID}"> 
						<td>
							{alerts.IMG} <a href="{alerts.FIXING_URL}">{alerts.NAME}</a>
						</td>
						<td>
							{alerts.DATE}
						</td>
						<td style="{alerts.STYLE}">
							{alerts.PRIORITY} 
						</td>
						<td>
							{alerts.ACTIONS}
							# IF alerts.C_PROCESSED #
							<a href="javascript:change_alert_status('{alerts.ID}', '{alerts.STATUS}');" title="{L_UNFIX}" id="status_{alerts.ID}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/processed_mini.png" alt="{L_UNFIX}" /></a>
							# ELSE #
							<a href="javascript:change_alert_status('{alerts.ID}', '{alerts.STATUS}');" title="{L_FIX}" id="status_{alerts.ID}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/not_processed_mini.png" alt="{L_FIX}" /></a>
							# ENDIF #
							<a href="javascript:delete_alert('{alerts.ID}');" title="{L_DELETE}" class="delete"></a>
						</td>			
					</tr>
					# END alerts #
				</tbody>
			</table>
			# ELSE #
				{L_NO_ALERT}
			# ENDIF #
		</div>