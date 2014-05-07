${resources('update/update')}
<!DOCTYPE html>
<html lang="{L_XML_LANGUAGE}">
	<head>
		<title>{@update.title} - {STEP_TITLE}</title>
		<meta charset="iso-8859-1" />
		<meta name="description" content="PHPBoost" />
		<meta name="robots" content="noindex, follow" />
		
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/default/theme/default.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/kernel/lib/css/font-awesome/css/font-awesome.css" />
		<link type="text/css" href="templates/update.css" title="phpboost" rel="stylesheet" />
		<script src="{PATH_TO_ROOT}/kernel/lib/js/top.js"></script>
		<link rel="shortcut icon" href="{PATH_TO_ROOT}/favicon.ico" type="image/x-icon" />
        <script>
        <!--
        var PATH_TO_ROOT = "{PATH_TO_ROOT}";
        var TOKEN = "{TOKEN}";
        var THEME = "{THEME}";
        -->
        </script>
	</head>
	<body>
	<div id="global">
		<div id="header-container">
			<div id="site-infos">
			<div id="site-logo"></div>
			<div id="site-name-container">
				<a id="site-name" title="PHPBoost CMS">PHPBoost CMS</a>
				<span id="site-slogan">Créez votre site internet facilement et gratuitement !</span>
			</div>
		</div>
		</div>
		<div id="left-menu">
            # IF NOT C_HAS_PREVIOUS_STEP #
			<table class="table-left">
				<tr>
					<td class="row-top">
						{@language.change}
					</td>
				</tr>
				<tr>
					<td class="row-next row-final" style="text-align:center;">
						<form action="{U_CHANGE_LANG}" method="post">
							<p>
								<select name="new_language" id="change_lang" onchange="document.location='index.php?lang=' + document.getElementById('change_lang').value;">
									# START lang #
									<option value="{lang.LANG}" {lang.SELECTED}>{lang.LANG_NAME}</option>
									# END lang #
								</select>
								&nbsp;&nbsp;&nbsp;<img src="../images/stats/countries/{LANG_IDENTIFIER}.png" alt="" class="valign-middle" />
							</p>
							<p id="button_change_lang">
								<button type="submit" value="true">{@change}</button>
							</p>
							<script>
							<!--
								document.getElementById('button_change_lang').style.display = 'none';
							-->
							</script>
						</form>
					</td>
				</tr>
			</table>
			# END IF #
			<table class="table-left">
				<tr>
					<td class="row-top">
						{@steps.list}
					</td>
				</tr>
				# START step #
					<tr>
						<td class="{step.CSS_CLASS}">
							<img src="templates/images/{step.IMG}" alt="${escape(step.NAME)}" class="valign-middle" />&nbsp;&nbsp;{step.NAME}
						</td>
					</tr>
				# END step #
			</table>
			<table class="table-left">
				<tr>
					<td class="row-top">
						{@installation.progression}
					</td>
				</tr>
				<tr>
					<td class="row-next row-final">
						<div class="progressbar-container">
							<span class="progressbar-infos">{PROGRESSION}%</span>
							<div class="progressbar" style="width:{PROGRESSION}%"></div>
						</div>
					</td>
				</tr>
			</table>
		</div>
		
		<div id="main">
			<div id="main-content">
				<div style="text-align: right; padding: 0px 30px 5px 0px; margin-bottom: 10px; border-bottom: 1px solid #EFEFEF;">
					<img src="templates/images/phpboost.png" alt="Logo PHPBoost" class="valign-middle" /> {STEP_TITLE}
				</div>
				# INCLUDE UpdateStep #
			</div>
		</div>
		
		<div class="spacer"></div>
	</div>
	
	<div id="footer">
		<span>
			{@poweredBy} <a style="font-size:10px" href="http://www.phpboost.com" title="PHPBoost">PHPBoost {PHPBOOST_VERSION}</a> {@phpboost.rights}
		</span>
	</div>
	<script src="{PATH_TO_ROOT}/kernel/lib/js/bottom.js"></script>
	</body>
</html>
