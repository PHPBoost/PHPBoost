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
		
		<div id="admin_contents">
			<table class="module_table">
				<tr> 
					<th colspan="4">
						{L_ADMIN_ALERTS}
					</th>
				</tr>	
				# IF C_ALERT_OR_ACTION #
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
				</tr>
				# ELSE #
				<tr> 
					<td class="row1" style="text-align:center;">
						{L_NO_ALERT_OR_ACTION}
					</td>					
				</tr>
				# ENDIF #
				
				
				# START alerts #
				<tr> 
					<td class="row1" style="text-align:center;">
						{alerts.IMG} <a href="{alerts.URL}">{alerts.NAME}</a>
					</td>
					<td class="row1" style="text-align:center;">
						{alerts.DATE}
					</td>
					<td class="row1" style="text-align:center;{alerts.STYLE}">
						{alerts.PRIORITY} 
					</td>					
				</tr>
				# END alerts #
				
				# IF C_ALERT_OR_ACTION #
				<tr> 
					<td class="row2" style="text-align:center;" colspan="3">
						<a href="admin_alerts.php">{L_DISPLAY_ALL_ALERTS}</a>
					</td>
				</tr>
				# ENDIF #
			</table>
		</div>
			