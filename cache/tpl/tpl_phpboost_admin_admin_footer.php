			<br /><br /><br />
		</div>
		<div id="footer">
			<span>
				<!-- This mention must figured on the website ! -->
				<!-- Cette mention dois figurer sur le site ! -->
				<?php echo isset($this->_var['L_POWERED_BY']) ? $this->_var['L_POWERED_BY'] : ''; ?> <a style="font-size:10px" href="http://www.phpboost.com" title="PHPBoost">PHPBoost 2</a> <?php echo isset($this->_var['L_PHPBOOST_RIGHT']) ? $this->_var['L_PHPBOOST_RIGHT'] : ''; ?>
			</span>	
			<?php if( isset($this->_var['C_DISPLAY_BENCH']) && $this->_var['C_DISPLAY_BENCH'] ) { ?>
			<br />
			<span>
				<?php echo isset($this->_var['L_ACHIEVED']) ? $this->_var['L_ACHIEVED'] : ''; echo ' '; echo isset($this->_var['BENCH']) ? $this->_var['BENCH'] : '';  echo isset($this->_var['L_UNIT_SECOND']) ? $this->_var['L_UNIT_SECOND'] : ''; ?> - <?php echo isset($this->_var['REQ']) ? $this->_var['REQ'] : ''; echo ' '; echo isset($this->_var['L_REQ']) ? $this->_var['L_REQ'] : ''; ?>
			</span>	
			<?php } ?>
			<?php if( isset($this->_var['C_DISPLAY_AUTHOR_THEME']) && $this->_var['C_DISPLAY_AUTHOR_THEME'] ) { ?>
			<br />
			<span>
				<?php echo isset($this->_var['L_THEME']) ? $this->_var['L_THEME'] : ''; echo ' '; echo isset($this->_var['L_THEME_NAME']) ? $this->_var['L_THEME_NAME'] : ''; echo ' '; echo isset($this->_var['L_BY']) ? $this->_var['L_BY'] : ''; ?> <a href="<?php echo isset($this->_var['U_THEME_AUTHOR_LINK']) ? $this->_var['U_THEME_AUTHOR_LINK'] : ''; ?>" style="font-size:10px;"><?php echo isset($this->_var['L_THEME_AUTHOR']) ? $this->_var['L_THEME_AUTHOR'] : ''; ?></a>
			</span>
			<?php } ?>
		</div>
	</body>
</html>
