		<script type="text/javascript">
		<!--
		function check_form_conf()
		{
			if(document.getElementById('calendar_auth').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?>");
				return false;
			}
			return true;
		}
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_CALENDAR']) ? $this->_var['L_CALENDAR'] : ''; ?></li>
				<li>
					<a href="admin_calendar.php"><img src="calendar.png" alt="" /></a>
					<br />
					<a href="admin_calendar.php" class="quick_link"><?php echo isset($this->_var['L_CALENDAR_CONFIG']) ? $this->_var['L_CALENDAR_CONFIG'] : ''; ?></a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
		
		<form action="admin_calendar.php" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
			<fieldset>
				<legend><?php echo isset($this->_var['L_CALENDAR_CONFIG']) ? $this->_var['L_CALENDAR_CONFIG'] : ''; ?></legend>
				<dl>
					<dt><label for="shoutbox_auth">* <?php echo isset($this->_var['L_RANK']) ? $this->_var['L_RANK'] : ''; ?></label></dt>
					<dd><label>
						<select name="calendar_auth" id="calendar_auth">
						<?php if( !isset($this->_block['select_auth']) || !is_array($this->_block['select_auth']) ) $this->_block['select_auth'] = array();
foreach($this->_block['select_auth'] as $select_auth_key => $select_auth_value) {
$_tmpb_select_auth = &$this->_block['select_auth'][$select_auth_key]; ?>
							<?php echo isset($_tmpb_select_auth['RANK']) ? $_tmpb_select_auth['RANK'] : ''; ?>
						<?php } ?>
						</select>
					</label></dd>
				</dl>
			</fieldset>	
			
			<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?></legend>
				<input type="submit" name="valid" value="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" class="submit" />
			</fieldset>
		</form>
		