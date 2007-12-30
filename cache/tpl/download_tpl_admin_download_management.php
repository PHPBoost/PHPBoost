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
		
		<?php if( !isset($this->_block['list']) || !is_array($this->_block['list']) ) $this->_block['list'] = array();
foreach($this->_block['list'] as $list_key => $list_value) {
$_tmpb_list = &$this->_block['list'][$list_key]; ?>
		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("<?php echo isset($this->_var['L_DEL_ENTRY']) ? $this->_var['L_DEL_ENTRY'] : ''; ?>");
		}
		-->
		</script>

		
		<table class="module_table">
			<tr style="text-align:center;">
				<th style="width:35%">
					<?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?>
				</th>
				<th>
					<?php echo isset($this->_var['L_SIZE']) ? $this->_var['L_SIZE'] : ''; ?>
				</th>
				<th>
					<?php echo isset($this->_var['L_CATEGORY']) ? $this->_var['L_CATEGORY'] : ''; ?>
				</th>
				<th>
					<?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?>
				</th>
				<th>
					<?php echo isset($this->_var['L_DATE']) ? $this->_var['L_DATE'] : ''; ?>
				</th>
				<th>
					<?php echo isset($this->_var['L_APROB']) ? $this->_var['L_APROB'] : ''; ?>
				</th>
				<th>
					<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>
				</th>
				<th>
					<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>
				</th>
			</tr>
			
			<?php if( !isset($_tmpb_list['download']) || !is_array($_tmpb_list['download']) ) $_tmpb_list['download'] = array();
foreach($_tmpb_list['download'] as $download_key => $download_value) {
$_tmpb_download = &$_tmpb_list['download'][$download_key]; ?>
			
			<tr style="text-align:center;"> 
				<td class="row2"> 
					<a href="../download/download.php?cat=<?php echo isset($_tmpb_download['IDCAT']) ? $_tmpb_download['IDCAT'] : ''; ?>&amp;id=<?php echo isset($_tmpb_download['ID']) ? $_tmpb_download['ID'] : ''; ?>"><?php echo isset($_tmpb_download['TITLE']) ? $_tmpb_download['TITLE'] : ''; ?></a>
				</td>
				<td class="row2"> 
					<?php echo isset($_tmpb_download['SIZE']) ? $_tmpb_download['SIZE'] : ''; ?>
				</td>
				<td class="row2"> 
					<?php echo isset($_tmpb_download['CAT']) ? $_tmpb_download['CAT'] : ''; ?>
				</td>
				<td class="row2"> 
					<?php echo isset($_tmpb_download['PSEUDO']) ? $_tmpb_download['PSEUDO'] : ''; ?>
				</td>
				<td class="row2">
					<?php echo isset($_tmpb_download['DATE']) ? $_tmpb_download['DATE'] : ''; ?>
				</td>
				<td class="row2">
					<?php echo isset($_tmpb_download['APROBATION']) ? $_tmpb_download['APROBATION'] : ''; ?>
					<br />
					<span class="text_small"><?php echo isset($_tmpb_download['VISIBLE']) ? $_tmpb_download['VISIBLE'] : ''; ?></span>
				</td>
				<td class="row2"> 
					<a href="admin_download.php?id=<?php echo isset($_tmpb_download['ID']) ? $_tmpb_download['ID'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/edit.png" alt="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" title="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" /></a>
				</td>
				<td class="row2">
					<a href="admin_download.php?delete=1&amp;id=<?php echo isset($_tmpb_download['ID']) ? $_tmpb_download['ID'] : ''; ?>" onClick="javascript:return Confirm();"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/delete.png" alt="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" title="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" /></a>
				</td>
			</tr>
			<?php } ?>

		</table>

		<br /><br />
		<p style="text-align: center;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?></p>
		<?php } ?>

		<?php if( !isset($this->_block['download']) || !is_array($this->_block['download']) ) $this->_block['download'] = array();
foreach($this->_block['download'] as $download_key => $download_value) {
$_tmpb_download = &$this->_block['download'][$download_key]; ?>
		<script type="text/javascript" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.js"></script>
		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('title').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_TITLE']) ? $this->_var['L_REQUIRE_TITLE'] : ''; ?>");
				return false;
			}
			if(document.getElementById('idcat').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_CAT']) ? $this->_var['L_REQUIRE_CAT'] : ''; ?>");
				return false;
			}
			if(document.getElementById('contents').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_TEXT']) ? $this->_var['L_REQUIRE_TEXT'] : ''; ?>");
				return false;
			}
			return true;
		}
		-->
		</script>
		
		<?php if( !isset($_tmpb_download['preview']) || !is_array($_tmpb_download['preview']) ) $_tmpb_download['preview'] = array();
