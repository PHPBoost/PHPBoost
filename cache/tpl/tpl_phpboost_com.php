	<?php if( !isset($this->_block['current']) || !is_array($this->_block['current']) ) $this->_block['current'] = array();
foreach($this->_block['current'] as $current_key => $current_value) {
$_tmpb_current = &$this->_block['current'][$current_key]; ?>
		<script type="text/javascript">
		<!--
		function check_form_com(){
			if(document.getElementById('<?php echo isset($this->_var['SCRIPT']) ? $this->_var['SCRIPT'] : ''; ?>login').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_LOGIN']) ? $this->_var['L_REQUIRE_LOGIN'] : ''; ?>");
				return false;
			}
			if(document.getElementById('contents').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_TEXT']) ? $this->_var['L_REQUIRE_TEXT'] : ''; ?>");
				return false;
			}
			return true;
		}
		function Confirm() {
		return confirm("<?php echo isset($this->_var['L_DELETE_MESSAGE']) ? $this->_var['L_DELETE_MESSAGE'] : ''; ?>");
		}
		-->
		</script>

		<?php if( !isset($_tmpb_current['post']) || !is_array($_tmpb_current['post']) ) $_tmpb_current['post'] = array();
foreach($_tmpb_current['post'] as $post_key => $post_value) {
$_tmpb_post = &$_tmpb_current['post'][$post_key]; ?>
		<span id="<?php echo isset($this->_var['SCRIPT']) ? $this->_var['SCRIPT'] : ''; ?>"></span>
		<form action="<?php echo isset($this->_var['U_ACTION']) ? $this->_var['U_ACTION'] : ''; ?>" method="post" onsubmit="return check_form_com();" class="fieldset_mini">
			<fieldset>
				<legend><?php echo isset($this->_var['L_EDIT_COMMENT']) ? $this->_var['L_EDIT_COMMENT'] : '';  echo isset($this->_var['L_ADD_COMMENT']) ? $this->_var['L_ADD_COMMENT'] : ''; ?></legend>
				
				<?php if( !isset($_tmpb_post['visible_com']) || !is_array($_tmpb_post['visible_com']) ) $_tmpb_post['visible_com'] = array();
foreach($_tmpb_post['visible_com'] as $visible_com_key => $visible_com_value) {
$_tmpb_visible_com = &$_tmpb_post['visible_com'][$visible_com_key]; ?>
				<dl>
					<dt><label for="<?php echo isset($this->_var['SCRIPT']) ? $this->_var['SCRIPT'] : ''; ?>login">* <?php echo isset($this->_var['L_LOGIN']) ? $this->_var['L_LOGIN'] : ''; ?></label></dt>
					<dd><label><input type="text" maxlength="25" size="25" id="<?php echo isset($this->_var['SCRIPT']) ? $this->_var['SCRIPT'] : ''; ?>login" name="login" value="<?php echo isset($_tmpb_visible_com['LOGIN']) ? $_tmpb_visible_com['LOGIN'] : ''; ?>" class="text" /></label></dd>
				</dl>
				<?php } ?>
				<br />
				<label for="contents">* <?php echo isset($this->_var['L_MESSAGE']) ? $this->_var['L_MESSAGE'] : ''; ?></label>
				<?php $this->tpl_include('handle_bbcode'); ?>
				<label><textarea rows="10" cols="60" id="contents" name="contents"><?php echo isset($this->_var['CONTENTS']) ? $this->_var['CONTENTS'] : ''; ?></textarea> </label>
				<br />
				<strong><?php echo isset($this->_var['L_FORBIDDEN_TAGS']) ? $this->_var['L_FORBIDDEN_TAGS'] : ''; ?></strong> <?php echo isset($this->_var['DISPLAY_FORBIDDEN_TAGS']) ? $this->_var['DISPLAY_FORBIDDEN_TAGS'] : ''; ?>
			</fieldset>			
			<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
				<?php if( !isset($_tmpb_post['hidden_com']) || !is_array($_tmpb_post['hidden_com']) ) $_tmpb_post['hidden_com'] = array();
foreach($_tmpb_post['hidden_com'] as $hidden_com_key => $hidden_com_value) {
$_tmpb_hidden_com = &$_tmpb_post['hidden_com'][$hidden_com_key]; ?>
				<input type="hidden" maxlength="25" size="25" name="login" value="<?php echo isset($_tmpb_hidden_com['LOGIN']) ? $_tmpb_hidden_com['LOGIN'] : ''; ?>" class="text" />
				<?php } ?>
				<input type="hidden" name="contents_ftags" id="contents_ftags" value="<?php echo isset($this->_var['FORBIDDEN_TAGS']) ? $this->_var['FORBIDDEN_TAGS'] : ''; ?>" />
				<input type="hidden" name="idprov" value="<?php echo isset($this->_var['IDPROV']) ? $this->_var['IDPROV'] : ''; ?>" />
				<input type="hidden" name="idcom" value="<?php echo isset($this->_var['IDCOM']) ? $this->_var['IDCOM'] : ''; ?>" />
				<input type="hidden" name="script" value="<?php echo isset($this->_var['SCRIPT']) ? $this->_var['SCRIPT'] : ''; ?>" />
				<input type="submit" name="valid" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
				&nbsp;&nbsp; 						
				<script type="text/javascript">
				<!--				
				document.write('<input value="<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>" onclick="XMLHttpRequest_preview(this.form);hide_div(\'xmlhttprequest_result\')" type="button" class="submit" />&nbsp;&nbsp; ');
				-->
				</script>						
				<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />
			</fieldset>
		</form>
		<?php } ?>
		
		<?php if( !isset($_tmpb_current['error_handler']) || !is_array($_tmpb_current['error_handler']) ) $_tmpb_current['error_handler'] = array();
foreach($_tmpb_current['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$_tmpb_current['error_handler'][$error_handler_key]; ?>
		<br />
		<span id="errorh"></span>
		<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>	
			<br />			
		</div>
		<br /><br />	
		<?php } ?>
		
		<div class="msg_position">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top">
				<div style="float:left;"><?php echo isset($this->_var['PAGINATION_COM']) ? $this->_var['PAGINATION_COM'] : ''; ?>&nbsp;</div>
				<div style="float:right;text-align: center;">
					<?php if( !isset($_tmpb_current['lock']) || !is_array($_tmpb_current['lock']) ) $_tmpb_current['lock'] = array();
foreach($_tmpb_current['lock'] as $lock_key => $lock_value) {
$_tmpb_lock = &$_tmpb_current['lock'][$lock_key]; ?>
					<a href="<?php echo isset($_tmpb_lock['U_LOCK']) ? $_tmpb_lock['U_LOCK'] : ''; ?>"><?php echo isset($_tmpb_lock['L_LOCK']) ? $_tmpb_lock['L_LOCK'] : ''; ?></a> <a href="<?php echo isset($_tmpb_lock['U_LOCK']) ? $_tmpb_lock['U_LOCK'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/<?php echo isset($_tmpb_lock['IMG']) ? $_tmpb_lock['IMG'] : ''; ?>.png" alt="" class="valign_middle" /></a>
					<?php } ?>
				</div>
			</div>	
		</div>
		<?php if( !isset($_tmpb_current['com']) || !is_array($_tmpb_current['com']) ) $_tmpb_current['com'] = array();
foreach($_tmpb_current['com'] as $com_key => $com_value) {
$_tmpb_com = &$_tmpb_current['com'][$com_key]; ?>
		<div class="msg_position">
			<div class="msg_container">
				<span id="m<?php echo isset($_tmpb_com['ID']) ? $_tmpb_com['ID'] : ''; ?>"></span>
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
					<?php echo isset($_tmpb_com['USER_ONLINE']) ? $_tmpb_com['USER_ONLINE'] : ''; echo ' '; echo isset($_tmpb_com['USER_PSEUDO']) ? $_tmpb_com['USER_PSEUDO'] : ''; ?>
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="<?php echo isset($_tmpb_com['U_ANCHOR']) ? $_tmpb_com['U_ANCHOR'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/ancre.png" alt="<?php echo isset($_tmpb_com['ID']) ? $_tmpb_com['ID'] : ''; ?>" /></a> <?php echo isset($_tmpb_com['DATE']) ? $_tmpb_com['DATE'] : ''; ?></div>
					<div style="float:right;"><a href="<?php echo isset($_tmpb_com['U_QUOTE']) ? $_tmpb_com['U_QUOTE'] : ''; ?>" title=""><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/quote.png" alt="<?php echo isset($this->_var['L_QUOTE']) ? $this->_var['L_QUOTE'] : ''; ?>" title="<?php echo isset($this->_var['L_QUOTE']) ? $this->_var['L_QUOTE'] : ''; ?>" class="valign_middle" /></a><?php echo isset($_tmpb_com['EDIT']) ? $_tmpb_com['EDIT'] : '';  echo isset($_tmpb_com['DEL']) ? $_tmpb_com['DEL'] : ''; ?>&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;"><?php echo isset($_tmpb_com['USER_RANK']) ? $_tmpb_com['USER_RANK'] : ''; ?></p>
						<p style="text-align:center;"><?php echo isset($_tmpb_com['USER_IMG_ASSOC']) ? $_tmpb_com['USER_IMG_ASSOC'] : ''; ?></p>
						<p style="text-align:center;"><?php echo isset($_tmpb_com['USER_AVATAR']) ? $_tmpb_com['USER_AVATAR'] : ''; ?></p>
						<p style="text-align:center;"><?php echo isset($_tmpb_com['USER_GROUP']) ? $_tmpb_com['USER_GROUP'] : ''; ?></p>
						<?php echo isset($_tmpb_com['USER_SEX']) ? $_tmpb_com['USER_SEX'] : ''; ?>
						<?php echo isset($_tmpb_com['USER_DATE']) ? $_tmpb_com['USER_DATE'] : ''; ?><br />
						<?php echo isset($_tmpb_com['USER_MSG']) ? $_tmpb_com['USER_MSG'] : ''; ?><br />
						<?php echo isset($_tmpb_com['USER_LOCAL']) ? $_tmpb_com['USER_LOCAL'] : ''; ?>
					</div>
					<div class="msg_contents">
						<div class="msg_contents_overflow">
							<?php echo isset($_tmpb_com['CONTENTS']) ? $_tmpb_com['CONTENTS'] : ''; ?>
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign">				
				<div class="msg_sign_overflow">
					<?php echo isset($_tmpb_com['USER_SIGN']) ? $_tmpb_com['USER_SIGN'] : ''; ?>
				</div>				
				<hr />
				<div style="float:left;">
					<?php echo isset($_tmpb_com['U_MEMBER_PM']) ? $_tmpb_com['U_MEMBER_PM'] : ''; echo ' '; echo isset($_tmpb_com['USER_MAIL']) ? $_tmpb_com['USER_MAIL'] : ''; echo ' '; echo isset($_tmpb_com['USER_MSN']) ? $_tmpb_com['USER_MSN'] : ''; echo ' '; echo isset($_tmpb_com['USER_YAHOO']) ? $_tmpb_com['USER_YAHOO'] : ''; echo ' '; echo isset($_tmpb_com['USER_WEB']) ? $_tmpb_com['USER_WEB'] : ''; ?>
				</div>
				<div style="float:right;font-size:10px;">
					<?php echo isset($_tmpb_com['WARNING']) ? $_tmpb_com['WARNING'] : ''; echo ' '; echo isset($_tmpb_com['PUNISHMENT']) ? $_tmpb_com['PUNISHMENT'] : ''; ?>
				</div>&nbsp;
			</div>	
		</div>				
		<?php } ?>		
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;"><?php echo isset($this->_var['PAGINATION_COM']) ? $this->_var['PAGINATION_COM'] : ''; ?>&nbsp;</div>
		</div>
	<?php } ?>



	<?php if( !isset($this->_block['popup']) || !is_array($this->_block['popup']) ) $this->_block['popup'] = array();
foreach($this->_block['popup'] as $popup_key => $popup_value) {
$_tmpb_popup = &$this->_block['popup'][$popup_key]; ?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo isset($this->_var['L_XML_LANGUAGE']) ? $this->_var['L_XML_LANGUAGE'] : ''; ?>" >
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"  />
	<meta http-equiv="Content-Style-Type" content="text/css" />

	<title><?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?></title>
	<link rel="stylesheet" href="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/design.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/global.css" type="text/css" media="screen, print, handheld" />
	<link rel="stylesheet" href="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/generic.css" type="text/css" media="screen, print, handheld" />
	<link rel="stylesheet" href="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/content.css" type="text/css" media="screen, print, handheld" />
	<link rel="stylesheet" href="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/bbcode.css" type="text/css" media="screen, print, handheld" />
	</head>

	<body>		
		<script type="text/javascript">
		<!--
		function check_form_com(){
			if(document.getElementById('login').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_LOGIN']) ? $this->_var['L_REQUIRE_LOGIN'] : ''; ?>");
				return false;
			}
			if(document.getElementById('contents').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_TEXT']) ? $this->_var['L_REQUIRE_TEXT'] : ''; ?>");
				return false;
			}
			return true;
		}
		function Confirm() {
			return confirm("<?php echo isset($this->_var['L_DELETE_MESSAGE']) ? $this->_var['L_DELETE_MESSAGE'] : ''; ?>");
		}
		-->
		</script>
		
		<?php if( !isset($_tmpb_popup['post']) || !is_array($_tmpb_popup['post']) ) $_tmpb_popup['post'] = array();
foreach($_tmpb_popup['post'] as $post_key => $post_value) {
$_tmpb_post = &$_tmpb_popup['post'][$post_key]; ?>
		<form action="<?php echo isset($this->_var['U_ACTION']) ? $this->_var['U_ACTION'] : ''; ?>" method="post" onsubmit="return check_form_com();" class="fieldset_content">
			<fieldset>
				<legend><?php echo isset($this->_var['L_EDIT_COMMENT']) ? $this->_var['L_EDIT_COMMENT'] : '';  echo isset($this->_var['L_ADD_COMMENT']) ? $this->_var['L_ADD_COMMENT'] : ''; ?></legend>
				
				<?php if( !isset($_tmpb_post['visible_com']) || !is_array($_tmpb_post['visible_com']) ) $_tmpb_post['visible_com'] = array();
foreach($_tmpb_post['visible_com'] as $visible_com_key => $visible_com_value) {
$_tmpb_visible_com = &$_tmpb_post['visible_com'][$visible_com_key]; ?>
				<dl> 
					<dd><label for="login">* <?php echo isset($this->_var['L_LOGIN']) ? $this->_var['L_LOGIN'] : ''; ?></label></dd>
					<dt><label><input type="text" maxlength="25" size="25" id="login" name="login" value="<?php echo isset($_tmpb_visible_com['LOGIN']) ? $_tmpb_visible_com['LOGIN'] : ''; ?>" class="text" /></label></dt>
				</dl>
				<?php } ?>
				<br />
				<label for="contents">* <?php echo isset($this->_var['L_MESSAGE']) ? $this->_var['L_MESSAGE'] : ''; ?></label>
				<?php $this->tpl_include('handle_bbcode'); ?>
				<label><textarea rows="10" cols="60" id="contents" name="contents"><?php echo isset($this->_var['CONTENTS']) ? $this->_var['CONTENTS'] : ''; ?></textarea> </label>
				<br />
				<strong><?php echo isset($this->_var['L_FORBIDDEN_TAGS']) ? $this->_var['L_FORBIDDEN_TAGS'] : ''; ?></strong> <?php echo isset($this->_var['DISPLAY_FORBIDDEN_TAGS']) ? $this->_var['DISPLAY_FORBIDDEN_TAGS'] : ''; ?>
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
				<?php if( !isset($_tmpb_post['hidden_com']) || !is_array($_tmpb_post['hidden_com']) ) $_tmpb_post['hidden_com'] = array();
foreach($_tmpb_post['hidden_com'] as $hidden_com_key => $hidden_com_value) {
$_tmpb_hidden_com = &$_tmpb_post['hidden_com'][$hidden_com_key]; ?>
				<input type="hidden" maxlength="25" size="25" name="login" value="<?php echo isset($_tmpb_hidden_com['LOGIN']) ? $_tmpb_hidden_com['LOGIN'] : ''; ?>" class="text" />
				<?php } ?>
				<input type="hidden" name="contents_ftags" id="shout_contents_ftags" value="<?php echo isset($this->_var['FORBIDDEN_TAGS']) ? $this->_var['FORBIDDEN_TAGS'] : ''; ?>" />
				<input type="hidden" name="idprov" value="<?php echo isset($this->_var['IDPROV']) ? $this->_var['IDPROV'] : ''; ?>" />
				<input type="hidden" name="idcom" value="<?php echo isset($this->_var['IDCOM']) ? $this->_var['IDCOM'] : ''; ?>" />
				<input type="hidden" name="script" value="<?php echo isset($this->_var['SCRIPT']) ? $this->_var['SCRIPT'] : ''; ?>" />
				<input type="submit" name="valid" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
				&nbsp;&nbsp; 						
				<script type="text/javascript">
				<!--				
				document.write('<input value="<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>" onclick="XMLHttpRequest_preview(this.form);hide_div(\'xmlhttprequest_result\')" type="button" class="submit" />&nbsp;&nbsp; ');
				-->
				</script>						
				<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />
			</fieldset>
		</form>
		<?php } ?>
		
		<?php if( !isset($_tmpb_popup['error_handler']) || !is_array($_tmpb_popup['error_handler']) ) $_tmpb_popup['error_handler'] = array();
foreach($_tmpb_popup['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$_tmpb_popup['error_handler'][$error_handler_key]; ?>
			<br />
			<span id="errorh"></span>
			<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
				<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>	
				<br />			
			</div>
			<br /><br />	
		<?php } ?>
		
		<div class="msg_position">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top">
				<div style="float:left;"><?php echo isset($this->_var['PAGINATION_COM']) ? $this->_var['PAGINATION_COM'] : ''; ?>&nbsp;</div>
				<div style="float:right;text-align: center;">
					<?php if( !isset($_tmpb_popup['lock']) || !is_array($_tmpb_popup['lock']) ) $_tmpb_popup['lock'] = array();
foreach($_tmpb_popup['lock'] as $lock_key => $lock_value) {
$_tmpb_lock = &$_tmpb_popup['lock'][$lock_key]; ?>
					<a href="<?php echo isset($_tmpb_lock['U_LOCK']) ? $_tmpb_lock['U_LOCK'] : ''; ?>"><?php echo isset($_tmpb_lock['L_LOCK']) ? $_tmpb_lock['L_LOCK'] : ''; ?></a> <a href="<?php echo isset($_tmpb_lock['U_LOCK']) ? $_tmpb_lock['U_LOCK'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/<?php echo isset($_tmpb_lock['IMG']) ? $_tmpb_lock['IMG'] : ''; ?>.png" alt="" style="vertical-align:middle;" /></a>
					<?php } ?>
				</div>
			</div>	
		</div>
		<?php if( !isset($_tmpb_popup['com']) || !is_array($_tmpb_popup['com']) ) $_tmpb_popup['com'] = array();
foreach($_tmpb_popup['com'] as $com_key => $com_value) {
$_tmpb_com = &$_tmpb_popup['com'][$com_key]; ?>
		<div class="msg_position">
			<div class="msg_container">
				<span id="m<?php echo isset($_tmpb_com['ID']) ? $_tmpb_com['ID'] : ''; ?>">
				<span id="com"></span>
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						<?php echo isset($_tmpb_com['USER_ONLINE']) ? $_tmpb_com['USER_ONLINE'] : ''; echo ' '; echo isset($_tmpb_com['USER_PSEUDO']) ? $_tmpb_com['USER_PSEUDO'] : ''; ?>
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="<?php echo isset($_tmpb_com['U_ANCHOR']) ? $_tmpb_com['U_ANCHOR'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/ancre.png" alt="<?php echo isset($_tmpb_com['ID']) ? $_tmpb_com['ID'] : ''; ?>" /></a> <?php echo isset($_tmpb_com['DATE']) ? $_tmpb_com['DATE'] : ''; ?></div>
					<div style="float:right;"><a href="<?php echo isset($_tmpb_com['U_QUOTE']) ? $_tmpb_com['U_QUOTE'] : ''; ?>" title=""><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/quote.png" alt="<?php echo isset($this->_var['L_QUOTE']) ? $this->_var['L_QUOTE'] : ''; ?>" title="<?php echo isset($this->_var['L_QUOTE']) ? $this->_var['L_QUOTE'] : ''; ?>" class="valign_middle" /></a><?php echo isset($_tmpb_com['EDIT']) ? $_tmpb_com['EDIT'] : '';  echo isset($_tmpb_com['DEL']) ? $_tmpb_com['DEL'] : ''; ?>&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;"><?php echo isset($_tmpb_com['USER_RANK']) ? $_tmpb_com['USER_RANK'] : ''; ?></p>
						<p style="text-align:center;"><?php echo isset($_tmpb_com['USER_IMG_ASSOC']) ? $_tmpb_com['USER_IMG_ASSOC'] : ''; ?></p>
						<p style="text-align:center;"><?php echo isset($_tmpb_com['USER_AVATAR']) ? $_tmpb_com['USER_AVATAR'] : ''; ?></p>
						<p style="text-align:center;"><?php echo isset($_tmpb_com['USER_GROUP']) ? $_tmpb_com['USER_GROUP'] : ''; ?></p>
						<?php echo isset($_tmpb_com['USER_SEX']) ? $_tmpb_com['USER_SEX'] : ''; ?>
						<?php echo isset($_tmpb_com['USER_DATE']) ? $_tmpb_com['USER_DATE'] : ''; ?><br />
						<?php echo isset($_tmpb_com['USER_MSG']) ? $_tmpb_com['USER_MSG'] : ''; ?><br />
						<?php echo isset($_tmpb_com['USER_LOCAL']) ? $_tmpb_com['USER_LOCAL'] : ''; ?>
					</div>
					<div class="msg_contents">
						<div class="msg_contents_overflow">
							<?php echo isset($_tmpb_com['CONTENTS']) ? $_tmpb_com['CONTENTS'] : ''; ?>
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign">				
				<div class="msg_sign_overflow">
					<?php echo isset($_tmpb_com['USER_SIGN']) ? $_tmpb_com['USER_SIGN'] : ''; ?>	
				</div>			
				<hr />
				<div style="float:right;font-size:10px;">
					<?php echo isset($_tmpb_com['WARNING']) ? $_tmpb_com['WARNING'] : ''; echo ' '; echo isset($_tmpb_com['PUNISHMENT']) ? $_tmpb_com['PUNISHMENT'] : ''; ?>
				</div>
			</div>	
		</div>				
		<?php } ?>		
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;"><?php echo isset($this->_var['PAGINATION_COM']) ? $this->_var['PAGINATION_COM'] : ''; ?>&nbsp;</div>
		</div>
	</body>
	</html>

	<?php } ?>
	