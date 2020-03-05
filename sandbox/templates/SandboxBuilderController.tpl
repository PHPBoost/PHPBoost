<section>
	# INCLUDE SANDBOX_SUBMENU #
	<header>
		<h1>
			{@sandbox.module.title} - {@title.builder}
		</h1>
	</header>

	<article class="content">
		{@H|builder.explain}
		# IF C_PREVIEW #
			<div class="message-helper bgc notice">Prévisualisation</div>
		# ENDIF #

		# IF C_RESULT #
			<strong>TEXT :</strong> {TEXT}
			<div class="spacer"></div>
			<strong>MAIL :</strong> {MAIL}
			<div class="spacer"></div>
			<strong>WEB :</strong> {WEB}
			<div class="spacer"></div>
			<strong>AGE :</strong> {AGE}
			<div class="spacer"></div>
			<strong>MULTI_LINE_TEXT :</strong> {MULTI_LINE_TEXT}
			<div class="spacer"></div>
			<strong>RICH_TEXT :</strong> {RICH_TEXT}
			<div class="spacer"></div>
			<strong>RADIO :</strong> {RADIO}
			<div class="spacer"></div>
			<strong>CHECKBOX :</strong> {CHECKBOX}
			<div class="spacer"></div>
			<strong>SELECT :</strong> {SELECT}
			<div class="spacer"></div>
			<strong>HIDDEN :</strong> {HIDDEN}
			<div class="spacer"></div>
			<strong>DATE :</strong> {DATE}
			<div class="spacer"></div>
			<strong>DATE_TIME :</strong> {DATE_TIME}
			<div class="spacer"></div>
			<strong>FILE :</strong> {FILE}
			<div class="spacer"></div>
			<strong>HIDDEN TEXT AREA TEXT FIELD :</strong> {H_T_TEXT_FIELD}
		# ENDIF #

		# INCLUDE form #

	</article>
	<footer></footer>
</section>
