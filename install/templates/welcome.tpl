<header></header>

<div class="content">
	<div class="cell-flex cell-columns-2">
		<div class="cell">
			<header><h2>{@H|install.welcome.message}</h2></header>
            <div class="cell-content">
				{@H|install.welcome.description}
            </div>
            <header><h2>${set(@install.welcome.distribution, ['distribution': @distribution.name])}</h2></header>
            <p>${html(@install.welcome.distribution.description)}</p>
            <p>${html(@distribution.description)}</p>
		</div>
        <div class="cell align-center">
            <div class="cell-thumbnail">
                <img src="templates/images/installboost.webp" alt="{@install.title}" />
            </div>
        </div>
	</div>
</div>

<footer>
	<div class="next-step">
		# INCLUDE LICENSE_FORM #
	</div>
</footer>
