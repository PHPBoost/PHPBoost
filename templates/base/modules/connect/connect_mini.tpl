		# IF C_USER_NOTCONNECTED #	
		<script type="text/javascript">
		<!--
		function check_connect(){
			if(document.getElementById('login').value == "") {
				alert("{L_REQUIRE_PSEUDO}");
				return false;
			}
			if(document.getElementById('password').value == "") {
				alert("{L_REQUIRE_PASSWORD}");
				return false;
			}
			return true;
		}
		
		-->
		</script>
		
		<div style="float:right;margin-right:8px;">
			<form action="{U_CONNECT}" method="post" onsubmit="return check_connect();" style="text-align:right;display:inline;">
				<p style="display:inline"><input size="15" type="text" id="login" name="login" value="{L_PSEUDO}" class="connect_form" onfocus="if( this.value == '{L_PSEUDO}' ) this.value = '';" maxlength="25" />
				<input size="15" type="password" id="password" name="password" class="connect_form" value="******" onfocus="if( this.value == '******' ) this.value = '';" maxlength="30" />
				<input checked="checked" type="checkbox" name="auto" />
				<input type="submit" name="connect" value="{L_CONNECT}" class="submit" /></p>
			</form>
			
			# IF C_USER_REGISTER #
			<form action="{U_REGISTER}" method="post" style="display:inline;">
				<p style="display:inline"><input type="submit" name="register" value="{L_REGISTER}" class="submit" /></p>
			</form>
			# ENDIF #
		</div>
		# ENDIF #		
		
		
		# IF C_USER_CONNECTED #
		<p style="text-align:right;color:#FFFFFF;">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members_mini.png" alt="" class="valign_middle" /> <a href="{PATH_TO_ROOT}/member/member{U_USER_ID}" class="small_link">{L_PRIVATE_PROFIL}</a>&nbsp;
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{IMG_PM}" class="valign_middle" alt="" /> <a href="{U_USER_PM}" class="small_link">{L_NBR_PM}</a>&nbsp;
			
			# IF C_ADMIN_AUTH #
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/ranks_mini.png" alt="" class="valign_middle" /> <a href="{PATH_TO_ROOT}/admin/admin_index.php" class="small_link">{L_ADMIN_PANEL} # IF C_UNREAD_ALERT # ({NUMBER_UNREAD_ALERTS}) # ENDIF # </a>&nbsp; 
			# ENDIF #
			
			# IF C_UNREAD_CONTRIBUTION #
				# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION #
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/contribution_panel_mini_new.gif" alt="" class="valign_middle" /> <a href="{PATH_TO_ROOT}/member/contribution_panel.php" class="small_link">{L_CONTRIBUTION_PANEL} ({NUM_UNREAD_CONTRIBUTIONS})</a>&nbsp;
				# ELSE #
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/contribution_panel_mini_new.gif" alt="" class="valign_middle" /> <a href="{PATH_TO_ROOT}/member/contribution_panel.php" class="small_link">{L_CONTRIBUTION_PANEL}</a>&nbsp;
				# ENDIF #
			# ELSE #
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/contribution_panel_mini.png" alt="" class="valign_middle" /> <a href="{PATH_TO_ROOT}/member/contribution_panel.php" class="small_link">{L_CONTRIBUTION_PANEL}</a>&nbsp;
			# ENDIF #
			
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/home_mini.png" alt="" class="valign_middle" /> <a href="{U_DISCONNECT}" class="small_link">{L_DISCONNECT}</a>
			&nbsp;&nbsp;&nbsp;
		</p>
		# END ENDIF #
		