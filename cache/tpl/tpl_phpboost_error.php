		<?php if( !isset($this->_block['connexion']) || !is_array($this->_block['connexion']) ) $this->_block['connexion'] = array();
foreach($this->_block['connexion'] as $connexion_key => $connexion_value) {
$_tmpb_connexion = &$this->_block['connexion'][$connexion_key]; ?>
		<script type="text/javascript">
		<!--
		function check_conect_error(){
			if(document.getElementById('login_error').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_PSEUDO']) ? $this->_var['L_REQUIRE_PSEUDO'] : ''; ?>");
				return false;
			}
			if(document.getElementById('password_error').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_PASSWORD']) ? $this->_var['L_REQUIRE_PASSWORD'] : ''; ?>");
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
				<div class="module_top"><strong><?php echo isset($this->_var['L_CONNECT']) ? $this->_var['L_CONNECT'] : ''; ?></strong></div>
				<div class="module_contents" style="text-align:center;">
					<?php if( !isset($_tmpb_connexion['error_handler']) || !is_array($_tmpb_connexion['error_handler']) ) $_tmpb_connexion['error_handler'] = array();
foreach($_tmpb_connexion['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$_tmpb_connexion['error_handler'][$error_handler_key]; ?>
					<span id="errorh"></span>
					<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
						<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
						<br />	
					</div>
					<br />	
					<?php } ?>
					
					<label><?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?>
					<input size="15" type="text" class="text" id="login_error" name="login" maxlength="25" /></label>
					<br />
					<label><?php echo isset($this->_var['L_PASSWORD']) ? $this->_var['L_PASSWORD'] : ''; ?>
					<input size="15" type="password" name="password" id="password_error" class="text" maxlength="30" /></label>
					<br />
					<label><?php echo isset($this->_var['L_AUTOCONNECT']) ? $this->_var['L_AUTOCONNECT'] : ''; ?> <input type="checkbox" name="auto" checked="checked" />
					<br /><br />
					<input type="submit" name="connect" value="<?php echo isset($this->_var['L_CONNECT']) ? $this->_var['L_CONNECT'] : ''; ?>" class="submit" /></label>
					
					<br /><br />
					<?php echo isset($this->_var['U_REGISTER']) ? $this->_var['U_REGISTER'] : ''; ?>
					<a href="../member/forget.php"><?php echo isset($this->_var['L_FORGOT_PASS']) ? $this->_var['L_FORGOT_PASS'] : ''; ?></a>	
				</div>
				<div class="module_bottom_l"></div>		
				<div class="module_bottom_r"></div>
				<div class="module_bottom"></div>
			</div>
		</form>	
		<?php } ?>

		<?php if( !isset($this->_block['error']) || !is_array($this->_block['error']) ) $this->_block['error'] = array();
foreach($this->_block['error'] as $error_key => $error_value) {
$_tmpb_error = &$this->_block['error'][$error_key]; ?>
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top"><strong><?php echo isset($this->_var['L_ERROR']) ? $this->_var['L_ERROR'] : ''; ?></strong></div>
			<div class="module_contents">
				<span id="errorh"></span>
					<div class="<?php echo isset($_tmpb_error['CLASS']) ? $_tmpb_error['CLASS'] : ''; ?>">
						<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error['IMG']) ? $_tmpb_error['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <strong><?php echo isset($_tmpb_error['L_ERROR']) ? $_tmpb_error['L_ERROR'] : ''; ?></strong>
					</div>
					
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"><strong><?php echo isset($this->_var['U_BACK']) ? $this->_var['U_BACK'] : ''; ?></strong></div>
		</div>
		
		<?php } ?>
		