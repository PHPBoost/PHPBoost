	# IF CURRENT_PAGE_COM #
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

		# IF AUTH_POST_COM #
		<span id="{SCRIPT}"></span>
		<form action="{U_ACTION}" method="post" onsubmit="return check_form_com();" class="fieldset_mini">
			<fieldset>
				<legend>{L_EDIT_COMMENT}{L_ADD_COMMENT}</legend>
				
				# IF VISIBLE_COM #
				<dl>
					<dt><label for="{SCRIPT}login">* {L_LOGIN}</label></dt>
					<dd><label><input type="text" maxlength="25" size="25" id="{SCRIPT}login" name="login" value="{LOGIN}" class="text" /></label></dd>
				</dl>
				# ENDIF #
				<br />
				<label for="contents">* {L_MESSAGE}</label>
				# INCLUDE handle_bbcode #
				<label><textarea rows="10" cols="60" id="contents" name="contents">{CONTENTS}</textarea> </label>
				<br />
				<strong>{L_FORBIDDEN_TAGS}</strong> {DISPLAY_FORBIDDEN_TAGS}
			</fieldset>			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				# IF HIDDEN_COM #
				<input type="hidden" maxlength="25" size="25" name="login" value="{LOGIN}" class="text" />
				# ENDIF #
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
		# ENDIF #
		
		# IF C_ERROR_HANDLER #
		<br />
		<span id="errorh"></span>
		<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}	
			<br />			
		</div>
		<br /><br />	
		# ENDIF #
		
		<div class="msg_position">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top">
				<div style="float:left;">{PAGINATION_COM}&nbsp;</div>
				<div style="float:right;text-align: center;">
					# IF COM_LOCK #
					<a href="{U_LOCK}">{L_LOCK}</a> <a href="{U_LOCK}"><img src="../templates/{THEME}/images/{LANG}/{IMG}.png" alt="" class="valign_middle" /></a>
					# ENDIF #
				</div>
			</div>	
		</div>
		# START com #
		<div class="msg_position">
			<div class="msg_container">
				<span id="m{com.ID}"></span>
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
					{com.USER_ONLINE} {com.USER_PSEUDO}
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="{com.U_ANCHOR}"><img src="../templates/{THEME}/images/ancre.png" alt="{com.ID}" /></a> {com.DATE}</div>
					<div style="float:right;"><a href="{com.U_QUOTE}" title=""><img src="../templates/{THEME}/images/{LANG}/quote.png" alt="{L_QUOTE}" title="{L_QUOTE}" class="valign_middle" /></a>{com.EDIT}{com.DEL}&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;">{com.USER_RANK}</p>
						<p style="text-align:center;">{com.USER_IMG_ASSOC}</p>
						<p style="text-align:center;">{com.USER_AVATAR}</p>
						<p style="text-align:center;">{com.USER_GROUP}</p>
						{com.USER_SEX}
						{com.USER_DATE}<br />
						{com.USER_MSG}<br />
						{com.USER_LOCAL}
					</div>
					<div class="msg_contents">
						<div class="msg_contents_overflow">
							{com.CONTENTS}
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign">				
				<div class="msg_sign_overflow">
					{com.USER_SIGN}
				</div>				
				<hr />
				<div style="float:left;">
					{com.U_MEMBER_PM} {com.USER_MAIL} {com.USER_MSN} {com.USER_YAHOO} {com.USER_WEB}
				</div>
				<div style="float:right;font-size:10px;">
					{com.WARNING} {com.PUNISHMENT}
				</div>&nbsp;
			</div>	
		</div>				
		# END com #		
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;">{PAGINATION_COM}&nbsp;</div>
		</div>
	# ENDIF #



	# IF POPUP_PAGE_COM #
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
		<title>{L_TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"  />
		<meta http-equiv="Content-Language" content="{L_XML_LANGUAGE}" />
		<link rel="stylesheet" href="../templates/{THEME}/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="../templates/{THEME}/global.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/{THEME}/generic.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/{THEME}/content.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/{THEME}/bbcode.css" type="text/css" media="screen, print, handheld" />
		<script type="text/javascript" src="../templates/{THEME}/images/global.js"></script>
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
		
		# IF AUTH_POST_COM #
		<form action="{U_ACTION}" method="post" onsubmit="return check_form_com();" class="fieldset_content">
			<fieldset>
				<legend>{L_EDIT_COMMENT}{L_ADD_COMMENT}</legend>
				
				# IF VISIBLE_COM #
				<dl> 
					<dd><label for="login">* {L_LOGIN}</label></dd>
					<dt><label><input type="text" maxlength="25" size="25" id="login" name="login" value="{LOGIN}" class="text" /></label></dt>
				</dl>
				# ENDIF #
				<br />
				<label for="contents">* {L_MESSAGE}</label>
				# INCLUDE handle_bbcode #
				<label><textarea rows="10" cols="60" id="contents" name="contents">{CONTENTS}</textarea> </label>
				<br />
				<strong>{L_FORBIDDEN_TAGS}</strong> {DISPLAY_FORBIDDEN_TAGS}
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				# IF HIDDEN_COM #
				<input type="hidden" maxlength="25" size="25" name="login" value="{LOGIN}" class="text" />
				# ENDIF #
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
		# ENDIF #
		
		# IF C_ERROR_HANDLER #
			<br />
			<span id="errorh"></span>
			<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
				<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}	
				<br />			
			</div>
			<br /><br />	
		# ENDIF #
		
		<div class="msg_position">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top">
				<div style="float:left;">{PAGINATION_COM}&nbsp;</div>
				<div style="float:right;text-align: center;">
					# IF COM_LOCK #
					<a href="{U_LOCK}">{L_LOCK}</a> <a href="{U_LOCK}"><img src="../templates/{THEME}/images/{LANG}/{IMG}.png" alt="" class="valign_middle" /></a>
					# END lock #
				</div>
			</div>	
		</div>
		# START com #
		<div class="msg_position">
			<div class="msg_container">
				<span id="m{com.ID}">
				<span id="com"></span>
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						{com.USER_ONLINE} {com.USER_PSEUDO}
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="{com.U_ANCHOR}"><img src="../templates/{THEME}/images/ancre.png" alt="{com.ID}" /></a> {com.DATE}</div>
					<div style="float:right;"><a href="{com.U_QUOTE}" title=""><img src="../templates/{THEME}/images/{LANG}/quote.png" alt="{L_QUOTE}" title="{L_QUOTE}" class="valign_middle" /></a>{com.EDIT}{com.DEL}&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;">{com.USER_RANK}</p>
						<p style="text-align:center;">{com.USER_IMG_ASSOC}</p>
						<p style="text-align:center;">{com.USER_AVATAR}</p>
						<p style="text-align:center;">{com.USER_GROUP}</p>
						{com.USER_SEX}
						{com.USER_DATE}<br />
						{com.USER_MSG}<br />
						{com.USER_LOCAL}
					</div>
					<div class="msg_contents">
						<div class="msg_contents_overflow">
							{com.CONTENTS}
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign">				
				<div class="msg_sign_overflow">
					{com.USER_SIGN}	
				</div>			
				<hr />
				<div style="float:right;font-size:10px;">
					{com.WARNING} {com.PUNISHMENT}
				</div>
			</div>	
		</div>				
		# END com #		
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;">{PAGINATION_COM}&nbsp;</div>
		</div>
	</body>
	</html>

	# ENDIF #
	
