# IF POPUP_PAGE_COM #
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
<head>
	<title>{L_TITLE}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"  />
	<meta http-equiv="Content-Language" content="{L_XML_LANGUAGE}" />
	<!-- Default CSS -->
	<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/default.css" type="text/css" media="screen, print, handheld" />
	<!-- Theme CSS -->
	<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/design.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/global.css" type="text/css" media="screen, print, handheld" />
	<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/generic.css" type="text/css" media="screen, print, handheld" />
	<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/content.css" type="text/css" media="screen, print, handheld" />
	<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/bbcode.css" type="text/css" media="screen, print, handheld" />
	<link rel="shortcut icon" href="{PATH_TO_ROOT}/favicon.ico" type="image/x-icon" />
	<script type="text/javascript">
	<!--
		var PATH_TO_ROOT = "{PATH_TO_ROOT}";
	-->
	</script>
	# IF C_BBCODE_TINYMCE_MODE # <script language="javascript" type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/content/tinymce/tiny_mce.js"></script> # ENDIF #
	
	<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/scriptaculous/prototype.js"></script>
	<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/scriptaculous/scriptaculous.js"></script>
	<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/global.js"></script>
</head>

<body>
# ENDIF #
	
		<script type="text/javascript">
		<!--
		function Confirm_com_del() {
			return confirm("{L_DELETE_MESSAGE}");
		}
		-->
		</script>

		# IF AUTH_POST_COM #
		<span id="anchor_{SCRIPT}"></span>
		{COM_FORM}
		# ENDIF #
		
		{ERROR_HANDLER}
		
		# IF C_COM_DISPLAY #
		<div class="msg_position">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top">
				<div style="float:left;">{PAGINATION_COM}&nbsp;</div>
				<div style="float:right;text-align: center;">
					# IF COM_LOCK #
					<a href="{U_LOCK}">{L_LOCK}</a> <a href="{U_LOCK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/{IMG}.png" alt="" class="valign_middle" /></a>
					# ENDIF #
				</div>
			</div>	
		</div>
		# START com_list #
		<div class="msg_position">
			<div class="msg_container{com_list.CLASS_COLOR}">
				<span id="m{com_list.ID}"></span>
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						{com_list.USER_ONLINE} {com_list.USER_PSEUDO}
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="{com_list.U_ANCHOR}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/ancre.png" alt="{com_list.ID}" /></a> {com_list.DATE}</div>
					<div style="float:right;">
						<a href="{com_list.U_QUOTE}" title=""><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/quote.png" alt="{L_QUOTE}" title="{L_QUOTE}" class="valign_middle" /></a>
						# IF com_list.C_COM_MSG_EDIT # 
						&nbsp;&nbsp;<a href="{com_list.U_COM_EDIT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" title="{L_EDIT}" class="valign_middle" /></a>
						# ENDIF #
						
						# IF com_list.C_COM_MSG_DEL #
						&nbsp;&nbsp;<a href="{com_list.U_COM_DEL}" onclick="javascript:return Confirm_com_del();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" class="valign_middle" /></a>
						# ENDIF #
						&nbsp;&nbsp;
					</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;">{com_list.USER_RANK}</p>
						<p style="text-align:center;">{com_list.USER_IMG_ASSOC}</p>
						<p style="text-align:center;">{com_list.USER_AVATAR}</p>
						<p style="text-align:center;">{com_list.USER_GROUP}</p>
						{com_list.USER_SEX}
						{com_list.USER_DATE}<br />
						{com_list.USER_MSG}<br />
						{com_list.USER_LOCAL}
					</div>
					<div class="msg_contents{com_list.CLASS_COLOR}">
						<div class="msg_contents_overflow">
							{com_list.CONTENTS}
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign{com_list.CLASS_COLOR}">				
				<div class="msg_sign_overflow">
					{com_list.USER_SIGN}
				</div>				
				<hr />
				<div style="float:left;">
					{com_list.U_USER_PM} {com_list.USER_MAIL} {com_list.USER_MSN} {com_list.USER_YAHOO} {com_list.USER_WEB}
				</div>
				<div style="float:right;font-size:10px;">
					&nbsp;
					# IF C_IS_MODERATOR # 
					{com_list.USER_WARNING}%
					<a href="{com_list.U_COM_WARNING}" title="{L_WARNING_MANAGEMENT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/important.png" alt="{L_WARNING_MANAGEMENT}" class="valign_middle" /></a>
					<a href="{com_list.U_COM_PUNISHEMENT}" title="{L_PUNISHEMENT_MANAGEMENT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/readonly.png" alt="{L_PUNISHEMENT_MANAGEMENT}" class="valign_middle" /></a>
					# ENDIF #
				</div>&nbsp;
			</div>	
		</div>				
		# END com_list #		
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;">{PAGINATION_COM}&nbsp;</div>
		</div>
		# ENDIF #
	
# IF POPUP_PAGE_COM #
</body>
</html>
# ENDIF #
	
