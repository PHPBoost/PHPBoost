		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_QUICK_LINKS}</li>
				<li>
					<a href="admin_members.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members.php" class="quick_link">{L_USERS_MANAGMENT}</a>
				</li>
				<li>
					<a href="admin_menus.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/menus.png" alt="" /></a>
					<br />
					<a href="admin_menus.php" class="quick_link">{L_MENUS_MANAGMENT}</a>
				</li>
				<li>
					<a href="admin_modules.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="admin_modules.php" class="quick_link">{L_MODULES_MANAGMENT}</a>
				</li>
				<li>
					<a href="admin_updates.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="admin_updates.php" class="quick_link">{L_WEBSITE_UPDATES}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			<fieldset style="width:90%; margin:auto;">
				<legend>
					{L_ADMIN_ALERTS}
				</legend>
				# IF C_UNREAD_ALERTS #
					<div class="warning">
						{L_UNREAD_ALERT}
					</div>
				# ELSE #
					<div class="success">
						{L_NO_UNREAD_ALERT}
					</div>
				# ENDIF #
				<br />
				<div style="text-align:center;">
					<a href="admin_alerts.php">{L_DISPLAY_ALL_ALERTS}</a>
				</div>
			</fieldset>
			
			<br />
			
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
			