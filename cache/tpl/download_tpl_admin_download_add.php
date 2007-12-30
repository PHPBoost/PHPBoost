		<script type="text/javascript" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.js"></script>
		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('contents').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_DESC']) ? $this->_var['L_REQUIRE_DESC'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('cat').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_CAT']) ? $this->_var['L_REQUIRE_CAT'] : ''; ?>");
				return false;
			}
			if(document.getElementById('title').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_TITLE']) ? $this->_var['L_REQUIRE_TITLE'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('url').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_URL']) ? $this->_var['L_REQUIRE_URL'] : ''; ?>");
				return false;
		    }
				if(document.getElementById('cat').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_CAT']) ? $this->_var['L_REQUIRE_CAT'] : ''; ?>");
				return false;
		    }
			return true;
		}
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_DOWNLOAD_MANAGEMENT']) ? $this->_var['L_DOWNLOAD_MANAGEMENT'] : ''; ?></li>
				<li>
					<a href="admin_download.php"><img src="download.png" alt="" /></a>
					<br />
					<a href="admin_download.php" class="quick_link"><?php echo isset($this->_var['L_DOWNLOAD_MANAGEMENT']) ? $this->_var['L_DOWNLOAD_MANAGEMENT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_download_add.php"><img src="download.png" alt="" /></a>
					<br />
					<a href="admin_download_add.php" class="quick_link"><?php echo isset($this->_var['L_DOWNLOAD_ADD']) ? $this->_var['L_DOWNLOAD_ADD'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_download_cat.php"><img src="download.png" alt="" /></a>
					<br />
					<a href="admin_download_cat.php" class="quick_link"><?php echo isset($this->_var['L_DOWNLOAD_CAT']) ? $this->_var['L_DOWNLOAD_CAT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_download_config.php"><img src="download.png" alt="" /></a>
					<br />
					<a href="admin_download_config.php" class="quick_link"><?php echo isset($this->_var['L_DOWNLOAD_CONFIG']) ? $this->_var['L_DOWNLOAD_CONFIG'] : ''; ?></a>
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
		
			<?php if( !isset($this->_block['download']) || !is_array($this->_block['download']) ) $this->_block['download'] = array();
foreach($this->_block['download'] as $download_key => $download_value) {
$_tmpb_download = &$this->_block['download'][$download_key]; ?>
			<table  class="module_table">
				<tr> 
					<th colspan="2">
						<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>
					</th>
				</tr>
				<tr> 
					<td>
						<br />
						<div class="module_position">					
							<div class="module_top_l"></div>		
							<div class="module_top_r"></div>
							<div class="module_top">
								<div style="float:left">
									<?php echo isset($this->_var['TITLE']) ? $this->_var['TITLE'] : ''; ?>
								</div>
								<div style="float:right">
									<?php echo isset($_tmpb_download['COM']) ? $_tmpb_download['COM'] : ''; ?>
								</div>
							</div>
							<div class="module_contents">
								<p>					
									<strong><?php echo isset($this->_var['L_DESC']) ? $this->_var['L_DESC'] : ''; ?>:</strong> <?php echo isset($_tmpb_download['CONTENTS']) ? $_tmpb_download['CONTENTS'] : ''; ?>									
									<br /><br />									
									<strong><?php echo isset($this->_var['L_CATEGORY']) ? $this->_var['L_CATEGORY'] : ''; ?>:</strong> 
									<a href="../download/php?cat=<?php echo isset($this->_var['IDCAT']) ? $this->_var['IDCAT'] : ''; ?>"><?php echo isset($_tmpb_download['CAT']) ? $_tmpb_download['CAT'] : ''; ?></a><br />
									
									<strong><?php echo isset($this->_var['L_DATE']) ? $this->_var['L_DATE'] : ''; ?>:</strong> <?php echo isset($_tmpb_download['DATE']) ? $_tmpb_download['DATE'] : ''; ?><br />									
									<strong><?php echo isset($this->_var['L_DOWNLOAD']) ? $this->_var['L_DOWNLOAD'] : ''; ?>:</strong> <?php echo isset($_tmpb_download['COMPT']) ? $_tmpb_download['COMPT'] : ''; ?>
								</p>
								<p style="text-align: center;">					
									<a href="../download/count.php?id=<?php echo isset($_tmpb_download['IDURL']) ? $_tmpb_download['IDURL'] : ''; ?>"><img src="../download/templates/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/bouton_dl.gif" alt="" /></a>
									<?php echo isset($_tmpb_download['URL']) ? $_tmpb_download['URL'] : ''; ?>
								</p>
							</div>
							<div class="module_bottom_l"></div>		
							<div class="module_bottom_r"></div>
							<div class="module_bottom"></div>
						</div>
						<br />
					</td>
				</tr>
			</table>
			<br /><br /><br />
			<?php } ?>

			<form action="admin_download_add.php" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_DOWNLOAD_ADD']) ? $this->_var['L_DOWNLOAD_ADD'] : ''; ?></legend>
					<p><?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?></p>
					<dl>
						<dt><label for="title">* <?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?></label></dt>
						<dd><label><input type="text" size="65" maxlength="100" id="title" name="title" value="<?php echo isset($this->_var['TITLE']) ? $this->_var['TITLE'] : ''; ?>" class="text" /></label></dd>
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
						<dt><label for="url">* <?php echo isset($this->_var['L_URL']) ? $this->_var['L_URL'] : ''; ?></label></dt>
						<dd><label><input type="text" size="65" id="url" name="url" id="url" value="<?php echo isset($this->_var['URL']) ? $this->_var['URL'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="size"><?php echo isset($this->_var['L_SIZE']) ? $this->_var['L_SIZE'] : ''; ?></label></dt>
						<dd><label><input type="text" size="10" maxlength="10" name="size" id="size" value="<?php echo isset($this->_var['SIZE']) ? $this->_var['SIZE'] : ''; ?>" class="text" /> <?php echo isset($this->_var['UNIT_SIZE']) ? $this->_var['UNIT_SIZE'] : ''; ?></label></dd>
					</dl>
					<dl>
						<dt><label for="compt"><?php echo isset($this->_var['L_DOWNLOAD']) ? $this->_var['L_DOWNLOAD'] : ''; ?></label></dt>
						<dd><label><input type="text" size="10" maxlength="10" name="compt" id="compt" value="<?php echo isset($this->_var['COMPT']) ? $this->_var['COMPT'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<br />
					<label for="contents">* <?php echo isset($this->_var['L_CONTENTS']) ? $this->_var['L_CONTENTS'] : ''; ?></label>
					<label>
						<?php $this->tpl_include('handle_bbcode'); ?>
						<textarea type="text" rows="20" cols="90" id="contents" name="contents"><?php echo isset($this->_var['CONTENTS']) ? $this->_var['CONTENTS'] : ''; ?></textarea> 
						<br />
					</label>
					<br />
					<dl class="overflow_visible">
						<dt><label for="release_date">* <?php echo isset($this->_var['L_RELEASE_DATE']) ? $this->_var['L_RELEASE_DATE'] : ''; ?></label></dt>
						<dd>
							<label><input type="radio" value="2" name="visible" <?php echo isset($this->_var['VISIBLE_WAITING']) ? $this->_var['VISIBLE_WAITING'] : ''; ?> /> 
						<input type="text" size="8" maxlength="8" id="start" name="start" value="<?php echo isset($this->_var['START']) ? $this->_var['START'] : ''; ?>" class="text" /> 
						<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
							<div id="start_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
							</div>
						</div>
						<a onClick="xmlhttprequest_calendar('start_date', '?input_field=start&amp;field=start_date&amp;d=<?php echo isset($this->_var['DAY_RELEASE_S']) ? $this->_var['DAY_RELEASE_S'] : ''; ?>&amp;m=<?php echo isset($this->_var['MONTH_RELEASE_S']) ? $this->_var['MONTH_RELEASE_S'] : ''; ?>&amp;y=<?php echo isset($this->_var['YEAR_RELEASE_S']) ? $this->_var['YEAR_RELEASE_S'] : ''; ?>');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.png" alt="" /></a>
						
						<?php echo isset($this->_var['L_UNTIL']) ? $this->_var['L_UNTIL'] : ''; ?>&nbsp;
						
						<input type="text" size="8" maxlength="8" id="end" name="end" value="<?php echo isset($this->_var['END']) ? $this->_var['END'] : ''; ?>" class="text" />					
						<div style="position:relative;z-index:100;top:6px;margin-left:155px;float:left;display:none;" id="calendar2">
							<div id="end_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);">							
							</div>
						</div>
						<a onClick="xmlhttprequest_calendar('end_date', '?input_field=end&amp;field=end_date&amp;d=<?php echo isset($this->_var['DAY_RELEASE_E']) ? $this->_var['DAY_RELEASE_E'] : ''; ?>&amp;m=<?php echo isset($this->_var['MONTH_RELEASE_E']) ? $this->_var['MONTH_RELEASE_E'] : ''; ?>&amp;y=<?php echo isset($this->_var['YEAR_RELEASE_E']) ? $this->_var['YEAR_RELEASE_E'] : ''; ?>');display_calendar(2);" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.png" alt="" /></a></label>
						<br />
						<label><input type="radio" value="1" name="visible" <?php echo isset($this->_var['VISIBLE_ENABLED']) ? $this->_var['VISIBLE_ENABLED'] : ''; ?> id="release_date" /> <?php echo isset($this->_var['L_IMMEDIATE']) ? $this->_var['L_IMMEDIATE'] : ''; ?></label>
						<br />
						<label><input type="radio" value="0" name="visible" <?php echo isset($this->_var['VISIBLE_UNAPROB']) ? $this->_var['VISIBLE_UNAPROB'] : ''; ?> /> <?php echo isset($this->_var['L_UNAPROB']) ? $this->_var['L_UNAPROB'] : ''; ?></label>
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="current_date">* <?php echo isset($this->_var['L_DOWNLOAD_DATE']) ? $this->_var['L_DOWNLOAD_DATE'] : ''; ?></label></dt>
						<dd><label>
							<input type="text" size="8" maxlength="8" id="current_date" name="current_date" value="<?php echo isset($this->_var['CURRENT_DATE']) ? $this->_var['CURRENT_DATE'] : ''; ?>" class="text" /> 
							<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar3">
								<div id="current" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);">							
								</div>
							</div>
							<a onClick="xmlhttprequest_calendar('current', '?input_field=current_date&amp;field=current&amp;d=<?php echo isset($this->_var['DAY_DATE']) ? $this->_var['DAY_DATE'] : ''; ?>&amp;m=<?php echo isset($this->_var['MONTH_DATE']) ? $this->_var['MONTH_DATE'] : ''; ?>&amp;y=<?php echo isset($this->_var['YEAR_DATE']) ? $this->_var['YEAR_DATE'] : ''; ?>');display_calendar(3);" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.png" alt="" /></a>
							
							<?php echo isset($this->_var['L_AT']) ? $this->_var['L_AT'] : ''; ?>
							<input type="text" size="2" maxlength="2" name="hour" value="<?php echo isset($this->_var['HOUR']) ? $this->_var['HOUR'] : ''; ?>" class="text" /> H <input type="text" size="2" maxlength="2" name="min" value="<?php echo isset($this->_var['MIN']) ? $this->_var['MIN'] : ''; ?>" class="text" />
						</label></dd>
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
		