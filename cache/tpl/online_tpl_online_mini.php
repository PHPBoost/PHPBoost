		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title"><?php echo isset($this->_var['L_ONLINE']) ? $this->_var['L_ONLINE'] : ''; ?></h5>
			</div>
			<div class="module_mini_table">
				<span class="text_small"><?php echo isset($this->_var['VISIT']) ? $this->_var['VISIT'] : ''; echo ' '; echo isset($this->_var['L_VISITOR']) ? $this->_var['L_VISITOR'] : ''; ?>, <?php echo isset($this->_var['MEMBER']) ? $this->_var['MEMBER'] : ''; echo ' '; echo isset($this->_var['L_MEMBER']) ? $this->_var['L_MEMBER'] : ''; ?>, <?php echo isset($this->_var['MODO']) ? $this->_var['MODO'] : ''; echo ' '; echo isset($this->_var['L_MODO']) ? $this->_var['L_MODO'] : ''; ?>, <?php echo isset($this->_var['ADMIN']) ? $this->_var['ADMIN'] : ''; echo ' '; echo isset($this->_var['L_ADMIN']) ? $this->_var['L_ADMIN'] : ''; echo ' '; echo isset($this->_var['L_ONLINE']) ? $this->_var['L_ONLINE'] : ''; ?>.</span>				
				<br /><br />					
				<?php if( !isset($this->_block['online']) || !is_array($this->_block['online']) ) $this->_block['online'] = array();
foreach($this->_block['online'] as $online_key => $online_value) {
$_tmpb_online = &$this->_block['online'][$online_key]; ?>						
					<?php echo isset($_tmpb_online['MEMBER']) ? $_tmpb_online['MEMBER'] : ''; ?>												
				<?php } ?>
				<?php echo isset($this->_var['MORE']) ? $this->_var['MORE'] : ''; ?>					
				
				<div class="text_small" style="text-align:left;padding-left: 8px;padding-top: 10px;">
					Total: <?php echo isset($this->_var['TOTAL']) ? $this->_var['TOTAL'] : ''; ?>
				</div>
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
		