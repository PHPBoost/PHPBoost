${resources('update/update')}
<!DOCTYPE html>
<html lang="{L_XML_LANGUAGE}">
	<head>
		<title>{@update.title} - {STEP_TITLE}</title>
		<meta charset="UTF-8" />
		<meta name="description" content="PHPBoost" />
		<meta name="robots" content="noindex, follow" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="stylesheet" href="{PATH_TO_ROOT}/update/templates/@import.css" type="text/css" />

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
							<span>{@language.change}</span>
						</h5>
						<form class="align-center" action="{U_CHANGE_LANG}" method="post">
							<label for="change_lang">
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

							</label>
							<p id="button_change_lang">
								<button type="submit" value="true" class="button submit">{@change}</button>
								<input type="hidden" name="token" value="{TOKEN}">
							</p>
							<script>
								jQuery('#button_change_lang').hide();
							</script>
						</form>
					# END IF #

					<h5 class="menu-title">
						<div class="site-logo"></div>
						<span>{@steps.list}</span>
					</h5>
					<nav class="cssmenu cssmenu-vertical step-menu">
						<ul>
						# START step #
							<li class="{step.CSS_CLASS} {STEPS_NUMBER}-steps">
								<span class="cssmenu-title">
									<i class="fa fa-{step.IMG} fa-fw" aria-hidden="true"></i><span>{step.NAME}</span>
								</span>
							</li>
						# END step #
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
							<h1><div id="site-logo"></div> {STEP_TITLE}</h1>
						</header>
						<article>
							# INCLUDE UpdateStep #
						</article>
						<footer></footer>
					</section>
				</div>
			</div>
			<footer id="footer">
				<span>${LangLoader::get_message('common.powered.by', 'common-lang')} <a href="https://www.phpboost.com" aria-label="{@phpboost.link}">PHPBoost</a> {@phpboost.rights}</span>
			</footer>
		</div>
		<script>
			jQuery('.lang-selector').selectImg({
				ariaLabel : ${escapejs(LangLoader::get_message('common.click.to.select', 'common-lang'))}
			});
		</script>
	</body>
</html>
