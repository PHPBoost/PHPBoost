		<link href="<?php echo isset($this->_var['MODULE_DATA_PATH']) ? $this->_var['MODULE_DATA_PATH'] : ''; ?>/forum.css" rel="stylesheet" type="text/css" media="screen, handheld">
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
				
				for(i = 0; i < <?php echo isset($this->_var['NBR_GROUP']) ? $this->_var['NBR_GROUP'] : ''; ?>; i++)
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
			function change_parent_cat(value)
			{			
				if( value > 0 )
				{
					disabled = 0;
					var i;
					var array_id = new Array('wr', 'xr', 'wg', 'xg');
					for(j = 0; j <= 5; j++)
					{
						for(i = -1; i <= 2; i++)
						{	
							if( document.getElementById(array_id[j] + i) )
								document.getElementById(array_id[j] + i).disabled = '';
						}
						for(i = 0; i < <?php echo isset($this->_var['NBR_GROUP']) ? $this->_var['NBR_GROUP'] : ''; ?>; i++)
						{	
							if( document.getElementById(array_id[j] + i) )
								document.getElementById(array_id[j] + i).disabled = '';	
						}
					}
					document.getElementById('wr3').selected = true;
					document.getElementById('xr3').selected = true;
				}
				else
				{
					disabled = 1;
					var i;
					var array_id = new Array('wr', 'xr', 'wg', 'xg');
					for(j = 0; j <= 5; j++)
					{
						for(i = -1; i <= 2; i++)
						{	
							if( document.getElementById(array_id[j] + i) )
							{
								document.getElementById(array_id[j] + i).disabled = 'disabled';	
								document.getElementById(array_id[j] + i).selected = '';
							}		
						}
						for(i = 0; i < <?php echo isset($this->_var['NBR_GROUP']) ? $this->_var['NBR_GROUP'] : ''; ?>; i++)
						{	
							if( document.getElementById(array_id[j] + i) )
							{
								document.getElementById(array_id[j] + i).disabled = 'disabled';	
								document.getElementById(array_id[j] + i).selected = '';
							}			
						}
					}
				}
			}
		-->
		</script>
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_FORUM_MANAGEMENT']) ? $this->_var['L_FORUM_MANAGEMENT'] : ''; ?></li>
				<li>
					<a href="admin_forum.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum.php" class="quick_link"><?php echo isset($this->_var['L_CAT_MANAGEMENT']) ? $this->_var['L_CAT_MANAGEMENT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_forum_add.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum_add.php" class="quick_link"><?php echo isset($this->_var['L_ADD_CAT']) ? $this->_var['L_ADD_CAT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_forum_config.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum_config.php" class="quick_link"><?php echo isset($this->_var['L_FORUM_CONFIG']) ? $this->_var['L_FORUM_CONFIG'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_forum_groups.php"><img src="forum.png" alt="" /></a>
					<br />
					<a href="admin_forum_groups.php" class="quick_link"><?php echo isset($this->_var['L_FORUM_GROUPS']) ? $this->_var['L_FORUM_GROUPS'] : ''; ?></a>
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
				
			<form action="admin_forum_add.php" method="post" onsubmit="return check_form_list();" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_ADD_CAT']) ? $this->_var['L_ADD_CAT'] : ''; ?></legend>
					<p><?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?></p>
					<dl>
						<dt><label for="name">* <?php echo isset($this->_var['L_NAME']) ? $this->_var['L_NAME'] : ''; ?></label></dt>
						<dd><label><input type="text" maxlength="100" size="35" id="name" name="name" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="category">* <?php echo isset($this->_var['L_PARENT_CATEGORY']) ? $this->_var['L_PARENT_CATEGORY'] : ''; ?></label></dt>
						<dd><label>
							<select name="category" id="category" onchange="change_parent_cat(this.options[this.selectedIndex].value)">
								<?php echo isset($this->_var['CATEGORIES']) ? $this->_var['CATEGORIES'] : ''; ?>
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="desc"><?php echo isset($this->_var['L_DESC']) ? $this->_var['L_DESC'] : ''; ?></label></dt>
						<dd><label><input type="text" maxlength="150" size="55" name="desc" id="desc" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="aprob"><?php echo isset($this->_var['L_APROB']) ? $this->_var['L_APROB'] : ''; ?></label></dt>
						<dd>
							<label><input type="radio" name="aprob" id="aprob" checked="checked" value="1" /> <?php echo isset($this->_var['L_YES']) ? $this->_var['L_YES'] : ''; ?></label>
							<label><input type="radio" name="aprob" value="0" /> <?php echo isset($this->_var['L_NO']) ? $this->_var['L_NO'] : ''; ?></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="status"><?php echo isset($this->_var['L_STATUS']) ? $this->_var['L_STATUS'] : ''; ?></label></dt>
						<dd>
							<label><input type="radio" name="status" id="status" checked="checked" value="1" /> <?php echo isset($this->_var['L_UNLOCK']) ? $this->_var['L_UNLOCK'] : ''; ?></label>
							<label><input type="radio" name="status" value="0" /> <?php echo isset($this->_var['L_LOCK']) ? $this->_var['L_LOCK'] : ''; ?></label>
						</dd>
					</dl>
					<dl>
						<dt><label><?php echo isset($this->_var['L_AUTH_READ']) ? $this->_var['L_AUTH_READ'] : ''; ?></label></dt>
						<dd><label>
							<span class="text_small">(<?php echo isset($this->_var['L_EXPLAIN_SELECT_MULTIPLE']) ? $this->_var['L_EXPLAIN_SELECT_MULTIPLE'] : ''; ?>)</span>
							<br />
							<?php echo isset($this->_var['AUTH_READ']) ? $this->_var['AUTH_READ'] : ''; ?>
							<br />
							<a href="javascript:check_select_multiple('r', true);"><?php echo isset($this->_var['L_SELECT_ALL']) ? $this->_var['L_SELECT_ALL'] : ''; ?></a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('r', false);"><?php echo isset($this->_var['L_SELECT_NONE']) ? $this->_var['L_SELECT_NONE'] : ''; ?></a>
						</label></dd>
					</dl>
					<dl>
						<dt><label><?php echo isset($this->_var['L_AUTH_WRITE']) ? $this->_var['L_AUTH_WRITE'] : ''; ?></label></dt>
						<dd><label>
							<span class="text_small">(<?php echo isset($this->_var['L_EXPLAIN_SELECT_MULTIPLE']) ? $this->_var['L_EXPLAIN_SELECT_MULTIPLE'] : ''; ?>)</span>
							<br />
							<?php echo isset($this->_var['AUTH_WRITE']) ? $this->_var['AUTH_WRITE'] : ''; ?>
							<br />
							<a href="javascript:check_select_multiple('w', true);"><?php echo isset($this->_var['L_SELECT_ALL']) ? $this->_var['L_SELECT_ALL'] : ''; ?></a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('w', false);"><?php echo isset($this->_var['L_SELECT_NONE']) ? $this->_var['L_SELECT_NONE'] : ''; ?></a>
						</label></dd>
					</dl>
					<dl>
						<dt><label><?php echo isset($this->_var['L_AUTH_EDIT']) ? $this->_var['L_AUTH_EDIT'] : ''; ?></label></dt>
						<dd><label>
							<span class="text_small">(<?php echo isset($this->_var['L_EXPLAIN_SELECT_MULTIPLE']) ? $this->_var['L_EXPLAIN_SELECT_MULTIPLE'] : ''; ?>)</span>
							<br />
							<?php echo isset($this->_var['AUTH_EDIT']) ? $this->_var['AUTH_EDIT'] : ''; ?>
							<br />
							<a href="javascript:check_select_multiple('x', true);"><?php echo isset($this->_var['L_SELECT_ALL']) ? $this->_var['L_SELECT_ALL'] : ''; ?></a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('x', false);"><?php echo isset($this->_var['L_SELECT_NONE']) ? $this->_var['L_SELECT_NONE'] : ''; ?></a>
						</label></dd>
					</dl>
				</fieldset>
								
				<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_ADD']) ? $this->_var['L_ADD'] : ''; ?></legend>
					<input type="submit" name="add" value="<?php echo isset($this->_var['L_ADD']) ? $this->_var['L_ADD'] : ''; ?>" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />
				</fieldset>
			</form>
		</div>
			