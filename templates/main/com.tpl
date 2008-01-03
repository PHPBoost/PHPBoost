	# START current #
		<script type="text/javascript">
		<!--
		function check_form_com(){
			if(document.getElementById('{SCRIPT}login').value == "") {
				alert("{L_REQUIRE_LOGIN}");
				return false;
			}
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
			}
			return true;
		}
		function Confirm() {
		return confirm("{L_DELETE_MESSAGE}");
		}
		-->
		</script>

		# START current.post #
		<span id="{SCRIPT}"></span>
		<form action="{U_ACTION}" method="post" onsubmit="return check_form_com();" class="fieldset_mini">
			<fieldset>
				<legend>{L_EDIT_COMMENT}{L_ADD_COMMENT}</legend>
				
				# START current.post.visible_com #
				<dl>
					<dt><label for="{SCRIPT}login">* {L_LOGIN}</label></dt>
					<dd><label><input type="text" maxlength="25" size="25" id="{SCRIPT}login" name="login" value="{current.post.visible_com.LOGIN}" class="text" /></label></dd>
				</dl>
				# END current.post.visible_com #
				<br />
				<label for="contents">* {L_MESSAGE}</label>
				# INCLUDE handle_bbcode #
				<label><textarea rows="10" cols="60" id="contents" name="contents">{CONTENTS}</textarea> </label>
				<br />
				<strong>{L_FORBIDDEN_TAGS}</strong> {DISPLAY_FORBIDDEN_TAGS}
			</fieldset>			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				# START current.post.hidden_com #
				<input type="hidden" maxlength="25" size="25" name="login" value="{current.post.hidden_com.LOGIN}" class="text" />
				# END current.post.hidden_com #
				<input type="hidden" name="contents_ftags" id="contents_ftags" value="{FORBIDDEN_TAGS}" />
				<input type="hidden" name="idprov" value="{IDPROV}" />
				<input type="hidden" name="idcom" value="{IDCOM}" />
				<input type="hidden" name="script" value="{SCRIPT}" />
				<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
				&nbsp;&nbsp; 						
				<script type="text/javascript">
				<!--				
				document.write('<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview(this.form);hide_div(\'xmlhttprequest_result\')" type="button" class="submit" />&nbsp;&nbsp; ');
				-->
				</script>						
				<input type="reset" value="{L_RESET}" class="reset" />
			</fieldset>
		</form>
		# END current.post #
		
		# START current.error_handler #
		<br />
		<span id="errorh"></span>
		<div class="{current.error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/{THEME}/images/{current.error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {current.error_handler.L_ERROR}	
			<br />			
		</div>
		<br /><br />	
		# END current.error_handler #
		
		<div class="msg_position">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top">
				<div style="float:left;">{PAGINATION_COM}&nbsp;</div>
				<div style="float:right;text-align: center;">
					# START current.lock #
					<a href="{current.lock.U_LOCK}">{current.lock.L_LOCK}</a> <a href="{current.lock.U_LOCK}"><img src="../templates/{THEME}/images/{LANG}/{current.lock.IMG}.png" alt="" class="valign_middle" /></a>
					# END current.lock #
				</div>
			</div>	
		</div>
		# START current.com #
		<div class="msg_position">
			<div class="msg_container">
				<span id="m{current.com.ID}"></span>
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
					{current.com.USER_ONLINE} {current.com.USER_PSEUDO}
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="{current.com.U_ANCHOR}"><img src="../templates/{THEME}/images/ancre.png" alt="{current.com.ID}" /></a> {current.com.DATE}</div>
					<div style="float:right;"><a href="{current.com.U_QUOTE}" title=""><img src="../templates/{THEME}/images/{LANG}/quote.png" alt="{L_QUOTE}" title="{L_QUOTE}" class="valign_middle" /></a>{current.com.EDIT}{current.com.DEL}&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;">{current.com.USER_RANK}</p>
						<p style="text-align:center;">{current.com.USER_IMG_ASSOC}</p>
						<p style="text-align:center;">{current.com.USER_AVATAR}</p>
						<p style="text-align:center;">{current.com.USER_GROUP}</p>
						{current.com.USER_SEX}
						{current.com.USER_DATE}<br />
						{current.com.USER_MSG}<br />
						{current.com.USER_LOCAL}
					</div>
					<div class="msg_contents">
						<div class="msg_contents_overflow">
							{current.com.CONTENTS}
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign">				
				<div class="msg_sign_overflow">
					{current.com.USER_SIGN}
				</div>				
				<hr />
				<div style="float:left;">
					{current.com.U_MEMBER_PM} {current.com.USER_MAIL} {current.com.USER_MSN} {current.com.USER_YAHOO} {current.com.USER_WEB}
				</div>
				<div style="float:right;font-size:10px;">
					{current.com.WARNING} {current.com.PUNISHMENT}
				</div>&nbsp;
			</div>	
		</div>				
		# END current.com #		
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;">{PAGINATION_COM}&nbsp;</div>
		</div>
	# END current #



	# START popup #
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"  />
	<meta http-equiv="Content-Style-Type" content="text/css" />

	<title>{L_TITLE}</title>
	<link rel="stylesheet" href="../templates/{THEME}/design.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../templates/{THEME}/global.css" type="text/css" media="screen, print, handheld" />
	<link rel="stylesheet" href="../templates/{THEME}/generic.css" type="text/css" media="screen, print, handheld" />
	<link rel="stylesheet" href="../templates/{THEME}/content.css" type="text/css" media="screen, print, handheld" />
	<link rel="stylesheet" href="../templates/{THEME}/bbcode.css" type="text/css" media="screen, print, handheld" />
	</head>

	<body>		
		<script type="text/javascript">
		<!--
		function check_form_com(){
			if(document.getElementById('login').value == "") {
				alert("{L_REQUIRE_LOGIN}");
				return false;
			}
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
			}
			return true;
		}
		function Confirm() {
			return confirm("{L_DELETE_MESSAGE}");
		}
		-->
		</script>
		
		# START popup.post #
		<form action="{U_ACTION}" method="post" onsubmit="return check_form_com();" class="fieldset_content">
			<fieldset>
				<legend>{L_EDIT_COMMENT}{L_ADD_COMMENT}</legend>
				
				# START popup.post.visible_com #
				<dl> 
					<dd><label for="login">* {L_LOGIN}</label></dd>
					<dt><label><input type="text" maxlength="25" size="25" id="login" name="login" value="{popup.post.visible_com.LOGIN}" class="text" /></label></dt>
				</dl>
				# END popup.post.visible_com #
				<br />
				<label for="contents">* {L_MESSAGE}</label>
				# INCLUDE handle_bbcode #
				<label><textarea rows="10" cols="60" id="contents" name="contents">{CONTENTS}</textarea> </label>
				<br />
				<strong>{L_FORBIDDEN_TAGS}</strong> {DISPLAY_FORBIDDEN_TAGS}
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				# START popup.post.hidden_com #
				<input type="hidden" maxlength="25" size="25" name="login" value="{popup.post.hidden_com.LOGIN}" class="text" />
				# END popup.post.hidden_com #
				<input type="hidden" name="contents_ftags" id="shout_contents_ftags" value="{FORBIDDEN_TAGS}" />
				<input type="hidden" name="idprov" value="{IDPROV}" />
				<input type="hidden" name="idcom" value="{IDCOM}" />
				<input type="hidden" name="script" value="{SCRIPT}" />
				<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
				&nbsp;&nbsp; 						
				<script type="text/javascript">
				<!--				
				document.write('<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview(this.form);hide_div(\'xmlhttprequest_result\')" type="button" class="submit" />&nbsp;&nbsp; ');
				-->
				</script>						
				<input type="reset" value="{L_RESET}" class="reset" />
			</fieldset>
		</form>
		# END popup.post #
		
		# START popup.error_handler #
			<br />
			<span id="errorh"></span>
			<div class="{popup.error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
				<img src="../templates/{THEME}/images/{popup.error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {popup.error_handler.L_ERROR}	
				<br />			
			</div>
			<br /><br />	
		# END popup.error_handler #
		
		<div class="msg_position">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top">
				<div style="float:left;">{PAGINATION_COM}&nbsp;</div>
				<div style="float:right;text-align: center;">
					# START popup.lock #
					<a href="{popup.lock.U_LOCK}">{popup.lock.L_LOCK}</a> <a href="{popup.lock.U_LOCK}"><img src="../templates/{THEME}/images/{LANG}/{popup.lock.IMG}.png" alt="" style="vertical-align:middle;" /></a>
					# END popup.lock #
				</div>
			</div>	
		</div>
		# START popup.com #
		<div class="msg_position">
			<div class="msg_container">
				<span id="m{popup.com.ID}">
				<span id="com"></span>
				<div class="msg_pseudo_mbr">
					{popup.com.USER_ONLINE} {popup.com.USER_PSEUDO}
				</div>
				<div class="msg_top_row">
					<div style="float:left;">&nbsp;&nbsp;<a href="{popup.com.U_ANCHOR}"><img src="../templates/{THEME}/images/ancre.png" alt="{popup.com.ID}" /></a> {popup.com.DATE}</div>
					<div style="float:right;"><a href="{popup.com.U_QUOTE}" title=""><img src="../templates/{THEME}/images/{LANG}/quote.png" alt="{L_QUOTE}" title="{L_QUOTE}" class="valign_middle" /></a>{popup.com.EDIT}{popup.com.DEL}&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;">{popup.com.USER_RANK}</p>
						<p style="text-align:center;">{popup.com.USER_IMG_ASSOC}</p>
						<p style="text-align:center;">{popup.com.USER_AVATAR}</p>
						<p style="text-align:center;">{popup.com.USER_GROUP}</p>
						{popup.com.USER_SEX}
						{popup.com.USER_DATE}<br />
						{popup.com.USER_MSG}<br />
						{popup.com.USER_LOCAL}
					</div>
					<div class="msg_contents">
						<div class="msg_contents_overflow">
							{popup.com.CONTENTS}
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign">				
				<div class="msg_sign_overflow">
					{popup.com.USER_SIGN}	
				</div>			
				<hr />
				<div style="float:right;font-size:10px;">
					{popup.com.WARNING} {popup.com.PUNISHMENT}
				</div>
			</div>	
		</div>				
		# END popup.com #		
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;">{PAGINATION_COM}&nbsp;</div>
		</div>
	</body>
	</html>

	# END popup #
	