		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_QUICK_LINKS}</li>
				<li>
					<a href="admin_alerts.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/administrator_alert.png" alt="" /></a>
					<br />
					<a href="admin_alerts.php" class="quick_link">{L_ADMINISTRATOR_ALERTS}</a>
				</li>
				<li>
					<a href="admin_members.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members.php" class="quick_link">{L_USERS_MANAGMENT}</a>
				</li>
				<li>
					<a href="menus/menus.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/menus.png" alt="" /></a>
					<br />
					<a href="menus/menus.php" class="quick_link">{L_MENUS_MANAGMENT}</a>
				</li>
				<li>
					<a href="admin_modules.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="admin_modules.php" class="quick_link">{L_MODULES_MANAGMENT}</a>
				</li>
				<li>
					<a href="updates/updates.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/updater.png" alt="" /></a>
					<br />
					<a href="updates/updates.php" class="quick_link">{L_WEBSITE_UPDATES}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			<div style="width:49%;float:left">
				<table class="module_table">
					<tr> 
						<th colspan="4">
							{L_ADMIN_ALERTS}
						</th>
					</tr>
					<tr> 
						<td class="row2">
							# IF C_UNREAD_ALERTS #
								<div class="warning">
									{L_UNREAD_ALERT}
								</div>
							# ELSE #
								<div class="success">
									{L_NO_UNREAD_ALERT}
								</div>
							# ENDIF #
							<div style="text-align:center;">
								<a href="admin_alerts.php">{L_DISPLAY_ALL_ALERTS}</a>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<div style="width:49%;float:right">
				<table class="module_table">
					<tr> 
						<th colspan="4">
							Dernier commentaires
						</th>
					</tr>
					<tr> 
						<td class="row2">
							<span class="text_small">Par Monsieur PHPBoost, sur Bonjour tout le monde !</span>
							<br />
							Bonjour, ceci est un commentaire. Pour supprimer un commentaire, connectez-vous, et affichez les commentaires de cet article. Vous pourrez alors ...
						</td>
					</tr>
				</table>
			</div>
			
			<table class="module_table" style="margin-top:30px;">
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
			