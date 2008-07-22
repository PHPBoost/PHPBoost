		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_QUICK_LINKS}</li>
				<li>
					<a href="admin_members.php"><img src="../templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members.php" class="quick_link">{L_MEMBERS_MANAGMENT}</a>
				</li>
				<li>
					<a href="admin_menus.php"><img src="../templates/{THEME}/images/admin/menus.png" alt="" /></a>
					<br />
					<a href="admin_menus.php" class="quick_link">{L_MENUS_MANAGMENT}</a>
				</li>
				<li>
					<a href="admin_modules.php"><img src="../templates/{THEME}/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="admin_modules.php" class="quick_link">{L_MODULES_MANAGMENT}</a>
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
						{alerts.IMG} <a href="{alerts.URL}">{alerts.DETAILS}</a>
					</td>
					<td class="row1" style="text-align:center;">
						{alerts.DATE}
					</td>
					<td class="row1" style="text-align:center;{alerts.COLOR}">
						{alerts.PRIORITY} 
					</td>					
				</tr>
				# END alerts #
				
				# IF C_ALERT_OR_ACTION #
				<tr> 
					<td class="row2" style="text-align:center;" colspan="3">
						<a href="">{L_DISPLAY_ALL_ALERTS}</a>
					</td>
				</tr>
				# ENDIF #
			</table>

			<table class="module_table">
				<tr> 
					<th colspan="4">
						{L_USER_ONLINE}
					</th>
				</tr>	
				<tr> 
					<td class="row2" style="text-align:center;">
						{L_USER_ONLINE}
					</td>
					<td  class="row2" style="text-align:center;">
						{L_USER_IP}
					</td>
					<td  class="row2" style="text-align:center;">
						{L_LOCALISATION}
					</td>
					<td  class="row2" style="text-align:center;">
						{L_LAST_UPDATE}
					</td>
				</tr>				
				# START user #
				<tr> 
					<td class="row1" style="text-align:center;">
						{user.USER}
					</td>
					<td class="row1" style="text-align:center;">
						{user.USER_IP}
					</td>
					<td class="row1" style="text-align:center;">
						{user.WHERE}
					</td>
					<td class="row1" style="text-align:center;">
						{user.TIME}
					</td>					
				</tr>
				# END user #
			</table>
		</div>
			