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
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/content.css" type="text/css" media="screen, print, handheld" />
		
		<script type="text/javascript" src="{PATH_TO_ROOT}/TinyMCE/templates/js/tinymce/tiny_mce_popup.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/TinyMCE/templates/js/tinymce/plugins/emotions/js/emotions.js"></script>
	</head>
	<body>
		<table>
			<thead>
				<tr> 
					<th colspan="{COLSPAN}">{L_SMILEY}</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					# START smiley #
						{smiley.TR_START}
							<td>
								<a href="javascript:EmotionsDialog.insert('{smiley.URL}', '{smiley.CODE}')">{smiley.IMG}</a>&nbsp;&nbsp;
							</td>
							# START smiley.td #
							{smiley.td.TD}
							# END smiley.td #
						{smiley.TR_END}
					# END smiley #
				</tr>
			</tbody>
		</table>
	</body>
</html>