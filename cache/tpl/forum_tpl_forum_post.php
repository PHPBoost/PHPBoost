		<script type='text/javascript'>
		<!--
		function check_form_post(){
			if(document.getElementById('contents').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_TEXT']) ? $this->_var['L_REQUIRE_TEXT'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('title').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_TITLE']) ? $this->_var['L_REQUIRE_TITLE'] : ''; ?>");
				return false;
		    }
			return true;
		}

		function hide_poll(divID)
		{
			if( document.getElementById(divID) ) 
			{
				document.getElementById(divID).style.display = 'block';
				if( document.getElementById('hidepoll_link') )
					document.getElementById('hidepoll_link').style.display = 'none';
			}
		}

		function add_field(i, i_max) 
		{
			var i2 = i + 1;

			document.getElementById('a'+i).innerHTML = '<input type="text" size="25" name="a'+i+'" value="" class="text" /><br /></span>';
			
			document.getElementById('a'+i).innerHTML += (i < i_max) ? '<p id="a'+i2+'"style="text-align:center"><a href="javascript:add_field('+i2+', '+i_max+')"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/plus.png" alt="+" /></a></a></p>' : '';
		}

		-->
		</script>
		
		<?php $this->tpl_include('forum_top'); ?>
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">&bull; <?php echo isset($this->_var['U_FORUM_CAT']) ? $this->_var['U_FORUM_CAT'] : ''; ?> &raquo; <?php echo isset($this->_var['U_TITLE_T']) ? $this->_var['U_TITLE_T'] : ''; ?> <span style="font-weight:normal"><em><?php echo isset($this->_var['DESC']) ? $this->_var['DESC'] : ''; ?></em></span></div>
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
							<legend><?php echo isset($this->_var['L_ACTION']) ? $this->_var['L_ACTION'] : ''; ?></legend>
							<p><?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?></p>
							<?php if( !isset($this->_block['cut_cat']) || !is_array($this->_block['cut_cat']) ) $this->_block['cut_cat'] = array();
foreach($this->_block['cut_cat'] as $cut_cat_key => $cut_cat_value) {
$_tmpb_cut_cat = &$this->_block['cut_cat'][$cut_cat_key]; ?>
							<dl>
								<dt><label for="to">* <?php echo isset($this->_var['L_CAT']) ? $this->_var['L_CAT'] : ''; ?></label></dt>
								<dd><label>
									<select id="to" name="to">
										<?php echo isset($_tmpb_cut_cat['CATEGORIES']) ? $_tmpb_cut_cat['CATEGORIES'] : ''; ?>
									</select>
								</label></dd>
							</dl>
							<?php } ?>
							<dl>
								<dt><label for="title">* <?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?></label></dt>
								<dd><label><input type="text" size="65" maxlength="100" id="title" name="title" value="<?php echo isset($this->_var['TITLE']) ? $this->_var['TITLE'] : ''; ?>" class="text" /></label></dd>
							</dl>
							<dl>
								<dt><label for="desc"><?php echo isset($this->_var['L_DESC']) ? $this->_var['L_DESC'] : ''; ?></label></dt>
								<dd><label><input type="text" size="50" maxlength="75" id="desc" name="desc" value="<?php echo isset($this->_var['DESC']) ? $this->_var['DESC'] : ''; ?>" class="text" /></label></dd>
							</dl>
							
							<label for="contents">* <?php echo isset($this->_var['L_MESSAGE']) ? $this->_var['L_MESSAGE'] : ''; ?></label>
							<?php $this->tpl_include('handle_bbcode'); ?>
							<label><textarea type="text" rows="25" cols="40" id="contents" name="contents"><?php echo isset($this->_var['CONTENTS']) ? $this->_var['CONTENTS'] : ''; ?></textarea></label>
							
							<br /><br />
							
							<?php if( !isset($this->_block['type']) || !is_array($this->_block['type']) ) $this->_block['type'] = array();
foreach($this->_block['type'] as $type_key => $type_value) {
$_tmpb_type = &$this->_block['type'][$type_key]; ?>
							<dl>
								<dt><label for="type"><?php echo isset($_tmpb_type['L_TYPE']) ? $_tmpb_type['L_TYPE'] : ''; ?></label></dt>
								<dd>
									<label><input type="radio" name="type" id="type" value="0" <?php echo isset($_tmpb_type['CHECKED_NORMAL']) ? $_tmpb_type['CHECKED_NORMAL'] : ''; ?> /> <?php echo isset($_tmpb_type['L_DEFAULT']) ? $_tmpb_type['L_DEFAULT'] : ''; ?></label>
									<label><input type="radio" name="type" value="1" <?php echo isset($_tmpb_type['CHECKED_POSTIT']) ? $_tmpb_type['CHECKED_POSTIT'] : ''; ?> /> <?php echo isset($_tmpb_type['L_POST_IT']) ? $_tmpb_type['L_POST_IT'] : ''; ?></label>
									<label><input type="radio" name="type" value="2" <?php echo isset($_tmpb_type['CHECKED_ANNONCE']) ? $_tmpb_type['CHECKED_ANNONCE'] : ''; ?> /> <?php echo isset($_tmpb_type['L_ANOUNCE']) ? $_tmpb_type['L_ANOUNCE'] : ''; ?></label>
								</dd>
							</dl>	
							<?php } ?>	
						</fieldset>	

						<fieldset>	
							<legend><?php echo isset($this->_var['L_POLL']) ? $this->_var['L_POLL'] : ''; ?></legend>
							<p id="hidepoll_link" style="text-align:center"><a href="javascript:hide_poll('hidepoll')"><?php echo isset($this->_var['L_OPEN_MENU_POLL']) ? $this->_var['L_OPEN_MENU_POLL'] : ''; ?></a></p>
							<div style="display:none;" id="hidepoll">
								<dl>
									<dt><label for="question">* <?php echo isset($this->_var['L_QUESTION']) ? $this->_var['L_QUESTION'] : ''; ?></label></dt>
									<dd><label><input type="text" size="40" name="question" id="question" value="<?php echo isset($this->_var['QUESTION']) ? $this->_var['QUESTION'] : ''; ?>" class="text" /></label></dd>
								</dl>
								<dl>
									<dt><label for="poll_type"><?php echo isset($this->_var['L_POLL_TYPE']) ? $this->_var['L_POLL_TYPE'] : ''; ?></label></dt>
									<dd>
										<label><input type="radio" name="poll_type" id="poll_type" value="0" <?php echo isset($this->_var['SELECTED_SIMPLE']) ? $this->_var['SELECTED_SIMPLE'] : ''; ?> /> <?php echo isset($this->_var['L_SINGLE']) ? $this->_var['L_SINGLE'] : ''; ?></label>
										<label><input type="radio" name="poll_type" value="1" <?php echo isset($this->_var['SELECTED_MULTIPLE']) ? $this->_var['SELECTED_MULTIPLE'] : ''; ?> /> <?php echo isset($this->_var['L_MULTIPLE']) ? $this->_var['L_MULTIPLE'] : ''; ?></label>	
									</dd>
								</dl>
								<?php if( !isset($this->_block['delete_poll']) || !is_array($this->_block['delete_poll']) ) $this->_block['delete_poll'] = array();
