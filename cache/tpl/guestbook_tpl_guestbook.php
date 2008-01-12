		<script type="text/javascript">
		<!--
		function check_form_or(){
			if(document.getElementById('guestbook_contents').value == "") {
				alert("<?php echo isset($this->_var['L_ALERT_TEXT']) ? $this->_var['L_ALERT_TEXT'] : ''; ?>");
				return false;
		    }
			return true;
		}

		function Confirm() {
		return confirm("<?php echo isset($this->_var['L_DELETE_MSG']) ? $this->_var['L_DELETE_MSG'] : ''; ?>");
		}
					
		-->
		</script>

		<form action="guestbook.php<?php echo isset($this->_var['UPDATE']) ? $this->_var['UPDATE'] : ''; ?>" method="post" onsubmit="return check_form_or();" class="fieldset_mini">
			<fieldset>
				<legend><?php echo isset($this->_var['L_ADD_MSG']) ? $this->_var['L_ADD_MSG'] : '';  echo isset($this->_var['L_UPDATE_MSG']) ? $this->_var['L_UPDATE_MSG'] : ''; ?></legend>
				<p><?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?></p>
				
				<?php if( !isset($this->_block['visible_guestbook']) || !is_array($this->_block['visible_guestbook']) ) $this->_block['visible_guestbook'] = array();
foreach($this->_block['visible_guestbook'] as $visible_guestbook_key => $visible_guestbook_value) {
$_tmpb_visible_guestbook = &$this->_block['visible_guestbook'][$visible_guestbook_key]; ?>
				<dl>
					<dt><label for="guestbook_pseudo">* <?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?></label></dt>
					<dd><label><input type="text" size="25" maxlength="25" name="guestbook_pseudo" id="guestbook_pseudo" value="<?php echo isset($_tmpb_visible_guestbook['PSEUDO']) ? $_tmpb_visible_guestbook['PSEUDO'] : ''; ?>" class="text" /></label></dd>
				</dl>
				<?php } ?>			
				
				<label for="guestbook_contents">* <?php echo isset($this->_var['L_MESSAGE']) ? $this->_var['L_MESSAGE'] : ''; ?></label>
				<?php $this->tpl_include('handle_bbcode'); ?>
				<label><textarea rows="10" cols="47" id="guestbook_contents" name="guestbook_contents"><?php echo isset($this->_var['CONTENTS']) ? $this->_var['CONTENTS'] : ''; ?></textarea></label>
				<p>
					<strong><?php echo isset($this->_var['L_FORBIDDEN_TAGS']) ? $this->_var['L_FORBIDDEN_TAGS'] : ''; ?></strong> <?php echo isset($this->_var['DISPLAY_FORBIDDEN_TAGS']) ? $this->_var['DISPLAY_FORBIDDEN_TAGS'] : ''; ?>
				</p>
			</fieldset>
			<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
				<?php if( !isset($this->_block['hidden_guestbook']) || !is_array($this->_block['hidden_guestbook']) ) $this->_block['hidden_guestbook'] = array();
foreach($this->_block['hidden_guestbook'] as $hidden_guestbook_key => $hidden_guestbook_value) {
$_tmpb_hidden_guestbook = &$this->_block['hidden_guestbook'][$hidden_guestbook_key]; ?>
					<input type="hidden" size="25" maxlength="25" name="guestbook_pseudo" value="<?php echo isset($_tmpb_hidden_guestbook['PSEUDO']) ? $_tmpb_hidden_guestbook['PSEUDO'] : ''; ?>" class="text" />
				<?php } ?>
				
				<input type="hidden" name="guestbook_contents_ftags" id="guestbook_contents_ftags" value="<?php echo isset($this->_var['FORBIDDEN_TAGS']) ? $this->_var['FORBIDDEN_TAGS'] : ''; ?>" />
				<input type="submit" name="guestbook" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
				
				<input value="<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>" type="submit" name="previs" id="previs_guestbook" class="submit" />
				<script type="text/javascript">
				<!--				
				document.getElementById('previs_guestbook').style.display = 'none';
				document.write('<input value="<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>" onclick="XMLHttpRequest_preview(this.form);" type="button" class="submit" />');
				-->
				</script>
				
				<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />			
			</fieldset>	
		</form>

		<br />
		<?php if( !isset($this->_block['error_handler']) || !is_array($this->_block['error_handler']) ) $this->_block['error_handler'] = array();
