<div class="content">
	<div class="float-right pbt-box align-center">
		<img src="templates/images/installboost.webp" alt="{@update.phpboost.logo}" />
	</div>

    <header>
        <h2>{@update.step.introduction.message}</h2>
    </header>

	{@H|update.step.introduction.clue}
	# IF C_PUT_UNDER_MAINTENANCE #{@H|update.step.introduction.maintenance_notice}# ENDIF #
	{@H|update.step.introduction.team_signature}
</div>

<footer>
	<div class="next-step">
		# INCLUDE SERVER_FORM #
	</div>
</footer>
