		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_MODULES_MANAGEMENT']) ? $this->_var['L_MODULES_MANAGEMENT'] : ''; ?></li>
				<li>
					<a href="admin_modules.php"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="admin_modules.php" class="quick_link"><?php echo isset($this->_var['L_MODULES_MANAGEMENT']) ? $this->_var['L_MODULES_MANAGEMENT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_modules_add.php"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="admin_modules_add.php" class="quick_link"><?php echo isset($this->_var['L_ADD_MODULES']) ? $this->_var['L_ADD_MODULES'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_modules_update.php"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/modules.png" alt="" /></a>
					<br />
					<a href="admin_modules_update.php" class="quick_link"><?php echo isset($this->_var['L_UPDATE_MODULES']) ? $this->_var['L_UPDATE_MODULES'] : ''; ?></a>
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
			
			<form action="" method="post" enctype="multipart/form-data" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_UPLOAD_MODULE']) ? $this->_var['L_UPLOAD_MODULE'] : ''; ?></legend>
					<dl>
						<dt><label for="upload_module"><?php echo isset($this->_var['L_EXPLAIN_ARCHIVE_UPLOAD']) ? $this->_var['L_EXPLAIN_ARCHIVE_UPLOAD'] : ''; ?></label></dt>
						<dd><label><input type="file" name="upload_module" id="upload_module" size="30" class="submit" />
						<input type="hidden" name="max_file_size" value="2000000" /></label></dd>
					</dl>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend><?php echo isset($this->_var['L_UPLOAD']) ? $this->_var['L_UPLOAD'] : ''; ?></legend>
					<input type="submit" value="<?php echo isset($this->_var['L_UPLOAD']) ? $this->_var['L_UPLOAD'] : ''; ?>" class="submit" />				
				</fieldset>	
			</form>
			
			<table class="module_table">
				<tr> 
					<th>
						<?php echo isset($this->_var['L_UPDATE_AVAILABLE']) ? $this->_var['L_UPDATE_AVAILABLE'] : ''; ?>
					</th>
				</tr>
				<tr> 
					<td class="row1<?php echo isset($this->_var['WARNING_MODULES']) ? $this->_var['WARNING_MODULES'] : ''; ?>" style="text-align:center">
						<?php echo isset($this->_var['UPDATE_MODULES_AVAILABLE']) ? $this->_var['UPDATE_MODULES_AVAILABLE'] : ''; echo ' '; echo isset($this->_var['L_MODULES_UPDATE']) ? $this->_var['L_MODULES_UPDATE'] : ''; ?><br />
						<?php if( !isset($this->_block['update_modules_available']) || !is_array($this->_block['update_modules_available']) ) $this->_block['update_modules_available'] = array();
foreach($this->_block['update_modules_available'] as $update_modules_available_key => $update_modules_available_value) {
$_tmpb_update_modules_available = &$this->_block['update_modules_available'][$update_modules_available_key]; ?>
						<a href="http://www.phpboost.com/phpboost/modules.php?name=<?php echo isset($_tmpb_update_modules_available['ID']) ? $_tmpb_update_modules_available['ID'] : ''; ?>"><?php echo isset($_tmpb_update_modules_available['NAME']) ? $_tmpb_update_modules_available['NAME'] : ''; ?> <em>(<?php echo isset($_tmpb_update_modules_available['VERSION']) ? $_tmpb_update_modules_available['VERSION'] : ''; ?>)</em></a><br />
						<?php } ?>
					</td>
				</tr>	
			</table>
				
			<br /><br />		
			
			<form action="admin_modules_update.php?update=1" method="post">
				<table class="module_table">
					<tr> 
						<th colspan="6">
							<?php echo isset($this->_var['L_MODULES_AVAILABLE']) ? $this->_var['L_MODULES_AVAILABLE'] : ''; ?>
						</th>
					</tr>
					<?php if( !isset($this->_block['modules_available']) || !is_array($this->_block['modules_available']) ) $this->_block['modules_available'] = array();
foreach($this->_block['modules_available'] as $modules_available_key => $modules_available_value) {
$_tmpb_modules_available = &$this->_block['modules_available'][$modules_available_key]; ?>
					<tr>
						<td class="row2" style="width:160px">
							<?php echo isset($this->_var['L_NAME']) ? $this->_var['L_NAME'] : ''; ?>
						</td>
						<td class="row2" style="width:140px;text-align:center;">
							<?php echo isset($this->_var['L_NEW_VERSION']) ? $this->_var['L_NEW_VERSION'] : ''; ?>
						</td>
						<td class="row2" style="width:140px;text-align:center;">
							<?php echo isset($this->_var['L_INSTALLED_VERSION']) ? $this->_var['L_INSTALLED_VERSION'] : ''; ?>
						</td>
						<td class="row2">
							<?php echo isset($this->_var['L_DESC']) ? $this->_var['L_DESC'] : ''; ?>
						</td>
						<td class="row2" style="width:100px">
							<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>
						</td>
					</tr>
					<?php } ?>
					<?php if( !isset($this->_block['no_module']) || !is_array($this->_block['no_module']) ) $this->_block['no_module'] = array();
foreach($this->_block['no_module'] as $no_module_key => $no_module_value) {
$_tmpb_no_module = &$this->_block['no_module'][$no_module_key]; ?>
					<tr>
						<td class="row2" colspan="4" style="text-align:center;">
							<strong><?php echo isset($this->_var['L_NO_MODULES_AVAILABLE']) ? $this->_var['L_NO_MODULES_AVAILABLE'] : ''; ?></strong>
						</td>
					</tr>
					<?php } ?>
					
					
					<?php if( !isset($this->_block['available']) || !is_array($this->_block['available']) ) $this->_block['available'] = array();
foreach($this->_block['available'] as $available_key => $available_value) {
$_tmpb_available = &$this->_block['available'][$available_key]; ?>
					<tr> 	
						<td class="row2">					
							<img style="vertical-align:middle;" src="../<?php echo isset($_tmpb_available['ICON']) ? $_tmpb_available['ICON'] : ''; ?>/<?php echo isset($_tmpb_available['ICON']) ? $_tmpb_available['ICON'] : ''; ?>.png" alt="" /> <strong><?php echo isset($_tmpb_available['NAME']) ? $_tmpb_available['NAME'] : ''; ?></strong>
						</td>
						<td class="row2" style="text-align:center;">					
							<strong><?php echo isset($_tmpb_available['VERSION']) ? $_tmpb_available['VERSION'] : ''; ?></strong>
						</td>
						<td class="row2" style="text-align:center;">					
							<strong><?php echo isset($_tmpb_available['PREVIOUS_VERSION']) ? $_tmpb_available['PREVIOUS_VERSION'] : ''; ?></strong>
						</td>
						<td class="row2">	
							<strong><?php echo isset($this->_var['L_AUTHOR']) ? $this->_var['L_AUTHOR'] : ''; ?>:</strong> <?php echo isset($_tmpb_available['AUTHOR']) ? $_tmpb_available['AUTHOR'] : ''; echo ' '; echo isset($_tmpb_available['AUTHOR_WEBSITE']) ? $_tmpb_available['AUTHOR_WEBSITE'] : ''; ?><br />
							<strong><?php echo isset($this->_var['L_DESC']) ? $this->_var['L_DESC'] : ''; ?>:</strong> <?php echo isset($_tmpb_available['DESC']) ? $_tmpb_available['DESC'] : ''; ?><br />
							<strong><?php echo isset($this->_var['L_COMPAT']) ? $this->_var['L_COMPAT'] : ''; ?>:</strong> PHPBoost <?php echo isset($_tmpb_available['COMPAT']) ? $_tmpb_available['COMPAT'] : ''; ?><br />
							<strong><?php echo isset($this->_var['L_USE_SQL']) ? $this->_var['L_USE_SQL'] : ''; ?>:</strong> <?php echo isset($_tmpb_available['USE_SQL']) ? $_tmpb_available['USE_SQL'] : ''; ?> <em><?php echo isset($_tmpb_available['SQL_TABLE']) ? $_tmpb_available['SQL_TABLE'] : ''; ?></em><br />
							<strong><?php echo isset($this->_var['L_USE_CACHE']) ? $this->_var['L_USE_CACHE'] : ''; ?>:</strong> <?php echo isset($_tmpb_available['USE_CACHE']) ? $_tmpb_available['USE_CACHE'] : ''; ?><br />
							<strong><?php echo isset($this->_var['L_ALTERNATIVE_CSS']) ? $this->_var['L_ALTERNATIVE_CSS'] : ''; ?>:</strong> <?php echo isset($_tmpb_available['ALTERNATIVE_CSS']) ? $_tmpb_available['ALTERNATIVE_CSS'] : ''; ?><br />
						</td>
						<td class="row2" style="text-align:center;">	
							<input type="submit" name="<?php echo isset($_tmpb_available['ID']) ? $_tmpb_available['ID'] : ''; ?>" value="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" class="submit" />
						</td>
					</tr>						
					<?php } ?>
				</table>			
			</form>
		</div>
		