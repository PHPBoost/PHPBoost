		# IF C_MEMBER_NOTCONNECTED #		
		<script type="text/javascript">
		<!--
		function check_conect(){
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
				
		<form action="{U_CONNECT}" method="post" onsubmit="return check_conect();" style="text-align:right;">
			<label><input size="15" type="text" class="text" id="login" name="login" value="{L_PSEUDO}" onclick="this.value='';" maxlength="25" /></label>
			<label><input size="15" type="password" id="password" name="password" class="text" maxlength="30" /></label>
			<label><input checked="checked" type="hidden" name="auto" /></label>
			<input type="submit" name="connect" value="{L_CONNECT}" class="submit" />
			{U_REGISTER}&nbsp;&nbsp;&nbsp;
		</form>	
		# ENDIF #		
		
		
		# IF C_MEMBER_CONNECTED #		
		<p style="text-align:right;color:#FFFFFF;">
			<img src="../templates/{THEME}/images/admin/members_mini.png" alt="" class="valign_middle" /> <a href="../member/member{U_MEMBER_ID}" class="small_link">{L_PRIVATE_PROFIL}</a>&nbsp;				
			{U_MEMBER_MP}&nbsp;
			
			# IF C_ADMIN_AUTH # 
			<img src="../templates/{THEME}/images/admin/ranks_mini.png" alt="" class="valign_middle" /> <a href="../admin/admin_index.php" class="small_link">{L_ADMIN_PANEL}</a>&nbsp; 
			# ENDIF #
			# IF C_MODO_AUTH # 
			<img src="../templates/{THEME}/images/admin/modo_mini.png" alt="" class="valign_middle" /> <a href="../member/moderation_panel.php" class="small_link">{L_MODO_PANEL}</a>&nbsp;
			# ENDIF #
			
			<img src="../templates/{THEME}/images/admin/home_mini.png" alt="" class="valign_middle" /> <a href="{U_DISCONNECT}" class="small_link">{L_DISCONNECT}</a>
			&nbsp;&nbsp;&nbsp;
		</p>
		# END ENDIF #
		