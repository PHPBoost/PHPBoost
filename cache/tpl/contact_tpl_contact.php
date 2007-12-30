		<script type="text/javascript">
		<!--
		function check_form_mail(){
			if(document.getElementById('mail_email').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_MAIL']) ? $this->_var['L_REQUIRE_MAIL'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('mail_contents').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_TEXT']) ? $this->_var['L_REQUIRE_TEXT'] : ''; ?>");
				return false;
		    }
			<?php echo isset($this->_var['L_REQUIRE_VERIF_CODE']) ? $this->_var['L_REQUIRE_VERIF_CODE'] : ''; ?>
			return true;
		}
		
		function refresh_img()
		{
			if ( typeof this.img_id == 'undefined' )
				this.img_id = 0;
			else
				this.img_id++;
			
			var xhr_object = null;
			var data = null;
			var filename = "../member/verif_code.php";
			
			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non supporté par le navigateur
			    return;

			data = "new=1";
			xhr_object.open("POST", filename, true);					
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 ) 
				{					
					document.getElementById('verif_code_img').src = '../member/verif_code.php?new=' + img_id;	
				}
			}

			xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr_object.send(data);
		}
		-->
		</script>

		<?php if( !isset($this->_block['error_handler']) || !is_array($this->_block['error_handler']) ) $this->_block['error_handler'] = array();
foreach($this->_block['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$this->_block['error_handler'][$error_handler_key]; ?>
		<span id="errorh"></span>
		<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
			<br />	
		</div>
		<?php } ?>
				
		<form action="contact.php<?php echo isset($this->_var['U_ACTION_CONTACT']) ? $this->_var['U_ACTION_CONTACT'] : ''; ?>" method="post" onsubmit="return check_form_mail();" class="fieldset_mini">
			<fieldset>
				<legend><?php echo isset($this->_var['L_CONTACT_MAIL']) ? $this->_var['L_CONTACT_MAIL'] : ''; ?></legend>
				<p><?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?></p>
				<dl>
					<dt><label for="mail_email">* <?php echo isset($this->_var['L_MAIL']) ? $this->_var['L_MAIL'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_VALID_MAIL']) ? $this->_var['L_VALID_MAIL'] : ''; ?></span></dt>
					<dd><label><input type="text" size="30" maxlength="50" id="mail_email" name="mail_email" value="<?php echo isset($this->_var['MAIL']) ? $this->_var['MAIL'] : ''; ?>" class="text" /></label></dd>
				</dl>		
				<dl>
					<dt><label for="mail_objet"><?php echo isset($this->_var['L_OBJET']) ? $this->_var['L_OBJET'] : ''; ?></label></dt>
					<dd><label><input type="text" size="30" name="mail_objet" id="mail_objet" class="text" /></label></dd>
				</dl>
				<?php if( !isset($this->_block['verif_code']) || !is_array($this->_block['verif_code']) ) $this->_block['verif_code'] = array();
foreach($this->_block['verif_code'] as $verif_code_key => $verif_code_value) {
$_tmpb_verif_code = &$this->_block['verif_code'][$verif_code_key]; ?>
				<dl>
					<dt><label for="verif_code">* <?php echo isset($this->_var['L_VERIF_CODE']) ? $this->_var['L_VERIF_CODE'] : ''; ?></label></dt>
					<dd><label>
						<img src="../member/verif_code.php" id="verif_code_img" alt="" style="padding:2px;" />
						<br />
						<input size="30" type="text" class="text" name="verif_code" id="verif_code" />
						<a href="javascript:refresh_img()"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/refresh.png" alt="" class="valign_middle" /></a>
					</label></dd>			
				</dl>
				<?php } ?>
				<label for="mail_contents">* <?php echo isset($this->_var['L_CONTENTS']) ? $this->_var['L_CONTENTS'] : ''; ?></label>
				<label><textarea rows="10" cols="47" id="mail_contents" name="mail_contents"></textarea></label>
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
				<input type="submit" name="mail_valid" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
				&nbsp;
				<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />			
			</fieldset>
		</form>
