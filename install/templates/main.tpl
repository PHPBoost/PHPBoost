${resources('install/install')}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title>{@installation.title} - {STEP_TITLE}</title>
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
	<div id="global">
		<div id="header_container">
		</div>
		<div id="left_menu">
            # IF NOT C_HAS_PREVIOUS_STEP #
			<table class="table_left">
				<tr>
					<td class="row_top">
						{@change_lang}
					</td>
				</tr>
				<tr>
					<td class="row_next row_final" style="text-align:center;">
						<form action="{U_CHANGE_LANG}" method="post">
							<p>
								<select name="new_language" id="change_lang" onchange="document.location='index.php?url=/welcome&amp;lang=' + document.getElementById('change_lang').value;">
									# START lang #
									<option value="{lang.LANG}" {lang.SELECTED}>{lang.LANG_NAME}</option>
									# END lang #
								</select>
								&nbsp;&nbsp;&nbsp;<img src="../images/stats/countries/{LANG_IDENTIFIER}.png" alt="" class="valign_middle" />
							</p>
							<p id="button_change_lang">
								<input type="submit" class="submit" value="{@change}" />
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
			# END IF #
			<table class="table_left">
				<tr>
					<td class="row_top">
						{@steps_list}
					</td>
				</tr>
				# START step #
					<tr>
						<td class="{step.CSS_CLASS}">
							<img src="templates/images/{step.IMG}" alt="${escape(step.NAME)}" class="valign_middle" />&nbsp;&nbsp;{step.NAME}
						</td>				
					</tr>
				# END step #
			</table>
			<table class="table_left">
				<tr>
					<td class="row_top">
						{@install_progress}
					</td>
				</tr>
				<tr>
					<td class="row_next row_final">
						<div style="margin:auto;width:200px">
							<div style="text-align:center;margin-bottom:5px;">{STEP_TITLE} :&nbsp;{PROGRESSION}%</div>
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
						{@appendices}
					</td>
				</tr>
				<tr>
					<td class="row_next">
						<img src="templates/images/help.png" alt="{@documentation}" class="valign_middle" />
						&nbsp;&nbsp;
						<a href="{U_DOCUMENTATION}">{@documentation}</a>
					</td>
				</tr>
				<tr>
					<td class="row_next row_final">
						<img src="templates/images/intro.png" alt="{@restart_installation}" class="valign_middle" />
						&nbsp;&nbsp;
						<a href="{U_RESTART}" onclick="return confirm('{@confirm_restart_installation}');">{@restart_installation}</a>
					</td>
				</tr>					
			</table>
		</div>
		
		<div id="main">
			<table class="table_contents">
				<tr> 
					<th colspan="2">
						<div style="text-align:right;padding-top:5px;padding-right:30px;"><img src="templates/images/phpboost.png" alt="Logo PHPBoost" class="valign_middle" /> {STEP_TITLE}</div>
					</th>
				</tr>
				
				<tr> 				
					<td class="row_contents">						
                        # INCLUDE installStep #					
					</td>
				</tr>
			</table>		
		</div>
	</div>
	<div id="footer">
		<span>
			{@powered_by} <a style="font-size:10px" href="http://www.phpboost.com" title="PHPBoost">PHPBoost {PHPBOOST_VERSION}</a> {@phpboost_right}
		</span>	
	</div>
	</body>
</html>
