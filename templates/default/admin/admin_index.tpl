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
			<div style="width:49%;float:left;">
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
				<table class="module_table">
					<tr> 
						<th colspan="4">
							{L_LAST_COMMENTS}
						</th>
					</tr>
					<tr> 
						<td class="row2">
							<div style="height:140px;width:358px;overflow:auto;margin:auto;">
								# START com_list #	
								<div style="margin-bottom:10px;">
									<a href="{com_list.U_PROV}#anchor_{com_list.COM_SCRIPT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/ancre.png" alt="" /></a> <span class="text_small">{L_BY} {com_list.USER_PSEUDO}</span>
									<p class="text_small">{com_list.CONTENTS}</p>
								</div>	
								# END com_list #
								# IF C_NO_COM #
								<p style="text-align:center;margin:0px;margin-top:50px;"><em>{L_NO_COMMENT}</em></p>
								# ENDIF #
							</div>
							<p style="text-align:center;margin:0;margin-top:9px;"><a class="small_link" href="admin_com.php">{L_VIEW_ALL_COMMENTS}</a></p>
						</td>
					</tr>
				</table>
			</div>
			<div style="float:right;width:50%;">
				<form action="admin_index.php" method="post">
					<table class="module_table">
						<tr> 
							<th colspan="4">
								{L_WRITING_PAD}
							</th>
						</tr>
						<tr> 
							<td class="row2">
								<div class="block_contents">
									<textarea id="writing_pad_content" name="writing_pad_content" cols="15" rows="10" style="height:243px">{WRITING_PAD_CONTENT}</textarea> 
									<p style="text-align:center;margin:0;margin-top:8px;">
										<input type="submit" name="writingpad" value="{L_UPDATE}" class="submit" />
										&nbsp;&nbsp; 
										<input type="reset" value="{L_RESET}" class="reset" />
									</p>
								</div>
							</td>
						</tr>
					</table>	
					<input type="hidden" name="token" value="{TOKEN}" />
				</form>
			</div>
			
			<div style="clear:right;"></div>
			
			<table class="module_table" style="width:99%">
				<tr> 
					<th colspan="4">
						{L_USER_ONLINE}
					</th>
				</tr>
				<tr> 
					<td class="row1" style="text-align:center;width:145px">
						{L_USER_ONLINE}
					</td>
					<td  class="row1" style="text-align:center;width:135px">
						{L_USER_IP}
					</td>
					<td  class="row1" style="text-align:center;">
						{L_LOCALISATION}
					</td>
					<td  class="row1" style="text-align:center;width:170px">
						{L_LAST_UPDATE}
					</td>
				</tr>	
				<tr> 
					<td colspan="4" class="row2" style="padding:0;">
						<div style="overflow:auto;">
							<table style="width:100%">
								# START user #
								<tr> 
									<td class="row2" style="text-align:center;width:145px">
										{user.USER}
									</td>
									<td class="row2" style="text-align:center;width:135px">
										{user.USER_IP}
									</td>
									<td class="row2" style="text-align:center;">
										{user.WHERE}
									</td>
									<td class="row2" style="text-align:center;width:170px">
										{user.TIME}
									</td>					
								</tr>
								# END user #
							</table>
						</div>
						&nbsp;
					</td>
				</tr>
			</table>
		</div>
			