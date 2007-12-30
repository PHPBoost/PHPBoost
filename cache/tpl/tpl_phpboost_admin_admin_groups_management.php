		<script type="text/javascript">
		<!--

		function Confirm() {
		return confirm("<?php echo isset($this->_var['L_CONFIRM_DEL_GROUP']) ? $this->_var['L_CONFIRM_DEL_GROUP'] : ''; ?>");
		}
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_GROUPS_MANAGEMENT']) ? $this->_var['L_GROUPS_MANAGEMENT'] : ''; ?></li>
				<li>
					<a href="admin_groups.php"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/groups.png" alt="" /></a>
					<br />
					<a href="admin_groups.php" class="quick_link"><?php echo isset($this->_var['L_GROUPS_MANAGEMENT']) ? $this->_var['L_GROUPS_MANAGEMENT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_groups.php?add=1"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/groups.png" alt="" /></a>
					<br />
					<a href="admin_groups.php?add=1" class="quick_link"><?php echo isset($this->_var['L_ADD_GROUPS']) ? $this->_var['L_ADD_GROUPS'] : ''; ?></a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
			<fieldset class="fieldset_content">
				<legend><?php echo isset($this->_var['L_GROUPS_MANAGEMENT']) ? $this->_var['L_GROUPS_MANAGEMENT'] : ''; ?></legend>
			
				<table class="module_table">
					<tr style="text-align:center;">
						<th>
							<?php echo isset($this->_var['L_NAME']) ? $this->_var['L_NAME'] : ''; ?>
						</th>
						<th>
							<?php echo isset($this->_var['L_IMAGE']) ? $this->_var['L_IMAGE'] : ''; ?>
						</th>
						<th>
							<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>
						</th>
						<th>
							<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>
						</th>
					</tr>
					
					<?php if( !isset($this->_block['group']) || !is_array($this->_block['group']) ) $this->_block['group'] = array();
foreach($this->_block['group'] as $group_key => $group_value) {
$_tmpb_group = &$this->_block['group'][$group_key]; ?>
					<tr style="text-align:center;"> 
						<td class="row2">
							<a href="../member/member<?php echo isset($_tmpb_group['LINK']) ? $_tmpb_group['LINK'] : ''; ?>"><?php echo isset($_tmpb_group['NAME']) ? $_tmpb_group['NAME'] : ''; ?></a>
						</td>
						<td class="row2">
							<?php echo isset($_tmpb_group['IMAGE']) ? $_tmpb_group['IMAGE'] : ''; ?>
						</td>
						<td class="row2"> 
							<a href="admin_groups.php?id=<?php echo isset($_tmpb_group['ID']) ? $_tmpb_group['ID'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/edit.png" alt="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" title="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" /></a>
						</td>
						<td class="row2">
							<a href="admin_groups.php?del=1&amp;id=<?php echo isset($_tmpb_group['ID']) ? $_tmpb_group['ID'] : ''; ?>" onClick="javascript:return Confirm();"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/delete.png" alt="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" title="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" /></a>
						</td>
					</tr>
					<?php } ?>

					<tr style="text-align:center;">
						<td class="row1" colspan="4">
							<a href="admin_groups.php?add=1" title="<?php echo isset($this->_var['L_ADD_GROUPS']) ? $this->_var['L_ADD_GROUPS'] : ''; ?>"><?php echo isset($this->_var['L_ADD_GROUPS']) ? $this->_var['L_ADD_GROUPS'] : ''; ?></a>
						</td>
					</tr>
				</table>
			</fieldset>
			
			<p style="text-align: center;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?></p>
		</div>
		