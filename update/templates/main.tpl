${resources('update/update')}
<!DOCTYPE html>
<html lang="{L_XML_LANGUAGE}">
	<head>
		<title>{@update.title} - {STEP_TITLE}</title>
		<meta charset="UTF-8" />
		<meta name="description" content="PHPBoost" />
		<meta name="robots" content="noindex, follow" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link type="text/css" href="{PATH_TO_ROOT}/update/templates/@import.css" title="phpboost" rel="stylesheet" />

		<script>
			<!--
			var PATH_TO_ROOT = "{PATH_TO_ROOT}";
			var TOKEN = "{TOKEN}";
			var THEME = "{THEME}";
			-->
		</script>
		<script src="{PATH_TO_ROOT}/kernel/lib/js/jquery/jquery.js"></script>
		<script src="{PATH_TO_ROOT}/kernel/lib/js/global.js"></script>
		<script src="{PATH_TO_ROOT}/templates/default/plugins/sticky.footer.js"></script>
		<link rel="shortcut icon" href="{PATH_TO_ROOT}/favicon.ico" type="image/x-icon" />
	</head>
	<body>
		<header id="header-admin">
			<div class="header-admin-container">
				<div id="top-header-admin">
					<div id="site-name-container">
						<div id="update-site-logo"></div>
						<a id="site-name" title="PHPBoost CMS">PHPBoost CMS</a>
						<span id="site-slogan">{@phpboost.slogan}</span>
					</div>
				</div>
				<div id="sub-header-admin">
					# IF NOT C_HAS_PREVIOUS_STEP #
					<div>
						<h3 class="menu-title">
							<div class="site-logo"></div>
							{@language.change}
						</h3>
						<form class="center" action="{U_CHANGE_LANG}" method="post">
							<label for="change_lang">
								<select name="new_language" id="change_lang" onchange="document.location='index.php?lang=' + document.getElementById('change_lang').value;">
									# START lang #
									<option value="{lang.LANG}" {lang.SELECTED}>{lang.LANG_NAME}</option>
									# END lang #
								</select>
								&nbsp;&nbsp;&nbsp;<img src="{PATH_TO_ROOT}/images/stats/countries/{LANG_IDENTIFIER}.png" alt="{LANG_NAME}" title="{LANG_NAME}" class="valign-middle" />
							</label>
							<p id="button_change_lang">
								<button type="submit" value="true" class="submit">{@change}</button>
								<input type="hidden" name="token" value="{TOKEN}">
							</p>
							<script>
							<!--
								jQuery('#button_change_lang').hide();
							-->
							</script>
						</form>
					</div>
					# END IF #

					<div>
						<h3 class="menu-title">
							<div class="site-logo"></div>
							{@steps.list}
						</h3>
						<nav class="cssmenu cssmenu-vertical step-menu">
							<ul>
							# START step #
								<li class="{step.CSS_CLASS}# IF step.C_NO_DATABASE_STEP_CLASS # no-database-step# ENDIF #">
									<span class="cssmenu-title">
										<i class="fa fa-{step.IMG} fa-fw" aria-hidden="true"></i><span>{step.NAME}</span>
									</span>
								</li>
							# END step #
							</ul>
						</nav>
					</div>

					<div>
						<h3 class="menu-title">
							<div class="site-logo"></div>
							{@installation.progression}
						</h3>
						<div class="progressbar-container">
							<span class="progressbar-infos">{PROGRESSION}%</span>
							<div class="progressbar" style="width:{PROGRESSION}%"></div>
						</div>
					</div>

				</div>
			</div>
		</header>

		<section id="global">
			<header>
				<h1><div class="site-logo"></div> {STEP_TITLE}</h1>
			</header>
			<article>
				# INCLUDE UpdateStep #
			</article>
			<footer id="footer" class="sticky-footer">
				<span>{@poweredBy} <a href="https://www.phpboost.com" aria-label="{@phpboost.link}">PHPBoost</a> {@phpboost.rights}</span>
			</footer>
		</section>
	</body>
</html>
