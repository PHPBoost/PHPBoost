	<header>
		<h2>{@step.execute.message}</h2>
	</header>
	
	<div class="content">
		{@H|step.execute.explanation}
		# INCLUDE INCOMPATIBLE_MODULES #
		# INCLUDE INCOMPATIBLE_THEMES #
		# INCLUDE INCOMPATIBLE_LANGS #
	</div>
	
	<footer>
		<div class="next-step">
			# INCLUDE SERVER_FORM #
		</div>
	</footer>
	
	<div id="update-in-progress-container">
		<div class="update-in-progress-background"></div>
		<div class="update-in-progress-spinner">
			<i class="fa fa-spin fa-cog fa-2x"></i>
			<span>{@step.execute.update_in_progress}</span>
		</div>
	</div>