		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_ADMIN_ALERTS}</li>
				<li>
					<a href="admin_alerts.php"><img src="../templates/{THEME}/images/admin/administrator_alert.png" alt="" /></a>
					<br />
					<a href="admin_alerts.php" class="quick_link">{L_ADMINISTRATOR_ALERTS_LIST}</a>
				</li>
			</ul>
		</div>
		
		<script type="text/javascript">
		<!--
		function change_alert_status(id, status)
		{
			document.getElementById("status_" + id).innerHTML = "<img src=\"../templates/{THEME}/images/loading_mini.gif\" alt=\"\" />";
			
			var xhr_object = null;
			var data = null;
			var filename = PATH_TO_ROOT + '/kernel/framework/ajax/admin_alerts.php?change_status=' + id;

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
						document.getElementById("status_" + id).innerHTML = "<img src=\"../templates/{THEME}/images/processed_mini.png\" alt=\"\" />";
						document.getElementById("status_" + id).href = "javascript:change_alert_status('" + id + "', '2');";
					}
					else
					{
						document.getElementById("status_" + id).innerHTML = "<img src=\"../templates/{THEME}/images/not_processed_mini.png\" alt=\"\" />";
						document.getElementById("status_" + id).href = "javascript:change_alert_status('" + id + "', '0');";
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
			
			document.getElementById("status_" + id).innerHTML = "<img src=\"../templates/{THEME}/images/loading_mini.gif\" alt=\"\" />";
			
			var xhr_object = null;
			var data = null;
			var filename = PATH_TO_ROOT + '/kernel/framework/ajax/admin_alerts.php?delete=' + id;

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
					document.getElementById("delete_" + id).style.display = "none";
				}
			}

			xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr_object.send(data);	
		}
		-->
		</script>
				
		<div id="admin_contents">
			<table class="module_table">
				<tr> 
					<th colspan="4">
						{L_ADMIN_ALERTS}
					</th>
				</tr>	
				# IF C_EXISTING_ALERTS #
				<tr> 
					<td class="row2" style="text-align:center;">
						{L_TYPE}
					</td>
					<td class="row2" style="text-align:center;">
						{L_DATE}
					</td>
					<td class="row2" style="text-align:center;">
						{L_PRIORITY}
					</td>
					<td class="row2" style="text-align:center;">
						{L_ACTIONS}
					</td>
				</tr>
				# ELSE #
				<tr> 
					<td class="row1" style="text-align:center;" colspan="4">
						{L_NO_ALERT}
					</td>					
				</tr>
				# ENDIF #
				
				# START alerts #
				<tr id="delete_{alerts.ID}"> 
					<td class="row1" style="text-align:center;">
						{alerts.IMG} <a href="{alerts.URL}">{alerts.NAME}</a>
					</td>
					<td class="row1" style="text-align:center;">
						{alerts.DATE}
					</td>
					<td class="row1" style="text-align:center;{alerts.STYLE}">
						{alerts.PRIORITY} 
					</td>
					<td class="row1" style="text-align:center;">
						{alerts.ACTIONS}
						# IF alerts.C_PROCESSED #
						<a href="javascript:change_alert_status('{alerts.ID}', '{alerts.STATUS}');" id="status_{alerts.ID}"><img src="../templates/{THEME}/images/processed_mini.png" alt="delete" /></a>
						# ELSE #
						<a href="javascript:change_alert_status('{alerts.ID}', '{alerts.STATUS}');" id="status_{alerts.ID}"><img src="../templates/{THEME}/images/not_processed_mini.png" alt="delete" /></a>
						# ENDIF #
						<a href="javascript:delete_alert('{alerts.ID}');"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="delete" /></a>
					</td>			
				</tr>
				# END alerts #
			</table>
		</div>
			