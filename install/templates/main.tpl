${resources('install/common')}
<!DOCTYPE html>
<html lang="{@common.xml.lang}">
	<head>
		<meta charset="UTF-8" />
		<title>{@install.title} - {STEP_TITLE}</title>
		<meta name="description" content="PHPBoost" />
		<meta name="robots" content="noindex, follow" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="stylesheet" href="{PATH_TO_ROOT}/install/templates/@import.css" type="text/css" media="screen, print" />
		# IF C_ADDITIONAL_STYLESHEET #<link rel="stylesheet" href="{PATH_TO_ROOT}{ADDITIONAL_STYLESHEET_URL}" type="text/css" media="screen, print" /> # ENDIF #

		<script>
			var PATH_TO_ROOT = "{PATH_TO_ROOT}";
			var TOKEN = "{TOKEN}";
			var THEME = "{THEME}";
		</script>
		<script src="{PATH_TO_ROOT}/kernel/lib/js/jquery/jquery.js"></script>
		<script src="{PATH_TO_ROOT}/templates/__default__/plugins/@global.js"></script>
		<script src="{PATH_TO_ROOT}/templates/__default__/plugins/selectimg.js"></script>
		<script src="{PATH_TO_ROOT}/templates/__default__/plugins/tooltip.js"></script>
		<link rel="shortcut icon" href="{PATH_TO_ROOT}/favicon.ico" type="image/x-icon" />
	</head>
	<body>
		<header id="header-admin">
			<div class="header-admin-container">
				<div id="top-header-admin">
					<div id="site-name-container">
						<a id="site-name">PHPBoost CMS</a>
					</div>
				</div>
				<div id="sub-header-admin">
					# IF NOT C_HAS_PREVIOUS_STEP #
						<h5 class="menu-title">
							<div class="site-logo"></div>
							<span>{@install.change.language}</span>
						</h5>
						<form class="align-center" action="{U_CHANGE_LANG}" method="post">
							<select
								id="change_lang"
								class="lang-selector"
								name="new_language"
								onchange="document.location='index.php?lang=' + document.getElementById('change_lang').value;">
								# START lang #
									<option value="{lang.LANG}" {lang.SELECTED} data-option-img="{PATH_TO_ROOT}/images/stats/countries/{lang.LANG_IDENTIFIER}.png">
										{lang.LANG_NAME}
									</option>
								# END lang #
							</select>
						</form>
					# ENDIF #

					<h5 class="menu-title">
						<div class="site-logo"></div>
						<span>{@install.steps.list}</span>
					</h5>
					<nav class="cssmenu cssmenu-vertical step-menu">
						<ul>
						# START step #
							<li class="{step.CSS_CLASS}">
								<span class="cssmenu-title">
									<i class="fa fa-{step.IMG} fa-fw" aria-hidden="true"></i><span>{step.NAME}</span>
								</span>
							</li>
						# END step #
						</ul>
					</nav>

					<h5 class="menu-title">
						<div class="site-logo"></div>
						<span>{@install.appendices}</span>
					</h5>
					<nav class="cssmenu cssmenu-vertical help-menu">
						<ul>
							<li>
								<a class="cssmenu-title" href="{@install.documentation.link}">
									<i class="fa fa-book fa-fw" aria-hidden="true"></i> <span>{@form.documentation} (fr)</span>
								</a>
							</li>
							<li>
								<a class="cssmenu-title" href="{U_RESTART}" onclick="return confirm('${escapejs(@install.confirm.restart)}');">
									<i class="fa fa-sync fa-fw" aria-hidden="true"></i> <span>{@install.restart}</span>
								</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</header>

		<div id="global">
			<div id="admin-main">
				<div id="admin-contents">
					<section>
						<header>
							<h1>{STEP_TITLE}</h1>
						</header>
						<article>
							# INCLUDE INSTALL_STEP #
						</article>
						<footer></footer>
					</section>
				</div>
			</div>
			<footer id="footer">
				<span>{@common.powered.by} <a href="https://www.phpboost.com" aria-label="{@common.phpboost.link}">PHPBoost</a> {@common.phpboost.right}</span>
			</footer>
		</div>
		<script>
			jQuery('.lang-selector').selectImg({
				ariaLabel : ${escapejs(@common.click.to.select)}
			});
		</script>
	</body>
</html>
