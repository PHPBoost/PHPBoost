<div class="content">
	<div class="float-right pbt-box align-center">
		<img src="templates/images/installboost.webp" alt="{@update.phpboost.logo}" />
	</div>

    <header>
        <h2>{@update.step.welcome.message}</h2>
    </header>

	{@H|update.step.welcome.clue}
	# IF C_PUT_UNDER_MAINTENANCE #{@H|update.step.welcome.maintenance_notice}# ENDIF #
	{@H|update.step.welcome.team_signature}
</div>

<footer>
	<div class="next-step">
		# INCLUDE SERVER_FORM #
	</div>
</footer>
