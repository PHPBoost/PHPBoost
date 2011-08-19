<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
		<title>{SITE_NAME} : {TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="Content-Language" content="{L_XML_LANGUAGE}" />
		<!-- Default CSS -->
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/default.css" type="text/css" media="screen, print, handheld" />
        <!-- Theme CSS -->
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/global.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/generic.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/content.css" type="text/css" media="screen, print, handheld" />
        <link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/bbcode.css" type="text/css" media="screen, print, handheld" />
        <link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/framework/content/syndication/syndication.css" type="text/css" media="screen, print, handheld" />
	</head>
	<body>
		<script type="text/javascript">
		<!--
		function insert_popup(code) 
		{
			window.opener.document.getElementById("{FIELD}").value += ' ' + code;
		}
		function fermer()
		{
			opener=self;
			self.close();
		}
		//-->
		</script>

		<table class="module_table" style="margin:15px auto">
			<th colspan="{COLSPAN}">
				{L_SMILEY}
			</th>
			<tr class="row2">
				<td>
					<table class="module_table" style="width:auto;text-align:center;margin:15px auto">
					# START smiley #
						{smiley.TR_START}
							<td>
								<a href="javascript:insert_popup('{smiley.CODE}')">{smiley.IMG}</a>&nbsp;&nbsp;
							</td>
							# START smiley.td #
							{smiley.td.TD}
							# END smiley.td #
						{smiley.TR_END}
					# END smiley #
					</table>
					<p style="text-align:center;">
						<a href="javascript:fermer()" title="{L_CLOSE}"><span style="color:red;">{L_CLOSE}</span></a>
					</p>
				</td>
			</tr>
			<th colspan="{COLSPAN}">
				&nbsp;
			</th>
		</table>	
	</body>
</html>