		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_CACHE']) ? $this->_var['L_CACHE'] : ''; ?></li>
				<li>
					<a href="admin_cache.php"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/cache.png" alt="" /></a>
					<br />
					<a href="admin_cache.php" class="quick_link"><?php echo isset($this->_var['L_CACHE']) ? $this->_var['L_CACHE'] : ''; ?></a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			<?php if( !isset($this->_block['error_handler']) || !is_array($this->_block['error_handler']) ) $this->_block['error_handler'] = array();
foreach($this->_block['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$this->_block['error_handler'][$error_handler_key]; ?>
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
					<br />	
				</div>	
			</div>
			<?php } ?>
			
			<form action="admin_cache.php" method="post" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_CACHE']) ? $this->_var['L_CACHE'] : ''; ?></legend>
					<p>
						<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/cache.png" alt="" class="img_left" />
						<?php echo isset($this->_var['L_EXPLAIN_SITE_CACHE']) ? $this->_var['L_EXPLAIN_SITE_CACHE'] : ''; ?>
						<br /><br />
					</p>
				</fieldset>
				<fieldset class="fieldset_submit">
					<legend><?php echo isset($this->_var['L_GENERATE']) ? $this->_var['L_GENERATE'] : ''; ?></legend>
					<input type="submit" name="cache" value="<?php echo isset($this->_var['L_GENERATE']) ? $this->_var['L_GENERATE'] : ''; ?>" class="submit" />		
				</fieldset>	
			</form>
		</div>
		