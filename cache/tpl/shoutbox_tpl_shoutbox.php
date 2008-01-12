		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('contents').value == "") {
				alert("<?php echo isset($this->_var['L_ALERT_TEXT']) ? $this->_var['L_ALERT_TEXT'] : ''; ?>");
				return false;
		    }
			return true;
		}
		function Confirm_shout() {
		return confirm("<?php echo isset($this->_var['L_DELETE_MSG']) ? $this->_var['L_DELETE_MSG'] : ''; ?>");
		}

		-->
		</script>

		<form action="shoutbox.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : '';  echo isset($this->_var['UPDATE']) ? $this->_var['UPDATE'] : ''; ?>" method="post" onsubmit="return check_form();" class="fieldset_mini">
			<fieldset>
				<legend><?php echo isset($this->_var['L_ADD_MSG']) ? $this->_var['L_ADD_MSG'] : '';  echo isset($this->_var['L_UPDATE_MSG']) ? $this->_var['L_UPDATE_MSG'] : ''; ?></legend>
				<p><?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?></p>
				
				<?php if( isset($this->_var['C_VISIBLE_SHOUT']) && $this->_var['C_VISIBLE_SHOUT'] ) { ?>
				<dl>
					<dt><label for="shout_pseudo">* <?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?></label></dt>
					<dd><label><input type="text" size="25" maxlength="25" name="shout_pseudo" id="shout_pseudo" value="<?php echo isset($this->_var['SHOUTBOX_PSEUDO']) ? $this->_var['SHOUTBOX_PSEUDO'] : ''; ?>" class="text" /></label></dd>
				</dl>
				<?php } ?>

				<label for="shout_contents">* <?php echo isset($this->_var['L_MESSAGE']) ? $this->_var['L_MESSAGE'] : ''; ?></label>
				<?php $this->tpl_include('handle_bbcode'); ?>
				<label><textarea rows="10" cols="50" id="shout_contents" name="shout_contents"><?php echo isset($this->_var['CONTENTS']) ? $this->_var['CONTENTS'] : ''; ?></textarea></label>
				<p>
					<strong><?php echo isset($this->_var['L_FORBIDDEN_TAGS']) ? $this->_var['L_FORBIDDEN_TAGS'] : ''; ?></strong> <?php echo isset($this->_var['DISPLAY_FORBIDDEN_TAGS']) ? $this->_var['DISPLAY_FORBIDDEN_TAGS'] : ''; ?>
				</p>
			</fieldset>
			<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
				<?php if( isset($this->_var['C_HIDDEN_SHOUT']) && $this->_var['C_HIDDEN_SHOUT'] ) { ?>
					<input size="16" maxlength="25" type="hidden" class="text" name="shout_pseudo" value="<?php echo isset($this->_var['SHOUTBOX_PSEUDO']) ? $this->_var['SHOUTBOX_PSEUDO'] : ''; ?>" /></label>
				<?php } ?>
				
				<input type="hidden" name="shout_contents_ftags" id="shout_contents_ftags" value="<?php echo isset($this->_var['FORBIDDEN_TAGS']) ? $this->_var['FORBIDDEN_TAGS'] : ''; ?>" />
				<input type="submit" name="shoutbox" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
				<script type="text/javascript">
				<!--				
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
		<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>">
			<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
		</div>
		<br />		
		<?php } ?>
		
		<div class="msg_position">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top" style="text-align:center;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>&nbsp;</div>	
		</div>
		<?php if( !isset($this->_block['shoutbox']) || !is_array($this->_block['shoutbox']) ) $this->_block['shoutbox'] = array();
