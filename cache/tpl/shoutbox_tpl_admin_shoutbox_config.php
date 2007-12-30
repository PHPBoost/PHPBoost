		<script type="text/javascript">
		<!--
		function check_form_conf()
		{
			if(document.getElementById('shoutbox_max_msg').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?>");
				return false;
			}
			if(document.getElementById('shoutbox_auth').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?>");
				return false;
			}
			return true;
		}
		function check_select_multiple(id, status)
		{
			var i;
			
			for(i = 0; i < <?php echo isset($this->_var['NBR_TAGS']) ? $this->_var['NBR_TAGS'] : ''; ?>; i++)
			{	
				if( document.getElementById(id + i) )
					document.getElementById(id + i).selected = status;			
			}
		}	
		-->
		</script>

		
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_SHOUTBOX']) ? $this->_var['L_SHOUTBOX'] : ''; ?></li>
				<li>
					<a href="admin_shoutbox.php"><img src="shoutbox.png" alt="" /></a>
					<br />
					<a href="admin_shoutbox.php" class="quick_link"><?php echo isset($this->_var['L_SHOUTBOX_CONFIG']) ? $this->_var['L_SHOUTBOX_CONFIG'] : ''; ?></a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
		
			<form action="admin_shoutbox.php" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_SHOUTBOX_CONFIG']) ? $this->_var['L_SHOUTBOX_CONFIG'] : ''; ?></legend>
					<dl>
						<dt><label for="shoutbox_max_msg">* <?php echo isset($this->_var['L_SHOUTBOX_MAX_MSG']) ? $this->_var['L_SHOUTBOX_MAX_MSG'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_SHOUTBOX_MAX_MSG_EXPLAIN']) ? $this->_var['L_SHOUTBOX_MAX_MSG_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="3" maxlength="3" id="shoutbox_max_msg" name="shoutbox_max_msg" value="<?php echo isset($this->_var['SHOUTBOX_MAX_MSG']) ? $this->_var['SHOUTBOX_MAX_MSG'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="shoutbox_auth">* <?php echo isset($this->_var['L_RANK']) ? $this->_var['L_RANK'] : ''; ?></label></dt>
						<dd><label>
							<select name="shoutbox_auth" id="shoutbox_auth">
								<?php if( !isset($this->_block['select_auth']) || !is_array($this->_block['select_auth']) ) $this->_block['select_auth'] = array();
foreach($this->_block['select_auth'] as $select_auth_key => $select_auth_value) {
$_tmpb_select_auth = &$this->_block['select_auth'][$select_auth_key]; ?>
									<?php echo isset($_tmpb_select_auth['RANK']) ? $_tmpb_select_auth['RANK'] : ''; ?>
								<?php } ?>
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label>* <?php echo isset($this->_var['L_FORBIDDEN_TAGS']) ? $this->_var['L_FORBIDDEN_TAGS'] : ''; ?></label></dt>
						<dd><label>
							<span class="text_small">(<?php echo isset($this->_var['L_EXPLAIN_SELECT_MULTIPLE']) ? $this->_var['L_EXPLAIN_SELECT_MULTIPLE'] : ''; ?>)</span>
							<br />
							<select name="shoutbox_forbidden_tags[]" size="10" multiple="multiple">
								<?php if( !isset($this->_block['forbidden_tags']) || !is_array($this->_block['forbidden_tags']) ) $this->_block['forbidden_tags'] = array();
foreach($this->_block['forbidden_tags'] as $forbidden_tags_key => $forbidden_tags_value) {
$_tmpb_forbidden_tags = &$this->_block['forbidden_tags'][$forbidden_tags_key]; ?>
									<?php echo isset($_tmpb_forbidden_tags['TAGS']) ? $_tmpb_forbidden_tags['TAGS'] : ''; ?>
								<?php } ?>						
							</select>
							<br />
							<a href="javascript:check_select_multiple('tag', true);"><?php echo isset($this->_var['L_SELECT_ALL']) ? $this->_var['L_SELECT_ALL'] : ''; ?></a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('tag', false);"><?php echo isset($this->_var['L_SELECT_NONE']) ? $this->_var['L_SELECT_NONE'] : ''; ?></a>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="shoutbox_max_link">* <?php echo isset($this->_var['L_MAX_LINK']) ? $this->_var['L_MAX_LINK'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_MAX_LINK_EXPLAIN']) ? $this->_var['L_MAX_LINK_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="2" name="shoutbox_max_link" id="shoutbox_max_link" value="<?php echo isset($this->_var['MAX_LINK']) ? $this->_var['MAX_LINK'] : ''; ?>" class="text" /></label></dd>
					</dl>
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend><?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?></legend>
					<input type="submit" name="valid" value="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" class="submit" />
					&nbsp;
					<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />			
				</fieldset>
			</form>
		</div>
		