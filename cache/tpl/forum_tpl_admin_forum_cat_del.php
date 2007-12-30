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
			
			<form method="post" action="admin_forum.php?del=<?php echo isset($this->_var['IDCAT']) ? $this->_var['IDCAT'] : ''; ?>" onsubmit="javascript:return check_form_select();" class="fieldset_content">
				<?php if( !isset($this->_block['topics']) || !is_array($this->_block['topics']) ) $this->_block['topics'] = array();
foreach($this->_block['topics'] as $topics_key => $topics_value) {
$_tmpb_topics = &$this->_block['topics'][$topics_key]; ?>
				<fieldset>
					<legend><?php echo isset($_tmpb_topics['L_KEEP']) ? $_tmpb_topics['L_KEEP'] : ''; ?></legend>
					<div class="error_warning" style="width:500px;margin:auto;padding:15px;">
						<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/important.png" alt="" style="float:left;padding-right:6px;" /> &nbsp;<?php echo isset($_tmpb_topics['L_EXPLAIN_CAT']) ? $_tmpb_topics['L_EXPLAIN_CAT'] : ''; ?>
						<br />	
					</div>
					<br />	
					<dl>
						<dt><label for="t_to"><?php echo isset($_tmpb_topics['L_MOVE_TOPICS']) ? $_tmpb_topics['L_MOVE_TOPICS'] : ''; ?></label></dt>
						<dd><label>
							<select id="t_to" name="t_to">
								<?php echo isset($_tmpb_topics['FORUMS']) ? $_tmpb_topics['FORUMS'] : ''; ?>
							</select>
						</label></dd>
					</dl>
				</fieldset>			
				<?php } ?>
				
				<?php if( !isset($this->_block['subforums']) || !is_array($this->_block['subforums']) ) $this->_block['subforums'] = array();
foreach($this->_block['subforums'] as $subforums_key => $subforums_value) {
$_tmpb_subforums = &$this->_block['subforums'][$subforums_key]; ?>
				<fieldset>
					<legend><?php echo isset($_tmpb_subforums['L_KEEP']) ? $_tmpb_subforums['L_KEEP'] : ''; ?></legend>
					<div class="error_warning" style="width:500px;margin:auto;padding:15px;">
						<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/important.png" alt="" style="float:left;padding-right:6px;" /> &nbsp;<?php echo isset($_tmpb_subforums['L_EXPLAIN_CAT']) ? $_tmpb_subforums['L_EXPLAIN_CAT'] : ''; ?>
						<br />	
					</div>
					<br />	
					<dl>
						<dt><label for="f_to"><?php echo isset($_tmpb_subforums['L_MOVE_FORUMS']) ? $_tmpb_subforums['L_MOVE_FORUMS'] : ''; ?></label></dt>
						<dd><label>
							<select id="f_to" name="f_to">
								<?php echo isset($_tmpb_subforums['FORUMS']) ? $_tmpb_subforums['FORUMS'] : ''; ?>
							</select>
						</label></dd>
					</dl>
				</fieldset>			
				<?php } ?>
				
				<fieldset>
					<legend><?php echo isset($this->_var['L_DEL_ALL']) ? $this->_var['L_DEL_ALL'] : ''; ?></legend>
					<dl>
						<dt><label for="del_conf"><?php echo isset($this->_var['L_DEL_FORUM_CONTENTS']) ? $this->_var['L_DEL_FORUM_CONTENTS'] : ''; ?></label></dt>
						<dd><label><input type="checkbox" name="del_conf" id="del_conf" /></label></dd>
					</dl>
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
					<input type="submit" name="del_cat" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
				</fieldset>
			</form>
		</div>
		