foreach($this->_block['shoutbox'] as $shoutbox_key => $shoutbox_value) {
$_tmpb_shoutbox = &$this->_block['shoutbox'][$shoutbox_key]; ?>
		<div class="msg_position">
			<div class="msg_container">
				<span id="m<?php echo isset($_tmpb_shoutbox['ID']) ? $_tmpb_shoutbox['ID'] : ''; ?>"></span>
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						<?php echo isset($_tmpb_shoutbox['USER_ONLINE']) ? $_tmpb_shoutbox['USER_ONLINE'] : ''; echo ' '; echo isset($_tmpb_shoutbox['USER_PSEUDO']) ? $_tmpb_shoutbox['USER_PSEUDO'] : ''; ?>
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="<?php echo isset($_tmpb_shoutbox['U_ANCHOR']) ? $_tmpb_shoutbox['U_ANCHOR'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/ancre.png" alt="<?php echo isset($_tmpb_shoutbox['ID']) ? $_tmpb_shoutbox['ID'] : ''; ?>" /></a> <?php echo isset($_tmpb_shoutbox['DATE']) ? $_tmpb_shoutbox['DATE'] : ''; ?></div>
					<div style="float:right;"><?php echo isset($_tmpb_shoutbox['EDIT']) ? $_tmpb_shoutbox['EDIT'] : '';  echo isset($_tmpb_shoutbox['DEL']) ? $_tmpb_shoutbox['DEL'] : ''; ?>&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;"><?php echo isset($_tmpb_shoutbox['USER_RANK']) ? $_tmpb_shoutbox['USER_RANK'] : ''; ?></p>
						<p style="text-align:center;"><?php echo isset($_tmpb_shoutbox['USER_IMG_ASSOC']) ? $_tmpb_shoutbox['USER_IMG_ASSOC'] : ''; ?></p>
						<p style="text-align:center;"><?php echo isset($_tmpb_shoutbox['USER_AVATAR']) ? $_tmpb_shoutbox['USER_AVATAR'] : ''; ?></p>
						<p style="text-align:center;"><?php echo isset($_tmpb_shoutbox['USER_GROUP']) ? $_tmpb_shoutbox['USER_GROUP'] : ''; ?></p>
						<?php echo isset($_tmpb_shoutbox['USER_SEX']) ? $_tmpb_shoutbox['USER_SEX'] : ''; ?>
						<?php echo isset($_tmpb_shoutbox['USER_DATE']) ? $_tmpb_shoutbox['USER_DATE'] : ''; ?><br />
						<?php echo isset($_tmpb_shoutbox['USER_MSG']) ? $_tmpb_shoutbox['USER_MSG'] : ''; ?><br />
						<?php echo isset($_tmpb_shoutbox['USER_LOCAL']) ? $_tmpb_shoutbox['USER_LOCAL'] : ''; ?>
					</div>
					<div class="msg_contents">
						<div class="msg_contents_overflow">
							<?php echo isset($_tmpb_shoutbox['CONTENTS']) ? $_tmpb_shoutbox['CONTENTS'] : ''; ?>
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign">				
				<div class="msg_sign_overflow">
					<?php echo isset($_tmpb_shoutbox['USER_SIGN']) ? $_tmpb_shoutbox['USER_SIGN'] : ''; ?>
				</div>				
				<hr />
				<div style="float:left;">
					<?php echo isset($_tmpb_shoutbox['U_MEMBER_PM']) ? $_tmpb_shoutbox['U_MEMBER_PM'] : ''; echo ' '; echo isset($_tmpb_shoutbox['USER_MAIL']) ? $_tmpb_shoutbox['USER_MAIL'] : ''; echo ' '; echo isset($_tmpb_shoutbox['USER_MSN']) ? $_tmpb_shoutbox['USER_MSN'] : ''; echo ' '; echo isset($_tmpb_shoutbox['USER_YAHOO']) ? $_tmpb_shoutbox['USER_YAHOO'] : ''; echo ' '; echo isset($_tmpb_shoutbox['USER_WEB']) ? $_tmpb_shoutbox['USER_WEB'] : ''; ?>
				</div>
				<div style="float:right;font-size:10px;">
					<?php echo isset($_tmpb_shoutbox['WARNING']) ? $_tmpb_shoutbox['WARNING'] : ''; echo ' '; echo isset($_tmpb_shoutbox['PUNISHMENT']) ? $_tmpb_shoutbox['PUNISHMENT'] : ''; ?>
				</div>&nbsp;
			</div>	
		</div>				
		<?php } ?>		
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>&nbsp;</div>
		</div>
		