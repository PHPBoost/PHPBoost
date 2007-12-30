			<table class="module_table">
				<tr> 
					<th colspan="2">
						<?php echo isset($this->_var['L_INDEX_ADMIN']) ? $this->_var['L_INDEX_ADMIN'] : ''; ?>
					</th>
				</tr>
				<tr> 
					<td class="row1" style="text-align:center;">
						<br />			
						<?php echo isset($this->_var['L_TEXT_INDEX']) ? $this->_var['L_TEXT_INDEX'] : ''; ?>			
						<br /><br />						
						
						<div class="phpboost_news">
							<script type="text/javascript" src="http://www.phpboost.com/cache/rss_news.html"></script>
						</div>
						<br />
					</td>
				</tr>
			</table>

			<table class="module_table">
				<tr> 
					<th>
						<?php echo isset($this->_var['L_UPDATE_AVAILABLE']) ? $this->_var['L_UPDATE_AVAILABLE'] : ''; ?>
					</th>
				</tr>
				<tr>	
					<td class="row1<?php echo isset($this->_var['WARNING_CORE']) ? $this->_var['WARNING_CORE'] : ''; ?>" style="text-align:center">
						<?php echo isset($this->_var['UPDATE_AVAILABLE']) ? $this->_var['UPDATE_AVAILABLE'] : ''; echo ' '; echo isset($this->_var['L_CORE_UPDATE']) ? $this->_var['L_CORE_UPDATE'] : ''; ?>
					</td>
				</tr>
				<tr> 
					<td class="row1<?php echo isset($this->_var['WARNING_MODULES']) ? $this->_var['WARNING_MODULES'] : ''; ?>" style="text-align:center">
						<?php echo isset($this->_var['UPDATE_MODULES_AVAILABLE']) ? $this->_var['UPDATE_MODULES_AVAILABLE'] : ''; echo ' '; echo isset($this->_var['L_MODULES_UPDATE']) ? $this->_var['L_MODULES_UPDATE'] : ''; ?><br />
						<?php if( !isset($this->_block['modules_available']) || !is_array($this->_block['modules_available']) ) $this->_block['modules_available'] = array();
foreach($this->_block['modules_available'] as $modules_available_key => $modules_available_value) {
$_tmpb_modules_available = &$this->_block['modules_available'][$modules_available_key]; ?>
						<a href="http://www.phpboost.com/phpboost/modules.php?name=<?php echo isset($_tmpb_modules_available['ID']) ? $_tmpb_modules_available['ID'] : ''; ?>"><?php echo isset($_tmpb_modules_available['NAME']) ? $_tmpb_modules_available['NAME'] : ''; ?> <em>(<?php echo isset($_tmpb_modules_available['VERSION']) ? $_tmpb_modules_available['VERSION'] : ''; ?>)</em></a><br />
						<?php } ?>
					</td>
				</tr>	
			</table>
			
			<table class="module_table">
				<tr> 
					<th colspan="4">
						<?php echo isset($this->_var['L_USER_ONLINE']) ? $this->_var['L_USER_ONLINE'] : ''; ?>
					</th>
				</tr>	
				<tr> 
					<td class="row1" style="text-align: center;">
						<?php echo isset($this->_var['L_USER_ONLINE']) ? $this->_var['L_USER_ONLINE'] : ''; ?>
					</td>
					<td  class="row1" style="text-align: center;">
						<?php echo isset($this->_var['L_USER_IP']) ? $this->_var['L_USER_IP'] : ''; ?>
					</td>
					<td  class="row1" style="text-align: center;">
						<?php echo isset($this->_var['L_LOCALISATION']) ? $this->_var['L_LOCALISATION'] : ''; ?>
					</td>
					<td  class="row1" style="text-align: center;">
						<?php echo isset($this->_var['L_LAST_UPDATE']) ? $this->_var['L_LAST_UPDATE'] : ''; ?>
					</td>
				</tr>
				
				<?php if( !isset($this->_block['user']) || !is_array($this->_block['user']) ) $this->_block['user'] = array();
foreach($this->_block['user'] as $user_key => $user_value) {
$_tmpb_user = &$this->_block['user'][$user_key]; ?>
				<tr> 
					<td class="row1" style="text-align: center;">
						<?php echo isset($_tmpb_user['USER']) ? $_tmpb_user['USER'] : ''; ?>
					</td>
					<td class="row1" style="text-align: center;">
						<?php echo isset($_tmpb_user['USER_IP']) ? $_tmpb_user['USER_IP'] : ''; ?>
					</td>
					<td class="row1" style="text-align: center;">
						<?php echo isset($_tmpb_user['WHERE']) ? $_tmpb_user['WHERE'] : ''; ?>
					</td>
					<td class="row1" style="text-align: center;">
						<?php echo isset($this->_var['L_ON']) ? $this->_var['L_ON'] : ''; echo ' '; echo isset($_tmpb_user['TIME']) ? $_tmpb_user['TIME'] : ''; ?>
					</td>
					
				</tr>
				<?php } ?>

			</table>