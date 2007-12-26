		# START connexion #
		<script type="text/javascript">
		<!--
		function check_conect_error(){
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
							
		<form action="" method="post" style="margin:auto;" onsubmit="return check_conect_error();">
			<div class="module_position">					
				<div class="module_top_l"></div>		
				<div class="module_top_r"></div>
				<div class="module_top"><strong>{L_CONNECT}</strong></div>
				<div class="module_contents" style="text-align:center;">
					# START error_handler #
					<span id="errorh"></span>
					<div class="{connexion.error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
						<img src="../templates/{THEME}/images/{connexion.error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {connexion.error_handler.L_ERROR}
						<br />	
					</div>
					<br />	
					# END error_handler #
					
					<label>{L_PSEUDO}
					<input size="15" type="text" class="text" id="login_error" name="login" maxlength="25" /></label>
					<br />
					<label>{L_PASSWORD}
					<input size="15" type="password" name="password" id="password_error" class="text" maxlength="30" /></label>
					<br />
					<label>{L_AUTOCONNECT} <input type="checkbox" name="auto" checked="checked" />
					<br /><br />
					<input type="submit" name="connect" value="{L_CONNECT}" class="submit" /></label>
					
					<br /><br />
					{U_REGISTER}
					<a href="../member/forget.php">{L_FORGOT_PASS}</a>	
				</div>
				<div class="module_bottom_l"></div>		
				<div class="module_bottom_r"></div>
				<div class="module_bottom"></div>
			</div>
		</form>	
		# END connexion #

		# START error #
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top"><strong>{L_ERROR}</strong></div>
			<div class="module_contents">
				<span id="errorh"></span>
					<div class="{error.CLASS}">
						<img src="../templates/{THEME}/images/{error.IMG}.png" alt="" style="float:left;padding-right:6px;" /> <strong>{error.L_ERROR}</strong>
					</div>
					
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"><strong>{U_BACK}</strong></div>
		</div>
		
		# END error #
		