foreach($this->_block['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$this->_block['error_handler'][$error_handler_key]; ?>
		<span id="errorh"></span>
		<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
			<br />	
		</div>
		<br />		
		<?php } ?>
		
		<div class="msg_position">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top" style="text-align:center;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>&nbsp;</div>	
		</div>
		<?php if( !isset($this->_block['guestbook']) || !is_array($this->_block['guestbook']) ) $this->_block['guestbook'] = array();
foreach($this->_block['guestbook'] as $guestbook_key => $guestbook_value) {
$_tmpb_guestbook = &$this->_block['guestbook'][$guestbook_key]; ?>
		<div class="msg_position">
			<div class="msg_container">
				<span id="m<?php echo isset($_tmpb_guestbook['ID']) ? $_tmpb_guestbook['ID'] : ''; ?>"></span>
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						<?php echo isset($_tmpb_guestbook['USER_ONLINE']) ? $_tmpb_guestbook['USER_ONLINE'] : ''; echo ' '; echo isset($_tmpb_guestbook['USER_PSEUDO']) ? $_tmpb_guestbook['USER_PSEUDO'] : ''; ?>
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="<?php echo isset($_tmpb_guestbook['U_ANCHOR']) ? $_tmpb_guestbook['U_ANCHOR'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/ancre.png" alt="<?php echo isset($_tmpb_guestbook['ID']) ? $_tmpb_guestbook['ID'] : ''; ?>" /></a> <?php echo isset($_tmpb_guestbook['DATE']) ? $_tmpb_guestbook['DATE'] : ''; ?></div>
					<div style="float:right;"><?php echo isset($_tmpb_guestbook['EDIT']) ? $_tmpb_guestbook['EDIT'] : '';  echo isset($_tmpb_guestbook['DEL']) ? $_tmpb_guestbook['DEL'] : ''; ?>&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;"><?php echo isset($_tmpb_guestbook['USER_RANK']) ? $_tmpb_guestbook['USER_RANK'] : ''; ?></p>
						<p style="text-align:center;"><?php echo isset($_tmpb_guestbook['USER_IMG_ASSOC']) ? $_tmpb_guestbook['USER_IMG_ASSOC'] : ''; ?></p>
						<p style="text-align:center;"><?php echo isset($_tmpb_guestbook['USER_AVATAR']) ? $_tmpb_guestbook['USER_AVATAR'] : ''; ?></p>
						<p style="text-align:center;"><?php echo isset($_tmpb_guestbook['USER_GROUP']) ? $_tmpb_guestbook['USER_GROUP'] : ''; ?></p>
						<?php echo isset($_tmpb_guestbook['USER_SEX']) ? $_tmpb_guestbook['USER_SEX'] : ''; ?>
						<?php echo isset($_tmpb_guestbook['USER_DATE']) ? $_tmpb_guestbook['USER_DATE'] : ''; ?><br />
						<?php echo isset($_tmpb_guestbook['USER_MSG']) ? $_tmpb_guestbook['USER_MSG'] : ''; ?><br />
						<?php echo isset($_tmpb_guestbook['USER_LOCAL']) ? $_tmpb_guestbook['USER_LOCAL'] : ''; ?>
					</div>
					<div class="msg_contents">
						<div class="msg_contents_overflow">
							<?php echo isset($_tmpb_guestbook['CONTENTS']) ? $_tmpb_guestbook['CONTENTS'] : ''; ?>
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign">				
				<div class="msg_sign_overflow">
					<?php echo isset($_tmpb_guestbook['USER_SIGN']) ? $_tmpb_guestbook['USER_SIGN'] : ''; ?>	
				</div>				
				<hr />
				<div style="float:left;">
					<?php echo isset($_tmpb_guestbook['U_MEMBER_PM']) ? $_tmpb_guestbook['U_MEMBER_PM'] : ''; echo ' '; echo isset($_tmpb_guestbook['USER_MAIL']) ? $_tmpb_guestbook['USER_MAIL'] : ''; echo ' '; echo isset($_tmpb_guestbook['USER_MSN']) ? $_tmpb_guestbook['USER_MSN'] : ''; echo ' '; echo isset($_tmpb_guestbook['USER_YAHOO']) ? $_tmpb_guestbook['USER_YAHOO'] : ''; echo ' '; echo isset($_tmpb_guestbook['USER_WEB']) ? $_tmpb_guestbook['USER_WEB'] : ''; ?>
				</div>
				<div style="float:right;font-size:10px;">
					<?php echo isset($_tmpb_guestbook['WARNING']) ? $_tmpb_guestbook['WARNING'] : ''; echo ' '; echo isset($_tmpb_guestbook['PUNISHMENT']) ? $_tmpb_guestbook['PUNISHMENT'] : ''; ?>
				</div>&nbsp;
			</div>	
		</div>				
		<?php } ?>		
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>&nbsp;</div>
		</div>
		