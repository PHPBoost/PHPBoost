
	<header>
		<h2>{@install.welcome.message}</h2>
	</header>

	<div class="content">
		<div class="cell-flex cell-columns-2">
			<div class="cell">
				<div class="cell-body">
					<div class="cell-content">
						{@H|install.welcome.description}
					</div>
				</div>
			</div>
			<div class="cell align-center">
				<div class="cell-body">
					<div class="cell-thumbnail">
						<img src="templates/images/PHPBoost_car.png" alt="{@install.title}" />
					</div>
				</div>
			</div>
		</div>

		<h3>${set(@install.welcome.distribution, ['distribution': @distribution.name])}</h3>
		<p>${html(@install.welcome.distribution.description)}</p>
		<p>${html(@distribution.description)}</p>
	</div>

	<footer>
		<div class="next-step">
			# INCLUDE LICENSE_FORM #
		</div>
	</footer>
