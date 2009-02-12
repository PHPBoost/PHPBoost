		<div class="dock" id="dock">
 			<div class="dock-container">
 			 	<a class="dock-item" href="{PATH_TO_ROOT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/dock/home.png" alt="home" />
		     		<span>Accueil</span></a>
		     	
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
				

		<a class="dock-item" href="{U_REGISTER}">
		     	<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/dock/suscribe.png" alt="suscribe" />
		     	<span>{L_REGISTER}</span></a>
		     	<a class="dock-item" href="{PATH_TO_ROOT}/member/error.php" onclick="return check_connect();">
		     	<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/dock/connect.png" alt="connect" />
		     	<span>{L_CONNECT}</span></a>
		     	
		     	
		<div style="margin:auto;text-align:center">
			<!--<form action="{U_CONNECT}" method="post" onsubmit="return check_connect();" style="text-align:right;display:inline;">
				<label><input size="15" type="text" class="text" id="login" name="login" value="{L_PSEUDO}" onclick="if( this.value == '{L_PSEUDO}' ) this.value = '';" maxlength="25" /></label>
				<label><input size="15" type="password" id="password" name="password" class="text" maxlength="30" /></label>
				<label><input checked="checked" type="checkbox" name="auto" /></label>
				<input type="submit" name="connect" value="{L_CONNECT}" class="submit" />
			</form>-->
			
			# IF C_USER_REGISTER #
			<!--<form action="{U_REGISTER}" method="post" style="display:inline;">
				<input type="submit" name="register" value="{L_REGISTER}" class="submit" />
			</form>-->
			# ENDIF #
		</div>
		
			# ENDIF #

 			

		     	
		
		# IF C_USER_CONNECTED #
		
		     	<a class="dock-item" href="{PATH_TO_ROOT}/member/member{U_USER_ID}"><img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/dock/user.png" alt="account" />
		     	<span>{L_PRIVATE_PROFIL}</span></a>

		       	<a class="dock-item" href="{U_USER_PM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/dock/mail.png" alt="mail" />
		        <span>{L_NBR_PM}</span></a> 		
		
			
			
			
			# IF C_ADMIN_AUTH #
			<a class="dock-item" href="{PATH_TO_ROOT}/admin/admin_index.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/dock/admin.png" alt="admin" />
			<span>{L_ADMIN_PANEL}</span>
				# IF C_UNREAD_ALERT #
					({NUMBER_UNREAD_ALERTS})
				# ENDIF #
			</a>
			# ENDIF #
			
			# IF C_UNREAD_CONTRIBUTION #
				# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION #
					<a href="{PATH_TO_ROOT}/member/contribution_panel.php" class="dock-item"><img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/dock/contribution.png" alt="contribution" /><span>{L_CONTRIBUTION_PANEL} ({NUM_UNREAD_CONTRIBUTIONS})</span></a>
				# ELSE #
					<a href="{PATH_TO_ROOT}/member/contribution_panel.php" class="dock-item"><img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/dock/contribution.png" alt="contribution" /><span>{L_CONTRIBUTION_PANEL}</span></a>
				# ENDIF #
			# ELSE #
					<a href="{PATH_TO_ROOT}/member/contribution_panel.php" class="dock-item"><img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/dock/contribution.png" alt="contribution" /><span>{L_CONTRIBUTION_PANEL}</span></a>
			# ENDIF #
			
			<a href="{U_DISCONNECT}" class="dock-item"><img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/dock/logout.png" alt="logout" /><span>{L_DISCONNECT}</span></a>
			&nbsp;&nbsp;&nbsp;
		
		# END ENDIF #
			</div> 
		</div>

		
