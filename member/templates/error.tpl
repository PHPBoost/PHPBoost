		# IF C_ERRORH_CONNEXION #
		<script type="text/javascript">
		<!--
		function check_connect_error(){
			if(document.getElementById('login_error').value == "") {
				alert("{L_REQUIRE_PSEUDO}");
				return false;
			}
			if(document.getElementById('password_error').value == "") {
				alert("{L_REQUIRE_PASSWORD}");
				return false;
			}
			return true;
		}
		-->
		</script>
		
		<form action="" method="post" style="margin:auto;" onsubmit="return check_connect_error();">
			<div class="module_position">					
				<div class="module_top_l"></div>		
				<div class="module_top_r"></div>
				<div class="module_top"><strong>{L_CONNECT}</strong></div>
				<div class="module_contents" style="text-align:center;">
					# IF C_ERROR_HANDLER #
					<span id="errorh"></span>
					<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
						<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
						<br />	
					</div>
					<br />	
					# ENDIF #
					
					<p style="margin:2px;"><label>{L_PSEUDO} <input size="15" type="text" class="text" id="login_error" name="login" maxlength="25" /></label></p>
					<p style="margin:2px;"><label>{L_PASSWORD}	<input size="15" type="password" name="password" id="password_error" class="text" maxlength="30" /></label></p>
					<p style="margin:2px;"><label>{L_AUTOCONNECT} <input type="checkbox" name="auto" checked="checked" /></label></p>
					<p style="margin:5px;">
						<input type="submit" name="connect" value="{L_CONNECT}" class="submit" />
						<input type="hidden" name="token" value="{TOKEN}" />
					</p>
					
					<br />
					{U_REGISTER}
					<a href="../member/forget.php"><img src="../templates/{THEME}/images/forget_mini.png" alt="" class="valign_middle" />  {L_FORGOT_PASS}</a>	
				</div>
				<div class="module_bottom_l"></div>		
				<div class="module_bottom_r"></div>
				<div class="module_bottom"></div>
			</div>
		</form>	
		# ENDIF #

		
		# IF C_ERRORH #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top"><strong>{L_ERROR}</strong></div>
			<div class="module_contents">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
				</div>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"><strong>{U_BACK}</strong></div>
		</div>
		# ENDIF #
		