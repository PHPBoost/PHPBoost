<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
		<title>{SITE_NAME} : {TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta http-equiv="Content-Language" content="{L_XML_LANGUAGE}" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/default.css" type="text/css" media="screen, print, handheld" />
        <!-- Theme CSS -->
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/global.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/generic.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/content.css" type="text/css" media="screen, print, handheld" />
        <link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/bbcode.css" type="text/css" media="screen, print, handheld" />
		
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/tinymce/tiny_mce_popup.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/tinymce/plugins/emotions/js/emotions.js"></script>
	</head>
	<body>
		<table class="module_table" style="margin:15px auto">
			<th>
				{L_SMILEY}
			</th>
			<tr class="row2">
				<td>
					<table class="module_table" style="width:96%;text-align:center;margin:15px auto">
					# START smiley #
						{smiley.TR_START}
							<td style="padding:4px;">
								<a href="javascript:EmotionsDialog.insert('{smiley.URL}','{smiley.CODE}');" title="{smiley.CODE}">{smiley.IMG}</a>
							</td>
							# START smiley.td #
							{smiley.td.TD}
							# END smiley.td #
						{smiley.TR_END}
					# END smiley #
					</table>
				</td>
			</tr>
		</table>	
	</body>
</html>