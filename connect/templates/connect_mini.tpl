		# IF C_DISCONNECTED #		
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
				
		<form action="{U_CONNECT}" method="post" onsubmit="return check_conect();">
		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_CONNECT}</h5>
			</div>
			<div class="module_mini_table">
				<p>
					<label>{L_PSEUDO}
					<br />
					<input size="15" type="text" class="text" id="login" name="login" maxlength="25" /></label>
					<br />
					<label>{L_PASSWORD}
					<br />
					<input size="15" type="password" id="password" name="password" class="text" maxlength="30" /></label>
					<br />
					<label>{L_AUTOCONNECT} <input checked="checked" type="checkbox" name="auto" /></label>
				</p>
				<p>	
					<input type="submit" name="connect" value="{L_CONNECT}" class="submit" />
				</p>
				<p>
					{U_REGISTER}
				</p>&nbsp;
			</div>		
			<div class="module_mini_bottom">
			</div>
		</div>				
		</form>	
		# ENDIF #		
		
		
		# IF C_CONNECTED #		
		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_PROFIL}</h5>
			</div>
			<div class="module_mini_table" style="text-align:left;">
				<ul style="margin:0;padding:0;padding-left:4px;list-style-type:none;line-height:18px">
					<li><img src="../templates/{THEME}/images/admin/members_mini.png" alt="" class="valign_middle" /> <a href="../member/member{U_MEMBER_ID}" class="small_link">{L_PRIVATE_PROFIL}</a></li>
					<li>{U_MEMBER_MP}</li>
					{U_ADMIN}
					{U_MODO}
					<li><img src="../templates/{THEME}/images/admin/home_mini.png" alt="" class="valign_middle" /> <a href="../member/member.php?disconnect=true" class="small_link">{L_DISCONNECT}</a></li>
				</ul>
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
		# END ENDIF #
		