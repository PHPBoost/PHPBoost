		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('name').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_NAME']) ? $this->_var['L_REQUIRE_NAME'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('url').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_URL']) ? $this->_var['L_REQUIRE_URL'] : ''; ?>");
				return false;
		    }
				if(document.getElementById('idcat').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_CAT']) ? $this->_var['L_REQUIRE_CAT'] : ''; ?>");
				return false;
		    }
			
			return true;
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
			<?php if( !isset($this->_block['web']) || !is_array($this->_block['web']) ) $this->_block['web'] = array();
foreach($this->_block['web'] as $web_key => $web_value) {
$_tmpb_web = &$this->_block['web'][$web_key]; ?>
			<table class="module_table">
					<tr> 
						<th colspan="2">
							<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>
						</th>
					</tr>
					<tr> 
						<td class="row1">
							<br />
							<table width="96%" border="0" style="border-collapse: collapse; margin: auto;" class="table_news" cellspacing="0" cellpadding="3">
								<tr>
									<th style="text-align: center;">
										<span class="text_subtitle"><?php echo isset($_tmpb_web['NAME']) ? $_tmpb_web['NAME'] : ''; ?></span>
									</th>
								</tr>
								<tr>					
									<td>						
										<p>
											<br /><br />
											
											<strong>&nbsp;<?php echo isset($_tmpb_web['L_DESC']) ? $_tmpb_web['L_DESC'] : ''; ?>:</strong> <?php echo isset($_tmpb_web['CONTENTS']) ? $_tmpb_web['CONTENTS'] : ''; ?>
											
											<br /><br />
											
											<strong>&nbsp;<?php echo isset($_tmpb_web['L_CAT']) ? $_tmpb_web['L_CAT'] : ''; ?>:</strong> 
											<a href="../web/web.php?cat=<?php echo isset($_tmpb_web['IDCAT']) ? $_tmpb_web['IDCAT'] : ''; ?>" title="<?php echo isset($_tmpb_web['CAT']) ? $_tmpb_web['CAT'] : ''; ?>"><?php echo isset($_tmpb_web['CAT']) ? $_tmpb_web['CAT'] : ''; ?></a><br />
											
											<strong>&nbsp;<?php echo isset($_tmpb_web['L_DATE']) ? $_tmpb_web['L_DATE'] : ''; ?>:</strong> <?php echo isset($_tmpb_web['DATE']) ? $_tmpb_web['DATE'] : ''; ?><br />
											
											<strong>&nbsp;<?php echo isset($_tmpb_web['L_VIEWS']) ? $_tmpb_web['L_VIEWS'] : ''; ?>:</strong> <?php echo isset($this->_var['COMPT']) ? $this->_var['COMPT'] : ''; ?> <br />
											<strong>&nbsp;<?php echo isset($_tmpb_web['L_NOTE']) ? $_tmpb_web['L_NOTE'] : ''; ?>:</strong> 0 <br />
											<strong>&nbsp;<?php echo isset($_tmpb_web['L_COM']) ? $_tmpb_web['L_COM'] : ''; ?></strong>
											<br /><br />
										</p>
										<p style="text-align: center;">					
											<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/web/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/bouton_url.gif" alt="" title="" />
											<br />
											<?php echo isset($_tmpb_web['URL']) ? $_tmpb_web['URL'] : ''; ?>
										</p>
								
										<br /><br /><br />
										<span id="web">&nbsp;</span>
									</td>
								</tr>
								<tr>
									<td class="news_bottom">
										&nbsp;
									</td>	
								</tr>
							</table>
							<br />
						</td>
					</tr>
			</table>	

			<br /><br /><br />
			<?php } ?>

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
			
			<form action="admin_web_add.php" name="form" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_WEB_ADD']) ? $this->_var['L_WEB_ADD'] : ''; ?></legend>
					<p><?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?></p>
					<dl>
						<dt><label for="name">* <?php echo isset($this->_var['L_NAME']) ? $this->_var['L_NAME'] : ''; ?></label></dt>
						<dd><label><input type="text" size="55" maxlength="50" name="name" id="name" value="<?php echo isset($this->_var['NAME']) ? $this->_var['NAME'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="idcat">* <?php echo isset($this->_var['L_CATEGORY']) ? $this->_var['L_CATEGORY'] : ''; ?></label></dt>
						<dd><label>
							<select id="idcat" name="idcat">				
							<?php if( !isset($this->_block['select']) || !is_array($this->_block['select']) ) $this->_block['select'] = array();
foreach($this->_block['select'] as $select_key => $select_value) {
$_tmpb_select = &$this->_block['select'][$select_key]; ?>				
								<?php echo isset($_tmpb_select['CAT']) ? $_tmpb_select['CAT'] : ''; ?>				
							<?php } ?>				
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="url">* <?php echo isset($this->_var['L_URL_LINK']) ? $this->_var['L_URL_LINK'] : ''; ?></label></dt>
						<dd><label><input type="text" size="65" id="url" name="url" id="url" value="<?php echo isset($this->_var['URL']) ? $this->_var['URL'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="compt"><?php echo isset($this->_var['L_VIEWS']) ? $this->_var['L_VIEWS'] : ''; ?></label></dt>
						<dd><label><input type="text" size="10" maxlength="10" name="compt" id="compt" value="<?php echo isset($this->_var['COMPT']) ? $this->_var['COMPT'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<br />
					<label for="contents"><?php echo isset($this->_var['L_DESC']) ? $this->_var['L_DESC'] : ''; ?></label>
					<label>
						<?php $this->tpl_include('handle_bbcode'); ?>
						<textarea type="text" rows="20" cols="90" id="contents" name="contents"><?php echo isset($this->_var['CONTENTS']) ? $this->_var['CONTENTS'] : ''; ?></textarea> 
						<br />
					</label>
					<dl>
						<dt><label for="aprob">* <?php echo isset($this->_var['L_APROB']) ? $this->_var['L_APROB'] : ''; ?></label></dt>
						<dd>
							<label><input type="radio" <?php echo isset($this->_var['CHECK_ENABLED']) ? $this->_var['CHECK_ENABLED'] : ''; ?> name="aprob" id="aprob" value="1" /> <?php echo isset($this->_var['L_YES']) ? $this->_var['L_YES'] : ''; ?></label>
							&nbsp;&nbsp; 
							<label><input type="radio" <?php echo isset($this->_var['CHECK_DISABLED']) ? $this->_var['CHECK_DISABLED'] : ''; ?>  name="aprob" value="0" /> <?php echo isset($this->_var['L_NO']) ? $this->_var['L_NO'] : ''; ?></label></dd>
					</dl>
				</fieldset>		
				
				<fieldset class="fieldset_submit">
					<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
					<input type="submit" name="valid" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
					&nbsp;&nbsp; 
					<input type="submit" name="previs" value="<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />				
				</fieldset>	
			</form>
		</div>
		