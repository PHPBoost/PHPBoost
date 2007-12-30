		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_MAINTAIN']) ? $this->_var['L_MAINTAIN'] : ''; ?></li>
				<li>
					<a href="admin_maintain.php"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/maintain.png" alt="" /></a>
					<br />
					<a href="admin_maintain.php" class="quick_link"><?php echo isset($this->_var['L_MAINTAIN']) ? $this->_var['L_MAINTAIN'] : ''; ?></a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
			<form action="admin_maintain.php" method="post" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_SET_MAINTAIN']) ? $this->_var['L_SET_MAINTAIN'] : ''; ?></legend>
					<dl>
						<dt><label for="maintain"><?php echo isset($this->_var['L_SET_MAINTAIN']) ? $this->_var['L_SET_MAINTAIN'] : ''; ?></label></dt>
						<dd><label>
							<select name="maintain" id="maintain">				
							<?php if( !isset($this->_block['select_maintain']) || !is_array($this->_block['select_maintain']) ) $this->_block['select_maintain'] = array();
foreach($this->_block['select_maintain'] as $select_maintain_key => $select_maintain_value) {
$_tmpb_select_maintain = &$this->_block['select_maintain'][$select_maintain_key]; ?>				
								<?php echo isset($_tmpb_select_maintain['DELAY']) ? $_tmpb_select_maintain['DELAY'] : ''; ?>				
							<?php } ?>				
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="display_delay"><?php echo isset($this->_var['L_MAINTAIN_DELAY']) ? $this->_var['L_MAINTAIN_DELAY'] : ''; ?></label></dt>
						<dd><label><input type="radio" <?php echo isset($this->_var['DISPLAY_DELAY_ENABLED']) ? $this->_var['DISPLAY_DELAY_ENABLED'] : ''; ?> name="display_delay" id="display_delay" value="1" /> <?php echo isset($this->_var['L_YES']) ? $this->_var['L_YES'] : ''; ?></label>
						&nbsp;&nbsp; 
						<label><input type="radio" <?php echo isset($this->_var['DISPLAY_DELAY_DISABLED']) ? $this->_var['DISPLAY_DELAY_DISABLED'] : ''; ?> name="display_delay" value="0" /> <?php echo isset($this->_var['L_NO']) ? $this->_var['L_NO'] : ''; ?></label></dd>
					</dl>
					<dl>
						<dt><label for="maintain_display_admin"><?php echo isset($this->_var['L_MAINTAIN_DISPLAY_ADMIN']) ? $this->_var['L_MAINTAIN_DISPLAY_ADMIN'] : ''; ?></label></dt>
						<dd><label><input type="radio" <?php echo isset($this->_var['DISPLAY_ADMIN_ENABLED']) ? $this->_var['DISPLAY_ADMIN_ENABLED'] : ''; ?> name="maintain_display_admin" id="maintain_display_admin" value="1" /> <?php echo isset($this->_var['L_YES']) ? $this->_var['L_YES'] : ''; ?></label>
						&nbsp;&nbsp; 
						<label><input type="radio" <?php echo isset($this->_var['DISPLAY_ADMIN_DISABLED']) ? $this->_var['DISPLAY_ADMIN_DISABLED'] : ''; ?> name="maintain_display_admin" value="0" /> <?php echo isset($this->_var['L_NO']) ? $this->_var['L_NO'] : ''; ?></label></dd>
					</dl>
					<label for="contents"><?php echo isset($this->_var['L_MAINTAIN_TEXT']) ? $this->_var['L_MAINTAIN_TEXT'] : ''; ?></label>
					<label>
						<?php $this->tpl_include('handle_bbcode'); ?>	
						<textarea type="text" rows="14" cols="20" name="contents" id="contents"><?php echo isset($this->_var['MAINTAIN_CONTENTS']) ? $this->_var['MAINTAIN_CONTENTS'] : ''; ?></textarea>
					</label>
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend><?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?></legend>
					<input type="submit" name="valid" value="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" class="submit" />
					<script type="text/javascript">
					<!--				
					document.write('&nbsp;&nbsp;<input value="<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>" onclick="XMLHttpRequest_preview(this.form);" type="button" class="submit" />');
					-->
					</script>
					&nbsp;&nbsp;
					<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />		
				</fieldset>	
			</form>	
		</div>
		