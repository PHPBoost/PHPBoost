		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title"><?php echo isset($this->_var['L_STATS']) ? $this->_var['L_STATS'] : ''; ?></h5>
			</div>
			<div class="module_mini_table">
				<p>
					<?php echo isset($this->_var['L_MEMBER_REGISTERED']) ? $this->_var['L_MEMBER_REGISTERED'] : ''; ?>
				</p>	
				<p>
					<?php echo isset($this->_var['L_LAST_REGISTERED_MEMBER']) ? $this->_var['L_LAST_REGISTERED_MEMBER'] : ''; ?>:
					<br /> 
					<?php echo isset($this->_var['U_LINK_LAST_MEMBER']) ? $this->_var['U_LINK_LAST_MEMBER'] : ''; ?>
				</p>	
				<p>
					<a href="../stats/stats.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>" title="<?php echo isset($this->_var['L_MORE_STAT']) ? $this->_var['L_MORE_STAT'] : ''; ?>" class="small_link"><?php echo isset($this->_var['L_MORE_STAT']) ? $this->_var['L_MORE_STAT'] : ''; ?></a>
				</p>	
			</div>
			<div class="module_mini_bottom_left">
			</div>
		</div>