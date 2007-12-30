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
		
			<?php if( !isset($this->_block['main']) || !is_array($this->_block['main']) ) $this->_block['main'] = array();
foreach($this->_block['main'] as $main_key => $main_value) {
$_tmpb_main = &$this->_block['main'][$main_key]; ?>	
				<script type="text/javascript">
				<!--
					function check_select_multiple(id, status)
					{
						var i;		
						for(i = -1; i <= 2; i++)
						{
							if( document.getElementById(id + 'r' + i) )
								document.getElementById(id + 'r' + i).selected = status;
						}				
						document.getElementById(id + 'r3').selected = true;
						
						for(i = 0; i < <?php echo isset($_tmpb_main['NBR_GROUP']) ? $_tmpb_main['NBR_GROUP'] : ''; ?>; i++)
						{	
							if( document.getElementById(id + 'g' + i) )
								document.getElementById(id + 'g' + i).selected = status;		
						}	
					}
					function check_select_multiple_ranks(id, start)
					{
						var i;				
						for(i = start; i <= 2; i++)
						{	
							if( document.getElementById(id + i) )
								document.getElementById(id + i).selected = true;			
						}
					}
				-->
				</script>
				<form action="admin_modules.php?uninstall=1" method="post">
					<table class="module_table">
						<tr> 
							<th colspan="5">
								<?php echo isset($this->_var['L_MODULES_INSTALLED']) ? $this->_var['L_MODULES_INSTALLED'] : ''; ?>
							</th>
						</tr>
						<?php if( !isset($_tmpb_main['error_handler']) || !is_array($_tmpb_main['error_handler']) ) $_tmpb_main['error_handler'] = array();
foreach($_tmpb_main['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$_tmpb_main['error_handler'][$error_handler_key]; ?>
						<tr> 
							<td class="row2" colspan="5" style="text-align:center;">
								<span id="errorh"></span>
								<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
									<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
									<br />	
								</div>
								<br />		
							</td>
						</tr>
						<?php } ?>
					
						<?php if( !isset($_tmpb_main['modules_installed']) || !is_array($_tmpb_main['modules_installed']) ) $_tmpb_main['modules_installed'] = array();
foreach($_tmpb_main['modules_installed'] as $modules_installed_key => $modules_installed_value) {
$_tmpb_modules_installed = &$_tmpb_main['modules_installed'][$modules_installed_key]; ?>
						<tr>
							<td class="row2" style="width:150px;text-align:center;">
								<?php echo isset($this->_var['L_NAME']) ? $this->_var['L_NAME'] : ''; ?>
							</td>
							<td class="row2" style="text-align:center;">
								<?php echo isset($this->_var['L_DESC']) ? $this->_var['L_DESC'] : ''; ?>
							</td>
							<td class="row2" style="width:100px;text-align:center;">
								<?php echo isset($this->_var['L_ACTIV']) ? $this->_var['L_ACTIV'] : ''; ?>
							</td>
							<td class="row2" style="width:270px;text-align:center;">
								<?php echo isset($this->_var['L_AUTH_ACCESS']) ? $this->_var['L_AUTH_ACCESS'] : ''; ?>
							</td>
							<td class="row2" style="width:80px;text-align:center;">
								<?php echo isset($this->_var['L_UNINSTALL']) ? $this->_var['L_UNINSTALL'] : ''; ?>
							</td>
						</tr>
						<?php } ?>
						<?php if( !isset($_tmpb_main['no_module_installed']) || !is_array($_tmpb_main['no_module_installed']) ) $_tmpb_main['no_module_installed'] = array();
foreach($_tmpb_main['no_module_installed'] as $no_module_installed_key => $no_module_installed_value) {
$_tmpb_no_module_installed = &$_tmpb_main['no_module_installed'][$no_module_installed_key]; ?>
						<tr>
							<td class="row2" colspan="4" style="text-align:center;">
								<strong><?php echo isset($this->_var['L_NO_MODULES_INSTALLED']) ? $this->_var['L_NO_MODULES_INSTALLED'] : ''; ?></strong>
							</td>
						</tr>
						<?php } ?>
						

						<?php if( !isset($_tmpb_main['installed']) || !is_array($_tmpb_main['installed']) ) $_tmpb_main['installed'] = array();
foreach($_tmpb_main['installed'] as $installed_key => $installed_value) {
$_tmpb_installed = &$_tmpb_main['installed'][$installed_key]; ?>
						<tr> 	
							<td class="row2">					
								<span id="m<?php echo isset($_tmpb_installed['ID']) ? $_tmpb_installed['ID'] : ''; ?>"></span>
								<img style="vertical-align:middle;" src="../<?php echo isset($_tmpb_installed['ICON']) ? $_tmpb_installed['ICON'] : ''; ?>/<?php echo isset($_tmpb_installed['ICON']) ? $_tmpb_installed['ICON'] : ''; ?>.png" alt="" /> <strong><?php echo isset($_tmpb_installed['NAME']) ? $_tmpb_installed['NAME'] : ''; ?></strong> <em>(<?php echo isset($_tmpb_installed['VERSION']) ? $_tmpb_installed['VERSION'] : ''; ?>)</em>
							</td>
							<td class="row2">	
								<strong><?php echo isset($this->_var['L_AUTHOR']) ? $this->_var['L_AUTHOR'] : ''; ?>:</strong> <?php echo isset($_tmpb_installed['AUTHOR']) ? $_tmpb_installed['AUTHOR'] : ''; echo ' '; echo isset($_tmpb_installed['AUTHOR_WEBSITE']) ? $_tmpb_installed['AUTHOR_WEBSITE'] : ''; ?><br />
								<strong><?php echo isset($this->_var['L_DESC']) ? $this->_var['L_DESC'] : ''; ?>:</strong> <?php echo isset($_tmpb_installed['DESC']) ? $_tmpb_installed['DESC'] : ''; ?><br />
								<strong><?php echo isset($this->_var['L_COMPAT']) ? $this->_var['L_COMPAT'] : ''; ?>:</strong> PHPBoost <?php echo isset($_tmpb_installed['COMPAT']) ? $_tmpb_installed['COMPAT'] : ''; ?><br />
								<strong><?php echo isset($this->_var['L_ADMIN']) ? $this->_var['L_ADMIN'] : ''; ?>:</strong> <?php echo isset($_tmpb_installed['ADMIN']) ? $_tmpb_installed['ADMIN'] : ''; ?><br />
								<strong><?php echo isset($this->_var['L_USE_SQL']) ? $this->_var['L_USE_SQL'] : ''; ?>:</strong> <?php echo isset($_tmpb_installed['USE_SQL']) ? $_tmpb_installed['USE_SQL'] : ''; ?> <em><?php echo isset($_tmpb_installed['SQL_TABLE']) ? $_tmpb_installed['SQL_TABLE'] : ''; ?></em><br />
								<strong><?php echo isset($this->_var['L_USE_CACHE']) ? $this->_var['L_USE_CACHE'] : ''; ?>:</strong> <?php echo isset($_tmpb_installed['USE_CACHE']) ? $_tmpb_installed['USE_CACHE'] : ''; ?><br />
								<strong><?php echo isset($this->_var['L_ALTERNATIVE_CSS']) ? $this->_var['L_ALTERNATIVE_CSS'] : ''; ?>:</strong> <?php echo isset($_tmpb_installed['ALTERNATIVE_CSS']) ? $_tmpb_installed['ALTERNATIVE_CSS'] : ''; ?><br />
							</td>
							<td class="row2">								
								<input type="radio" name="activ<?php echo isset($_tmpb_installed['ID']) ? $_tmpb_installed['ID'] : ''; ?>" value="1" <?php echo isset($_tmpb_installed['ACTIV_ENABLED']) ? $_tmpb_installed['ACTIV_ENABLED'] : ''; ?> /> <?php echo isset($this->_var['L_YES']) ? $this->_var['L_YES'] : ''; ?>
								<input type="radio" name="activ<?php echo isset($_tmpb_installed['ID']) ? $_tmpb_installed['ID'] : ''; ?>" value="0" <?php echo isset($_tmpb_installed['ACTIV_DISABLED']) ? $_tmpb_installed['ACTIV_DISABLED'] : ''; ?> /> <?php echo isset($this->_var['L_NO']) ? $this->_var['L_NO'] : ''; ?>
							</td>
							<td class="row2" style="text-align:center;">								
								<span class="text_small">(<?php echo isset($this->_var['L_EXPLAIN_SELECT_MULTIPLE']) ? $this->_var['L_EXPLAIN_SELECT_MULTIPLE'] : ''; ?>)</span>
								<br />
								<select id="groups_auth" name="groups_auth<?php echo isset($_tmpb_installed['ID']) ? $_tmpb_installed['ID'] : ''; ?>[]" size="8" multiple="multiple" onclick="document.getElementById('<?php echo isset($_tmpb_installed['ID']) ? $_tmpb_installed['ID'] : ''; ?>r3').selected = true;">
									<?php if( !isset($_tmpb_installed['select_group']) || !is_array($_tmpb_installed['select_group']) ) $_tmpb_installed['select_group'] = array();
foreach($_tmpb_installed['select_group'] as $select_group_key => $select_group_value) {
$_tmpb_select_group = &$_tmpb_installed['select_group'][$select_group_key]; ?>	
										<?php echo isset($_tmpb_select_group['GROUP']) ? $_tmpb_select_group['GROUP'] : ''; ?>
									<?php } ?>						
								</select>
								<br />
								<a href="javascript:check_select_multiple('<?php echo isset($_tmpb_installed['ID']) ? $_tmpb_installed['ID'] : ''; ?>', true);"><?php echo isset($this->_var['L_SELECT_ALL']) ? $this->_var['L_SELECT_ALL'] : ''; ?></a>
								&nbsp;/&nbsp;
								<a href="javascript:check_select_multiple('<?php echo isset($_tmpb_installed['ID']) ? $_tmpb_installed['ID'] : ''; ?>', false);"><?php echo isset($this->_var['L_SELECT_NONE']) ? $this->_var['L_SELECT_NONE'] : ''; ?></a>
							</td>
							<td class="row2">	
								<input type="submit" name="<?php echo isset($_tmpb_installed['ID']) ? $_tmpb_installed['ID'] : ''; ?>" value="<?php echo isset($this->_var['L_UNINSTALL']) ? $this->_var['L_UNINSTALL'] : ''; ?>" class="submit" />
							</td>
						</tr>					
						<?php } ?>
					</table>
					
					<br /><br />
					
					<fieldset class="fieldset_submit">
						<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
						<input type="submit" name="valid" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
						&nbsp;&nbsp; 
						<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />
					</fieldset>
				</form>
			<?php } ?>
			
			
			<?php if( !isset($this->_block['del']) || !is_array($this->_block['del']) ) $this->_block['del'] = array();
foreach($this->_block['del'] as $del_key => $del_value) {
$_tmpb_del = &$this->_block['del'][$del_key]; ?>				
				<form action="admin_modules.php?uninstall=1" method="post" class="fieldset_content">
					<fieldset>
						<legend><?php echo isset($this->_var['L_DEL_MODULE']) ? $this->_var['L_DEL_MODULE'] : ''; ?></legend>
						<div class="error_warning" style="width:500px;margin:auto;">
							<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/important.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($this->_var['L_DEL_DATA']) ? $this->_var['L_DEL_DATA'] : ''; ?>
						</div>
						<br />
						<dl>
							<dt><label for="drop_files"><?php echo isset($this->_var['L_DEL_FILE']) ? $this->_var['L_DEL_FILE'] : ''; ?></label></dt>
							<dd><label><input type="radio" name="drop_files" value="1" /> <?php echo isset($this->_var['L_YES']) ? $this->_var['L_YES'] : ''; ?></label>
							<label><input type="radio" name="drop_files" id="drop_files" value="0" checked="checked" /> <?php echo isset($this->_var['L_NO']) ? $this->_var['L_NO'] : ''; ?></label></dd>
						</dl>
					</fieldset>		
					<fieldset class="fieldset_submit">
						<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
						<input type="hidden" name="idmodule" value="<?php echo isset($_tmpb_del['IDMODULE']) ? $_tmpb_del['IDMODULE'] : ''; ?>" />
						<input type="submit" name="valid_del" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
					</fieldset>
				</form>
			<?php } ?>
		</div>
		