
	<header>
		<h2>{@step.welcome.message}</h2>
	</header>

	<div class="content">
		<div class="float-right pbt-box align-center">
			<img src="templates/images/PHPBoost_box.png" alt="{@phpboost.logo}" />
		</div>
		<p>{@H|step.welcome.explanation}</p>
		<h3>${set(@step.welcome.distribution, ['distribution': @distribution.name])}</h3>
		<p>${html(@step.welcome.distribution.explanation)}</p>
		<p>${html(@distribution.description)}</p>
	</div>

	<footer>
		<div class="next-step">
			# INCLUDE LICENSE_FORM #
		</div>
	</footer>
