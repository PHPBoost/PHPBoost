<header>
	<h2>{@db.parameters.config}</h2>
</header>

<div class="content">
	<div class="float-right pbt-box align-center">
		<a href="https://www.mysql.com/" target="_blank" rel="noopener noreferrer">
			<img src="templates/images/mysql.webp" alt="MySQL" />
		</a>
	</div>
	<span class="spacer">&nbsp;</span>
	{@H|db.parameters.config.explanation}
	# IF ERROR #
		<div class="message-helper bgc error">{ERROR}</div>
	# END #
</div>

<footer>
	<div class="next-step">
		# INCLUDE DATABASE_FORM #
	</div>
</footer>
