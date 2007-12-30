		<div class="forum_title"><?php echo isset($this->_var['FORUM_NAME']) ? $this->_var['FORUM_NAME'] : ''; ?></div>
		<div class="module_position" style="margin-bottom:15px;">
			<div class="row2">
				<span style="float:left;">
					&bull; <a href="index.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_FORUM_INDEX']) ? $this->_var['L_FORUM_INDEX'] : ''; ?></a>
				</span>
				<span style="float:right;">
					<?php echo isset($this->_var['U_SEARCH']) ? $this->_var['U_SEARCH'] : ''; ?>
					<?php echo isset($this->_var['U_TOPIC_TRACK']) ? $this->_var['U_TOPIC_TRACK'] : ''; ?>
					<?php echo isset($this->_var['U_LAST_MSG_READ']) ? $this->_var['U_LAST_MSG_READ'] : ''; ?>
					<?php echo isset($this->_var['U_MSG_NOT_READ']) ? $this->_var['U_MSG_NOT_READ'] : ''; ?>
					<?php echo isset($this->_var['U_MSG_SET_VIEW']) ? $this->_var['U_MSG_SET_VIEW'] : ''; ?>
				</span>&nbsp;
			</div>
		</div>
		