	<header>
		<h2>{@db.parameters.config}</h2>
	</header>

	<div class="content">
		# IF SUCCESS #
			<div class="message-helper bgc success">{SUCCESS}</div>
		# END #
		# IF ERROR #
			<div class="message-helper bgc error">{ERROR}</div>
		# END #
		<div class="float-right pbt-box align-center">
			<a href="http://www.mysql.com/">
				<img src="templates/images/mysql.png" alt="MySQL" class="float-right" />
			</a>
		</div>

		{@H|db.parameters.config.explanation}

	</div>

	<footer>
		<div class="content next-step">
			# INCLUDE DATABASE_FORM #
		</div>
	</footer>
