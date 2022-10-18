<header>
	<h2>{@install.db.parameters.config}</h2>
</header>

<div class="content">
	# IF SUCCESS #
		<div class="message-helper bgc success">{SUCCESS}</div>
	# END #
	# IF ERROR #
		<div class="message-helper bgc error">{ERROR}</div>
	# END #
	<div class="cell-flex cell-columns-2">
		<div class="cell cell-3-4">
			<div class="cell-body">
				<div class="cell-content">{@H|install.db.parameters.config.description}</div>
			</div>
		</div>
		<div class="cell cell-1-4">
			<div class="cell-thumbnail cell-center">
				<img src="templates/images/mysql.webp" alt="MySQL" />
				<a class="cell-thumbnail-caption" href="https://www.mysql.com/" target="_blank" rel="noopener noreferrer">mysql.com</a>
			</div>
		</div>
	</div>
</div>

<footer>
	<div class="content next-step">
		# INCLUDE DATABASE_FORM #
	</div>
</footer>
