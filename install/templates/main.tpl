<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title>{L_TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="description" content="PHPBoost" />
		<meta name="robots" content="noindex, follow" />
		<link type="text/css" href="templates/install.css" title="phpboost" rel="stylesheet" />
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/scriptaculous/prototype.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/scriptaculous/scriptaculous.js"></script>
		<script type="text/javascript" src="templates/global.js"></script>
		<link rel="shortcut icon" href="{PATH_TO_ROOT}/favicon.ico" type="image/x-icon" />
	</head>
	<body>
		<script type="text/javascript">
		<!--
			var step = {NUM_STEP};
		-->
		</script>
	<div id="global">
		<div id="header_container">
		</div>
		<div id="left_menu">
			<table class="table_left">
				<tr>
					<td class="row_top">
						{L_LANG}
					</td>
				</tr>
				<tr>
					<td class="row_next row_final" style="text-align:center;">
						<form action="{U_CHANGE_LANG}" method="post">
							<p>
								<select name="new_language" id="change_lang" onchange="document.location = 'install.php?step=' + step + '&amp;lang=' + document.getElementById('change_lang').value;">
									# START lang #
									<option value="{lang.LANG}" {lang.SELECTED}>{lang.LANG_NAME}</option>
									# END lang #
								</select>
								&nbsp;&nbsp;&nbsp;<img src="../images/stats/countries/{LANG_IDENTIFIER}.png" alt="" class="valign_middle" />
							</p>
							<p id="button_change_lang">
								<input type="submit" class="submit" value="{L_CHANGE}" />
							</p>
							<script type="text/javascript">
							<!--
								document.getElementById('button_change_lang').style.display = 'none';
							-->
							</script>
						</form>
					</td>
				</tr>
			</table>
			<table class="table_left">
				<tr>
					<td class="row_top">
						{L_STEPS_LIST}
					</td>
				</tr>
				# START link_menu #
					<tr>
						<td class="{link_menu.CLASS}">
							<img src="templates/images/{link_menu.STEP_IMG}" alt="" class="valign_middle" />&nbsp;&nbsp;{link_menu.STEP_NAME}
						</td>				
					</tr>
				# END link_menu #
			</table>
			<table class="table_left">
				<tr>
					<td class="row_top">
						{L_INSTALL_PROGRESS}
					</td>
				</tr>
				<tr>
					<td class="row_next row_final">
						<div style="margin:auto;width:200px">
							<div style="text-align:center;margin-bottom:5px;">{L_STEP} :&nbsp;{PROGRESS_LEVEL}%</div>
							<div style="float:left;height:12px;border:1px solid black;background:white;width:192px;padding:2px;padding-left:3px;padding-right:1px;">
								# START progress_bar #<img src="templates/images/progress.png" alt="" /># END progress_bar #
							</div>
						</div>
					</td>
				</tr>						
			</table>
			<table class="table_left">
				<tr>
					<td class="row_top">
						{L_APPENDICES}
					</td>
				</tr>
				<tr>
					<td class="row_next">
						<img src="templates/images/help.png" alt="{L_DOCUMENTATION}" class="valign_middle" />
						&nbsp;&nbsp;
						<a href="{U_DOCUMENTATION}">{L_DOCUMENTATION}</a>
					</td>
				</tr>
				<tr>
					<td class="row_next row_final">
						<img src="templates/images/intro.png" alt="{L_RESTART_INSTALL}" class="valign_middle" />
						&nbsp;&nbsp;
						<a href="{U_RESTART}" onclick="return confirm('{L_CONFIRM_RESTART}');">{L_RESTART_INSTALL}</a>
					</td>
				</tr>					
			</table>
		</div>
		
		<div id="main">
			<table class="table_contents">
				<tr> 
					<th colspan="2">
						<div style="text-align:right;padding-top:5px;padding-right:30px;"><img src="templates/images/phpboost.png" alt="Logo PHPBoost" class="valign_middle" /> {L_STEP}</div>
					</th>
				</tr>
				
				<tr> 				
					<td class="row_contents">						
						<span style="float:right;padding:8px;padding-top:0px;padding-right:25px">
							<img src="templates/images/PHPBoost_box3.0.png" alt="Logo PHPBoost" />
						</span>
						<h1>{STEP_TITLE}</h1>
						{STEP_EXPLANATION}
						
						<div style="margin-bottom:60px;">&nbsp;</div>
						
                        # INCLUDE step #
												
						<fieldset class="submit_case">
                        # IF C_HAS_PREVIOUS_STEP #
                            <a href="{PREVIOUS_STEP_URL}" title="{EL_PREVIOUS_STEP_TITLE}" >
                                <img src="templates/images/left.png" alt="{EL_PREVIOUS_STEP_TITLE}" class="valign_middle" />
                            </a>
                        # ENDIF #
                        # IF C_HAS_NEXT_STEP #
                            <a href="{NEXT_STEP_URL}" title="{EL_NEXT_STEP_TITLE}" >
                                <img src="templates/images/right.png" alt="{EL_NEXT_STEP_TITLE}" class="valign_middle" />
                            </a>
                        # ENDIF #
						</fieldset>						
					</td>
				</tr>
			</table>		
		</div>
	</div>
	<div id="footer">
		<span>
			{L_POWERED_BY} <a style="font-size:10px" href="http://www.phpboost.com" title="PHPBoost">PHPBoost {PHPBOOST_VERSION}</a> {L_PHPBOOST_RIGHT}
		</span>	
	</div>
	</body>
</html>
