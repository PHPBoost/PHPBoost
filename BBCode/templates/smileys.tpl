<!DOCTYPE html>
<html lang="{L_XML_LANGUAGE}">
	<head>
		<title>{SITE_NAME} : {TITLE}</title>
		<meta charset="iso-8859-1" />

		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/default.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/global.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/content.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}{U_CSS_FILE}" type="text/css" media="screen, print, handheld" />
	</head>
	<body>
		<script type="text/javascript">
		<!--
		function insert_popup(code) 
		{
			window.opener.document.getElementById("{FIELD}").value += ' ' + code;
		}
		-->
		</script>

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
								<a href="javascript:insert_popup('{smiley.CODE}')">{smiley.IMG}</a>&nbsp;&nbsp;
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