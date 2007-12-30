	<?php if( isset($this->_var['C_END_RIGHT']) && $this->_var['C_END_RIGHT'] ) { ?>
	</div>
	<?php } ?>
	
	<div id="main">
		<div id="links">
			&nbsp;&nbsp;<a class="small_link" href="<?php echo isset($this->_var['START_PAGE']) ? $this->_var['START_PAGE'] : ''; ?>" title="<?php echo isset($this->_var['L_INDEX']) ? $this->_var['L_INDEX'] : ''; ?>"><?php echo isset($this->_var['L_INDEX']) ? $this->_var['L_INDEX'] : ''; ?></a>
			<?php if( !isset($this->_block['link_speed_bar']) || !is_array($this->_block['link_speed_bar']) ) $this->_block['link_speed_bar'] = array();
foreach($this->_block['link_speed_bar'] as $link_speed_bar_key => $link_speed_bar_value) {
$_tmpb_link_speed_bar = &$this->_block['link_speed_bar'][$link_speed_bar_key]; ?>
			&raquo; <a class="small_link" href="<?php echo isset($_tmpb_link_speed_bar['URL']) ? $_tmpb_link_speed_bar['URL'] : ''; ?>" title="<?php echo isset($_tmpb_link_speed_bar['TITLE']) ? $_tmpb_link_speed_bar['TITLE'] : ''; ?>"><?php echo isset($_tmpb_link_speed_bar['TITLE']) ? $_tmpb_link_speed_bar['TITLE'] : ''; ?></a>
			<?php } ?>			
		</div>		
		<br />