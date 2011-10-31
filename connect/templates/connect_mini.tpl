# INCLUDE AUTHENTICATION_FORM #
	# IF C_VERTICAL #
		# IF C_USER_CONNECTED #		
		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_PROFIL}</h5>
			</div>
			<div class="module_mini_contents" style="text-align:left;">
				<ul style="margin:0;padding:0;padding-left:4px;list-style-type:none;line-height:18px">
					<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members_mini.png" alt="" class="valign_middle" /> <a href="${relative_url(UserUrlBuilder::home_profile())}" class="small_link">{L_PRIVATE_PROFIL}</a></li>
					<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{IMG_PM}" class="valign_middle" alt="" /> <a href="{U_USER_PM}" class="small_link">{L_NBR_PM}</a>&nbsp;</li>
					
					# IF C_ADMIN_AUTH # 
					<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/ranks_mini.png" alt="" class="valign_middle" /> <a href="{PATH_TO_ROOT}/admin/admin_index.php" class="small_link">{L_ADMIN_PANEL}
						# IF C_UNREAD_ALERT #
							({NUMBER_UNREAD_ALERTS})
						# ENDIF #
					</a></li> 
					# ENDIF #
					
					# IF C_MODERATOR_AUTH # 
					<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/modo_mini.png" alt="" class="valign_middle" /> <a href="${relative_url(UserUrlBuilder::moderation_panel())}" class="small_link">{L_MODO_PANEL}</a></li> 
					# ENDIF #
					
					# IF C_UNREAD_CONTRIBUTION #
						# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION #
							<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/contribution_panel_mini_new.gif" alt="" class="valign_middle" /> <a href="{U_CONTRIBUTION}" class="small_link">{L_CONTRIBUTION_PANEL} ({NUM_UNREAD_CONTRIBUTIONS})</a></li> 
						# ELSE #
							<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/contribution_panel_mini_new.gif" alt="" class="valign_middle" /> <a href="{U_CONTRIBUTION}" class="small_link">{L_CONTRIBUTION_PANEL}</a></li> 
						# ENDIF #
					# ELSE #
						<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/contribution_panel_mini.png" alt="" class="valign_middle" /> <a href="{U_CONTRIBUTION}" class="small_link">{L_CONTRIBUTION_PANEL}</a></li> 
					# ENDIF #
					
					<li><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/home_mini.png" alt="" class="valign_middle" /> <a href="{U_DISCONNECT}" class="small_link">{L_DISCONNECT}</a></li>
				</ul>
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
		# ENDIF #
	# ELSE #
		# IF C_USER_CONNECTED #
		<p style="text-align:right;color:#FFFFFF;">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members_mini.png" alt="" class="valign_middle" /> <a href="{U_HOME_PROFILE}" class="small_link">{L_PRIVATE_PROFIL}</a>&nbsp;
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{IMG_PM}" class="valign_middle" alt="" /> <a href="{U_USER_PM}" class="small_link">{L_NBR_PM}</a>&nbsp;
			
			# IF C_ADMIN_AUTH #
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/ranks_mini.png" alt="" class="valign_middle" /> <a href="{U_ADMINISTRATION}" class="small_link">{L_ADMIN_PANEL} # IF C_UNREAD_ALERT # ({NUMBER_UNREAD_ALERTS}) # ENDIF # </a>&nbsp; 
			# ENDIF #
			
			# IF C_UNREAD_CONTRIBUTION #
				# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION #
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/contribution_panel_mini_new.gif" alt="" class="valign_middle" /> <a href="{U_CONTRIBUTION}" class="small_link">{L_CONTRIBUTION_PANEL} ({NUM_UNREAD_CONTRIBUTIONS})</a>&nbsp;
				# ELSE #
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/contribution_panel_mini_new.gif" alt="" class="valign_middle" /> <a href="{U_CONTRIBUTION}" class="small_link">{L_CONTRIBUTION_PANEL}</a>&nbsp;
				# ENDIF #
			# ELSE #
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/contribution_panel_mini.png" alt="" class="valign_middle" /> <a href="{U_CONTRIBUTION}" class="small_link">{L_CONTRIBUTION_PANEL}</a>&nbsp;
			# ENDIF #
			
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/home_mini.png" alt="" class="valign_middle" /> <a href="{U_DISCONNECT}" class="small_link">{L_DISCONNECT}</a>
			&nbsp;&nbsp;&nbsp;
		</p>
		# ENDIF #
	# ENDIF #