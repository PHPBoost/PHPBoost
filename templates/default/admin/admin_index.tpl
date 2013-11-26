		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_QUICK_LINKS}</li>
				<li>
					<a href="admin_alerts.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/administrator_alert.png" alt="" /></a>
					<br />
					<a href="admin_alerts.php" class="quick_link">{L_ADMINISTRATOR_ALERTS}</a>
				</li>
				<li>
					<a href="${relative_url(AdminMembersUrlBuilder::management())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/members.png" alt="" /></a>
					<br />
					<a href="${relative_url(AdminMembersUrlBuilder::management())}" class="quick_link">{L_USERS_MANAGMENT}</a>
				</li>
				<li>
					<a href="menus/menus.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/menus.png" alt="" /></a>
					<br />
					<a href="menus/menus.php" class="quick_link">{L_MENUS_MANAGMENT}</a>
				</li>
				<li>
					<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}" class="quick_link">{L_MODULES_MANAGMENT}</a>
				</li>
				<li>
					<a href="updates/updates.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/updater.png" alt="" /></a>
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
								<div class="message_helper-warning">
									<div class="message-helper-content">{L_UNREAD_ALERT}</div>
								</div>
							# ELSE #
								<div class="message-helper-success">
									<div class="message-helper-content">{L_NO_UNREAD_ALERT}</div>
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
								# START comments_list #	
								<div style="margin-bottom:10px;">
									<a href="{comments_list.U_LINK}"><img src="{PATH_TO_ROOT}/templates/default/images/admin/ancre.png" alt="" /></a> <span class="smaller">{L_BY} {comments_list.U_PSEUDO}</span>
									<p class="smaller">{comments_list.CONTENT}</p>
								</div>	
								# END comments_list #
								# IF C_NO_COM #
								<p style="text-align:center;margin:0px;margin-top:50px;"><em>{L_NO_COMMENT}</em></p>
								# ENDIF #
							</div>
							<p style="text-align:center;margin:0;margin-top:9px;"><a class="small" href="${relative_url(UserUrlBuilder::comments())}">{L_VIEW_ALL_COMMENTS}</a></p>
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
										<button type="submit" name="writingpad" value="true">{L_UPDATE}</button>
											&nbsp;&nbsp; 
										<button type="reset" value="true">{L_RESET}</button>
									</p>
								</div>
							</td>
						</tr>
					</table>	
					<input type="hidden" name="token" value="{TOKEN}">
				</form>
			</div>
			
			<div style="clear:right;"></div>
			{L_USER_ONLINE}
			<table>
				<thead>
					<tr> 
						<th>
							{L_USER_ONLINE}
						</th>
						<th>
							{L_USER_IP}
						</th>
						<th>
							{L_LOCALISATION}
						</th>
						<th>
							{L_LAST_UPDATE}
						</th>
					</tr>
				</thead>
				<tbody>
					# START user #
					<tr> 
						<td>
							{user.USER}
						</td>
						<td>
							{user.USER_IP}
						</td>
						<td>
							{user.WHERE}
						</td>
						<td>
							{user.TIME}
						</td>					
					</tr>
					# END user #
				</tbody>
			</table>
		</div>
			