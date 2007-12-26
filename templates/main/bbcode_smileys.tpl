<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"  />
<meta http-equiv="Content-Style-Type" content="text/css" />

<title>{L_TITLE}</title>
<link rel="stylesheet" href="../templates/{THEME}/{THEME}.css" type="text/css" />
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

	<table class="module_table">
		<th colspan="{COLSPAN}">
			{L_SMILEY}
		</th>
		<tr class="row2">
			<td>
				<table border="0" style="margin:auto;">
				# START smiley #
					{smiley.TR_START}
						<td>
							<a href="javascript:insert_popup('{smiley.CODE}')">{smiley.IMG}</a>&nbsp;&nbsp;
						</td>
						# START td #
						{smiley.td.TD}
						# END td #
					{smiley.TR_END}
				# END smiley #
				</table>
			</td>
		</tr>
		<th colspan="{COLSPAN}">
			<a href="javascript:fermer()" title="{L_CLOSE}"><span style="color:red;">{L_CLOSE}</span></a>
		</th>
	</table>	

</body>
</html>