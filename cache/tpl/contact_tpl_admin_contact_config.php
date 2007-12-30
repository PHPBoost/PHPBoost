		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_CONTACT']) ? $this->_var['L_CONTACT'] : ''; ?></li>
				<li>
					<a href="admin_contact.php"><img src="contact.png" alt="" /></a>
					<br />
					<a href="admin_contact.php" class="quick_link"><?php echo isset($this->_var['L_CONTACT_CONFIG']) ? $this->_var['L_CONTACT_CONFIG'] : ''; ?></a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
		
			<form action="admin_contact.php" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_CONTACT_CONFIG']) ? $this->_var['L_CONTACT_CONFIG'] : ''; ?></legend>
					<dl>
						<dt><label for="contact_verifcode"><?php echo isset($this->_var['L_CONTACT_VERIFCODE']) ? $this->_var['L_CONTACT_VERIFCODE'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_CONTACT_VERIFCODE_EXPLAIN']) ? $this->_var['L_CONTACT_VERIFCODE_EXPLAIN'] : ''; ?></span></dt>
						<dd>
							<label><input type="radio" <?php echo isset($this->_var['CONTACT_VERIFCODE_ENABLED']) ? $this->_var['CONTACT_VERIFCODE_ENABLED'] : ''; ?> name="contact_verifcode" id="contact_verifcode" value="1" />	<?php echo isset($this->_var['L_YES']) ? $this->_var['L_YES'] : ''; ?></label>
							&nbsp;&nbsp; 
							<label><input type="radio" <?php echo isset($this->_var['CONTACT_VERIFCODE_DISABLED']) ? $this->_var['CONTACT_VERIFCODE_DISABLED'] : ''; ?> name="contact_verifcode" value="0" /> <?php echo isset($this->_var['L_NO']) ? $this->_var['L_NO'] : ''; ?></label>
						</dd>
					</dl>
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend><?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?></legend>
					<input type="submit" name="valid" value="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" class="submit" />
					&nbsp;
					<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />			
				</fieldset>
			</form>
		</div>
		