foreach($this->_block['delete_poll'] as $delete_poll_key => $delete_poll_value) {
$_tmpb_delete_poll = &$this->_block['delete_poll'][$delete_poll_key]; ?>
								<dl>
									<dt><label for="del_poll"><?php echo isset($this->_var['L_DELETE_POLL']) ? $this->_var['L_DELETE_POLL'] : ''; ?></label></dt>
									<dd><label><input type="checkbox" name="del_poll" id="del_poll" value="true" /></label></dd>
								</dl>
								<?php } ?>
								<dl>
									<dt><label><?php echo isset($this->_var['L_ANSWERS']) ? $this->_var['L_ANSWERS'] : ''; ?></label></dt>
									<dd><label>									
										<table style="border:0">
											<tr>
												<td>								
													<input type="text" size="25" name="a0" value="" class="text" /><br />
													<input type="text" size="25" name="a1" value="" class="text" /><br />
													<input type="text" size="25" name="a2" value="" class="text" /><br />
													<input type="text" size="25" name="a3" value="" class="text" /><br />
													<input type="text" size="25" name="a4" value="" class="text" /><br />
													<p id="a11"style="text-align:center"><a href="javascript:add_field(11, 15)"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/plus.png" alt="+" /></a></a></p>
												</td>
												<td>
													<input type="text" size="25" name="a5" value="" class="text" /><br />
													<input type="text" size="25" name="a6" value="" class="text" /><br />
													<input type="text" size="25" name="a7" value="" class="text" /><br />
													<input type="text" size="25" name="a8" value="" class="text" /><br />
													<input type="text" size="25" name="a9" value="" class="text" /><br />
													<p id="a16"style="text-align:center"><a href="javascript:add_field(16, 20)"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/form/plus.png" alt="+" /></a></a></p>
												</td>
											</tr>	
										</table>
									</label></dd>
								</dl>
							</div>
						</fieldset>	
						
						<fieldset class="fieldset_submit">
						<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
							<input type="hidden" name="idm" value="<?php echo isset($this->_var['IDM']) ? $this->_var['IDM'] : ''; ?>" />
							<input type="submit" name="post_topic" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
							&nbsp;&nbsp; 									
							<script type="text/javascript">
							<!--				
							document.write('<input value="<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>" onclick="XMLHttpRequest_preview(this.form);" type="button" class="submit" />');
							-->
							</script>						
							<noscript><input value="<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>" type="submit" name="prw_t" class="submit" /></noscript>
							&nbsp;&nbsp;
							<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />				
						</fieldset>
					</div>
				</form>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">&bull; <?php echo isset($this->_var['U_FORUM_CAT']) ? $this->_var['U_FORUM_CAT'] : ''; ?> &raquo; <?php echo isset($this->_var['U_TITLE_T']) ? $this->_var['U_TITLE_T'] : ''; ?> <span style="font-weight:normal"><em><?php echo isset($this->_var['DESC']) ? $this->_var['DESC'] : ''; ?></em></span></div>
		</div>
		
		<?php $this->tpl_include('forum_bottom'); ?>
		
		<?php if( !isset($this->_block['display']) || !is_array($this->_block['display']) ) $this->_block['display'] = array();
foreach($this->_block['display'] as $display_key => $display_value) {
$_tmpb_display = &$this->_block['display'][$display_key]; ?>
		<br /><br />
		<div class="forum_action" style="padding:5px;">
			<?php echo isset($_tmpb_display['ICON_DISPLAY_MSG']) ? $_tmpb_display['ICON_DISPLAY_MSG'] : ''; ?>
			<a href="action<?php echo isset($_tmpb_display['U_ACTION_MSG_DISPLAY']) ? $_tmpb_display['U_ACTION_MSG_DISPLAY'] : ''; ?>"><?php echo isset($_tmpb_display['L_EXPLAIN_DISPLAY_MSG']) ? $_tmpb_display['L_EXPLAIN_DISPLAY_MSG'] : ''; ?></a>
		</div>		
		<?php } ?>	
		