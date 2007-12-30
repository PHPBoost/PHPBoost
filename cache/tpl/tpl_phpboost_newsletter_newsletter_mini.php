		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title"><?php echo isset($this->_var['L_NEWSLETTER']) ? $this->_var['L_NEWSLETTER'] : ''; ?></h5>
			</div>		
			<div class="module_mini_table">
				<form action="<?php echo isset($this->_var['ACTION']) ? $this->_var['ACTION'] : ''; ?>" method="post">
					<p>
					<input type="text" name="mail_newsletter" maxlength="50" size="18" class="text" value="<?php echo isset($this->_var['USER_MAIL']) ? $this->_var['USER_MAIL'] : ''; ?>" />
					</p>
					<p>
						<label><input type="radio" name="subscribe" value="subscribe" checked="checked" /> <?php echo isset($this->_var['SUBSCRIBE']) ? $this->_var['SUBSCRIBE'] : ''; ?></label>
						<br />
						<label><input type="radio" name="subscribe" value="unsubscribe" /> <?php echo isset($this->_var['UNSUBSCRIBE']) ? $this->_var['UNSUBSCRIBE'] : ''; ?></label>
					</p>
					<p>		
						<input type="submit" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />	
					</p>
					<p>
						<a href="<?php echo isset($this->_var['ARCHIVES_LINK']) ? $this->_var['ARCHIVES_LINK'] : ''; ?>" style=" font-size:10px;"><?php echo isset($this->_var['L_ARCHIVES']) ? $this->_var['L_ARCHIVES'] : ''; ?></a>
					</p>
				</form>		
			</div>
			<div class="module_mini_bottom_left">
			</div>
		</div>
		