		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("<?php echo isset($this->_var['L_DEL_ENTRY']) ? $this->_var['L_DEL_ENTRY'] : ''; ?>");
		}
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_WEB_MANAGEMENT']) ? $this->_var['L_WEB_MANAGEMENT'] : ''; ?></li>
				<li>
					<a href="admin_web.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web.php" class="quick_link"><?php echo isset($this->_var['L_WEB_MANAGEMENT']) ? $this->_var['L_WEB_MANAGEMENT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_web_add.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_add.php" class="quick_link"><?php echo isset($this->_var['L_WEB_ADD']) ? $this->_var['L_WEB_ADD'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_web_cat.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_cat.php" class="quick_link"><?php echo isset($this->_var['L_WEB_CAT']) ? $this->_var['L_WEB_CAT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_web_config.php"><img src="web.png" alt="" /></a>
					<br />
					<a href="admin_web_config.php" class="quick_link"><?php echo isset($this->_var['L_WEB_CONFIG']) ? $this->_var['L_WEB_CONFIG'] : ''; ?></a>
				</li>
			</ul>
		</div> 
		
		<div id="admin_contents">		
			<table class="module_table">
				<tr> 
					<th colspan="8">
						<?php echo isset($this->_var['L_LISTE']) ? $this->_var['L_LISTE'] : ''; ?>
					</th>
				</tr>
				<tr style="text-align:center;">
					<td class="row1">
						<?php echo isset($this->_var['L_NAME']) ? $this->_var['L_NAME'] : ''; ?>
					</td>
					<td class="row1">
						<?php echo isset($this->_var['L_CATEGORY']) ? $this->_var['L_CATEGORY'] : ''; ?>
					</td>
					<td class="row1">
						<?php echo isset($this->_var['L_VIEW']) ? $this->_var['L_VIEW'] : ''; ?>
					</td>
					<td class="row1">
						<?php echo isset($this->_var['L_DATE']) ? $this->_var['L_DATE'] : ''; ?>
					</td>
					<td class="row1">
						<?php echo isset($this->_var['L_APROB']) ? $this->_var['L_APROB'] : ''; ?>
					</td>
					<td class="row1">
						<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>
					</td>
					<td class="row1">
						<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>
					</td>
				</tr>
				
				<?php if( !isset($this->_block['web']) || !is_array($this->_block['web']) ) $this->_block['web'] = array();
foreach($this->_block['web'] as $web_key => $web_value) {
$_tmpb_web = &$this->_block['web'][$web_key]; ?>
				<tr style="text-align:center;"> 
					<td class="row2"> 
						<a href="../web/web.php?cat=<?php echo isset($_tmpb_web['IDCAT']) ? $_tmpb_web['IDCAT'] : ''; ?>&amp;id=<?php echo isset($_tmpb_web['IDWEB']) ? $_tmpb_web['IDWEB'] : ''; ?>"><?php echo isset($_tmpb_web['NAME']) ? $_tmpb_web['NAME'] : ''; ?></a>
					</td>
					<td class="row2"> 
						<?php echo isset($_tmpb_web['CAT']) ? $_tmpb_web['CAT'] : ''; ?>
					</td>
					<td class="row2"> 
						<?php echo isset($_tmpb_web['COMPT']) ? $_tmpb_web['COMPT'] : ''; ?>
					</td>
					<td class="row2">
						<?php echo isset($_tmpb_web['DATE']) ? $_tmpb_web['DATE'] : ''; ?>
					</td>
					<td class="row2">
						<?php echo isset($_tmpb_web['APROBATION']) ? $_tmpb_web['APROBATION'] : ''; ?>
					</td>
					<td class="row2"> 
						<a href="admin_web.php?id=<?php echo isset($_tmpb_web['IDWEB']) ? $_tmpb_web['IDWEB'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/edit.png" alt="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" title="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" /></a>
					</td>
					<td class="row2">
						<a href="admin_web.php?delete=true&amp;id=<?php echo isset($_tmpb_web['IDWEB']) ? $_tmpb_web['IDWEB'] : ''; ?>" onClick="javascript:return Confirm();"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/delete.png" alt="Supprimer" title="Supprimer" /></a>
					</td>
				</tr>
				<?php } ?>

			</table>

			<br /><br />
			<p style="text-align: center;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?></p>
		</div>
		