foreach($_tmpb_download['preview'] as $preview_key => $preview_value) {
$_tmpb_preview = &$_tmpb_download['preview'][$preview_key]; ?>
		<table class="module_table">
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
									<?php echo isset($_tmpb_preview['TITLE']) ? $_tmpb_preview['TITLE'] : ''; ?>
								</div>
								<div style="float:right">
									<?php echo isset($_tmpb_download['COM']) ? $_tmpb_download['COM'] : ''; ?>
								</div>
							</div>
							<div class="module_contents">
								<p>					
									<strong><?php echo isset($this->_var['L_DESC']) ? $this->_var['L_DESC'] : ''; ?>:</strong> <?php echo isset($_tmpb_preview['CONTENTS']) ? $_tmpb_preview['CONTENTS'] : ''; ?>
									<br /><br />
									<strong><?php echo isset($this->_var['L_CATEGORY']) ? $this->_var['L_CATEGORY'] : ''; ?>:</strong> 
									<a href="../download/download.php?cat=<?php echo isset($_tmpb_preview['IDCAT']) ? $_tmpb_preview['IDCAT'] : ''; ?>"><?php echo isset($_tmpb_preview['CAT']) ? $_tmpb_preview['CAT'] : ''; ?></a><br />
									
									<strong><?php echo isset($this->_var['L_DATE']) ? $this->_var['L_DATE'] : ''; ?>:</strong> <?php echo isset($_tmpb_preview['DATE']) ? $_tmpb_preview['DATE'] : ''; ?><br />									
									<strong><?php echo isset($this->_var['L_DOWNLOAD']) ? $this->_var['L_DOWNLOAD'] : ''; ?>:</strong> <?php echo isset($_tmpb_preview['COMPT']) ? $_tmpb_preview['COMPT'] : ''; ?>	
								</p>
								<p style="text-align: center;">					
									<a href="../download/count.php?id=<?php echo isset($_tmpb_preview['IDURL']) ? $_tmpb_preview['IDURL'] : ''; ?>"><img src="<?php echo isset($_tmpb_preview['MODULE_DATA_PATH']) ? $_tmpb_preview['MODULE_DATA_PATH'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/bouton_dl.gif" alt="" /></a>
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

		<?php if( !isset($_tmpb_download['error_handler']) || !is_array($_tmpb_download['error_handler']) ) $_tmpb_download['error_handler'] = array();
foreach($_tmpb_download['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$_tmpb_download['error_handler'][$error_handler_key]; ?>
		<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
					<br />	
				</div>
		</div>
		<?php } ?>
		
		<form action="admin_download.php" name="form" method="post" style="margin:auto;" onsubmit="return check_form();" class="fieldset_content">
			<fieldset>
				<legend><?php echo isset($this->_var['L_EDIT_FILE']) ? $this->_var['L_EDIT_FILE'] : ''; ?></legend>
				<p><?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?></p>
				<dl>
					<dt><label for="title">* <?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?></label></dt>
					<dd><label><input type="text" size="65" maxlength="100" id="title" name="title" value="<?php echo isset($_tmpb_download['TITLE']) ? $_tmpb_download['TITLE'] : ''; ?>" class="text" /></label></dd>
				</dl>
				<dl>
					<dt><label for="idcat">* <?php echo isset($this->_var['L_CATEGORY']) ? $this->_var['L_CATEGORY'] : ''; ?></label></dt>
					<dd><label>
						<select id="idcat" name="idcat">				
						<?php if( !isset($_tmpb_download['select']) || !is_array($_tmpb_download['select']) ) $_tmpb_download['select'] = array();
foreach($_tmpb_download['select'] as $select_key => $select_value) {
$_tmpb_select = &$_tmpb_download['select'][$select_key]; ?>				
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
				<label for="contents"><?php echo isset($this->_var['L_CONTENTS']) ? $this->_var['L_CONTENTS'] : ''; ?></label>
				<label>
					<?php $this->tpl_include('handle_bbcode'); ?>
					<textarea type="text" rows="20" cols="90" id="contents" name="contents"><?php echo isset($_tmpb_download['CONTENTS']) ? $_tmpb_download['CONTENTS'] : ''; ?></textarea> 
					<br />
				</label>
				<br />
				<dl class="overflow_visible">
					<dt><label for="release_date">* <?php echo isset($this->_var['L_RELEASE_DATE']) ? $this->_var['L_RELEASE_DATE'] : ''; ?></label></dt>
					<dd>
						<label><input type="radio" value="2" name="visible" <?php echo isset($_tmpb_download['VISIBLE_WAITING']) ? $_tmpb_download['VISIBLE_WAITING'] : ''; ?> /> 
					<input type="text" size="8" maxlength="8" id="start" name="start" value="<?php echo isset($_tmpb_download['START']) ? $_tmpb_download['START'] : ''; ?>" class="text" /> 
					<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
						<div id="start_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
						</div>
					</div>
					<a onClick="xmlhttprequest_calendar('start_date', '?input_field=start&amp;field=start_date&amp;d=<?php echo isset($_tmpb_download['DAY_RELEASE_S']) ? $_tmpb_download['DAY_RELEASE_S'] : ''; ?>&amp;m=<?php echo isset($_tmpb_download['MONTH_RELEASE_S']) ? $_tmpb_download['MONTH_RELEASE_S'] : ''; ?>&amp;y=<?php echo isset($_tmpb_download['YEAR_RELEASE_S']) ? $_tmpb_download['YEAR_RELEASE_S'] : ''; ?>');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.png" alt="" /></a>
					
					<?php echo isset($this->_var['L_UNTIL']) ? $this->_var['L_UNTIL'] : ''; ?>&nbsp;
					
					<input type="text" size="8" maxlength="8" id="end" name="end" value="<?php echo isset($_tmpb_download['END']) ? $_tmpb_download['END'] : ''; ?>" class="text" />					
					<div style="position:relative;z-index:100;top:6px;margin-left:155px;float:left;display:none;" id="calendar2">
						<div id="end_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);">							
						</div>
					</div>
					<a onClick="xmlhttprequest_calendar('end_date', '?input_field=end&amp;field=end_date&amp;d=<?php echo isset($_tmpb_download['DAY_RELEASE_E']) ? $_tmpb_download['DAY_RELEASE_E'] : ''; ?>&amp;m=<?php echo isset($_tmpb_download['MONTH_RELEASE_E']) ? $_tmpb_download['MONTH_RELEASE_E'] : ''; ?>&amp;y=<?php echo isset($_tmpb_download['YEAR_RELEASE_E']) ? $_tmpb_download['YEAR_RELEASE_E'] : ''; ?>');display_calendar(2);" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.png" alt="" /></a></label>
					<br />
					<label><input type="radio" value="1" name="visible" <?php echo isset($_tmpb_download['VISIBLE_ENABLED']) ? $_tmpb_download['VISIBLE_ENABLED'] : ''; ?> id="release_date" /> <?php echo isset($this->_var['L_IMMEDIATE']) ? $this->_var['L_IMMEDIATE'] : ''; ?></label>
					<br />
					<label><input type="radio" value="0" name="visible" <?php echo isset($_tmpb_download['VISIBLE_UNAPROB']) ? $_tmpb_download['VISIBLE_UNAPROB'] : ''; ?> /> <?php echo isset($this->_var['L_UNAPROB']) ? $this->_var['L_UNAPROB'] : ''; ?></label>
					</dd>
				</dl>
				<dl class="overflow_visible">
					<dt><label for="current_date">* <?php echo isset($this->_var['L_DOWNLOAD_DATE']) ? $this->_var['L_DOWNLOAD_DATE'] : ''; ?></label></dt>
					<dd><label>
						<input type="text" size="8" maxlength="8" id="current_date" name="current_date" value="<?php echo isset($_tmpb_download['CURRENT_DATE']) ? $_tmpb_download['CURRENT_DATE'] : ''; ?>" class="text" /> 
						<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar3">
							<div id="current" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);">							
							</div>
						</div>
						<a onClick="xmlhttprequest_calendar('current', '?input_field=current_date&amp;field=current&amp;d=<?php echo isset($_tmpb_download['DAY_DATE']) ? $_tmpb_download['DAY_DATE'] : ''; ?>&amp;m=<?php echo isset($_tmpb_download['MONTH_DATE']) ? $_tmpb_download['MONTH_DATE'] : ''; ?>&amp;y=<?php echo isset($_tmpb_download['YEAR_DATE']) ? $_tmpb_download['YEAR_DATE'] : ''; ?>');display_calendar(3);" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.png" alt="" /></a>
						
						<?php echo isset($this->_var['L_AT']) ? $this->_var['L_AT'] : ''; ?>
						<input type="text" size="2" maxlength="2" name="hour" value="<?php echo isset($_tmpb_download['HOUR']) ? $_tmpb_download['HOUR'] : ''; ?>" class="text" /> H <input type="text" size="2" maxlength="2" name="min" value="<?php echo isset($_tmpb_download['MIN']) ? $_tmpb_download['MIN'] : ''; ?>" class="text" />
					</label></dd>
				</dl>
			</fieldset>			
			<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?></legend>
				<input type="hidden" name="id" value="<?php echo isset($_tmpb_download['IDURL']) ? $_tmpb_download['IDURL'] : ''; ?>" />
				<input type="hidden" name="user_id" value="<?php echo isset($_tmpb_download['USER_ID']) ? $_tmpb_download['USER_ID'] : ''; ?>" />
				<input type="submit" name="valid" value="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" class="submit" />
				&nbsp;&nbsp; 
				<input type="submit" name="previs" value="<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>" class="submit" />
				&nbsp;&nbsp; 
				<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />				
			</fieldset>	
		</form>
		<?php } ?>
		
		</div>
		