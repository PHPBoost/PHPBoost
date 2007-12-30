		<?php if( isset($this->_var['C_DISCONNECTED']) && $this->_var['C_DISCONNECTED'] ) { ?>		
		<script type="text/javascript">
		<!--
		function check_conect(){
			if(document.getElementById('login').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_PSEUDO']) ? $this->_var['L_REQUIRE_PSEUDO'] : ''; ?>");
				return false;
			}
			if(document.getElementById('password').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_PASSWORD']) ? $this->_var['L_REQUIRE_PASSWORD'] : ''; ?>");
				return false;
			}
			return true;
		}
		
		-->
		</script>
				
		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title"><?php echo isset($this->_var['L_CONNECT']) ? $this->_var['L_CONNECT'] : ''; ?></h5>
			</div>
			<div class="module_mini_table">
				<form action="<?php echo isset($this->_var['U_CONNECT']) ? $this->_var['U_CONNECT'] : ''; ?>" method="post" onsubmit="return check_conect();">
					<p>
						<label><?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?>
						<br />
						<input size="15" type="text" class="text" id="login" name="login" maxlength="25" /></label>
						<br />
						<label><?php echo isset($this->_var['L_PASSWORD']) ? $this->_var['L_PASSWORD'] : ''; ?>
						<br />
						<input size="15" type="password" id="password" name="password" class="text" maxlength="30" /></label>
						<br />
						<label><?php echo isset($this->_var['L_AUTOCONNECT']) ? $this->_var['L_AUTOCONNECT'] : ''; ?> <input checked="checked" type="checkbox" name="auto" /></label>
					</p>
					<p>	
						<input type="submit" name="connect" value="<?php echo isset($this->_var['L_CONNECT']) ? $this->_var['L_CONNECT'] : ''; ?>" class="submit" />
					</p>
					<p>
						<?php echo isset($this->_var['U_REGISTER']) ? $this->_var['U_REGISTER'] : ''; ?>
					</p>	
				</form>	
			</div>		
			<div class="module_mini_bottom_left">
			</div>
		</div>				
		<?php } ?>		
		
		
		<?php if( isset($this->_var['C_CONNECTED']) && $this->_var['C_CONNECTED'] ) { ?>		
		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title"><?php echo isset($this->_var['L_PROFIL']) ? $this->_var['L_PROFIL'] : ''; ?></h5>
			</div>
			<div class="module_mini_table" style="text-align:left;">
				<ul style="margin:0;padding:0;padding-left:4px;list-style-type:none;line-height:18px">
					<li><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/members_mini.png" alt="" class="valign_middle"> <a href="../member/member<?php echo isset($this->_var['U_MEMBER_ID']) ? $this->_var['U_MEMBER_ID'] : ''; ?>" class="small_link"><?php echo isset($this->_var['L_PRIVATE_PROFIL']) ? $this->_var['L_PRIVATE_PROFIL'] : ''; ?></a></li>
					<li><?php echo isset($this->_var['U_MEMBER_MP']) ? $this->_var['U_MEMBER_MP'] : ''; ?></li>
					<?php echo isset($this->_var['U_ADMIN']) ? $this->_var['U_ADMIN'] : ''; ?>
					<?php echo isset($this->_var['U_MODO']) ? $this->_var['U_MODO'] : ''; ?>
					<li><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/home_mini.png" alt="" class="valign_middle"> <a href="../member/member.php?disconnect=true" class="small_link"><?php echo isset($this->_var['L_DISCONNECT']) ? $this->_var['L_DISCONNECT'] : ''; ?></a></li>
				</ul>
			</div>
			<div class="module_mini_bottom_left">
			</div>
		</div>
		<?php } ?>
		