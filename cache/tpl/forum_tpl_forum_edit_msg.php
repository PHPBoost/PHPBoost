		<script type='text/javascript'>
		<!--
		function check_form_post(){
			if(document.getElementById('contents').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_TEXT']) ? $this->_var['L_REQUIRE_TEXT'] : ''; ?>");
				return false;
			}
		}

		-->
		</script>

		<?php $this->tpl_include('forum_top'); ?>
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top"><a href="index.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_FORUM_INDEX']) ? $this->_var['L_FORUM_INDEX'] : ''; ?></a> &raquo; <?php echo isset($this->_var['U_FORUM_CAT']) ? $this->_var['U_FORUM_CAT'] : ''; ?> &raquo; <?php echo isset($this->_var['U_TITLE_T']) ? $this->_var['U_TITLE_T'] : ''; ?> <span style="font-weight:normal"><em><?php echo isset($this->_var['DESC']) ? $this->_var['DESC'] : ''; ?></em></span></div>
			<div class="module_contents">
				<form action="<?php echo isset($this->_var['U_ACTION']) ? $this->_var['U_ACTION'] : ''; ?>" method="post" onsubmit="return check_form_post();">
					<?php if( !isset($this->_block['error_handler']) || !is_array($this->_block['error_handler']) ) $this->_block['error_handler'] = array();
foreach($this->_block['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$this->_block['error_handler'][$error_handler_key]; ?>
					<br />	
					<span id="errorh"></span>
					<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>">
						<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
					</div>
					<br />		
					<?php } ?>

					<?php if( !isset($this->_block['show_msg']) || !is_array($this->_block['show_msg']) ) $this->_block['show_msg'] = array();
foreach($this->_block['show_msg'] as $show_msg_key => $show_msg_value) {
$_tmpb_show_msg = &$this->_block['show_msg'][$show_msg_key]; ?>
					<div class="module_position">					
						<div class="module_top_l"></div>		
						<div class="module_top_r"></div>
						<div class="module_top">
							<span style="float:left;"><?php echo isset($_tmpb_show_msg['L_PREVIEW']) ? $_tmpb_show_msg['L_PREVIEW'] : ''; ?></span>
							<span style="float:right;"></span>&nbsp;
						</div>
					</div>	
					<div class="msg_position">
						<div class="msg_container">
							<div class="msg_pseudo_mbr"></div>
							<div class="msg_top_row">
								<div style="float:left;">&nbsp;&nbsp;<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/ancre.png" alt="" /> <?php echo isset($_tmpb_show_msg['DATE']) ? $_tmpb_show_msg['DATE'] : ''; ?></div>
								<div style="float:right;"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/quote.png" alt="" title="" />&nbsp;&nbsp;</div>
							</div>
							<div class="msg_contents_container">
								<div class="msg_info_mbr">
								</div>
								<div class="msg_contents">
									<div class="msg_contents_overflow">
										<?php echo isset($_tmpb_show_msg['CONTENTS']) ? $_tmpb_show_msg['CONTENTS'] : ''; ?>
									</div>
								</div>
							</div>
						</div>	
						<div class="msg_sign">		
							<hr />
							<span style="float:left;">
								<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/pm.png" alt="pm" />
							</span>
							<span style="float:right;font-size:10px;">
							</span>&nbsp;
						</div>	
					</div>
					<div class="msg_position">		
						<div class="msg_bottom_l"></div>		
						<div class="msg_bottom_r"></div>
						<div class="msg_bottom">&nbsp;</div>
					</div>
					<br /><br />
					<?php } ?>
					
					<div class="fieldset_content">
						<fieldset>
							<legend><?php echo isset($this->_var['L_EDIT_MESSAGE']) ? $this->_var['L_EDIT_MESSAGE'] : ''; ?></legend>
							<p><?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?></p>
							<label for="contents">* <?php echo isset($this->_var['L_MESSAGE']) ? $this->_var['L_MESSAGE'] : ''; ?></label>
							<?php $this->tpl_include('handle_bbcode'); ?>
							<label><textarea type="text" rows="25" cols="66" id="contents" name="contents"><?php echo isset($this->_var['CONTENTS']) ? $this->_var['CONTENTS'] : ''; ?></textarea></label>
						</fieldset>
						
						<fieldset class="fieldset_submit">
						<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
							<input type="hidden" name="p_update" value="<?php echo isset($this->_var['P_UPDATE']) ? $this->_var['P_UPDATE'] : ''; ?>" />
							<input type="submit" name="edit_msg" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
							&nbsp;&nbsp; 									
							<input value="<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>" type="submit" name="prw" id="previs_msg" class="submit" />
							<script type="text/javascript">
							<!--				
							document.getElementById('previs_msg').style.display = 'none';
							document.write('<input value="<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>" onclick="XMLHttpRequest_preview(this.form);" type="button" class="submit" />');
							-->
							</script>
							&nbsp;&nbsp;
							<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />				
						</fieldset>
					</div>		
				</form>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"><a href="index.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_FORUM_INDEX']) ? $this->_var['L_FORUM_INDEX'] : ''; ?></a> &raquo; <?php echo isset($this->_var['U_FORUM_CAT']) ? $this->_var['U_FORUM_CAT'] : ''; ?> &raquo; <?php echo isset($this->_var['U_TITLE_T']) ? $this->_var['U_TITLE_T'] : ''; ?> <span style="font-weight:normal"><em><?php echo isset($this->_var['DESC']) ? $this->_var['DESC'] : ''; ?></em></span></div>
		</div>
		
		<?php $this->tpl_include('forum_bottom'); ?>
		