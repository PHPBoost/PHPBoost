	<header>
		<h2>{@db.parameters.config}</h2>
	</header>
	
	<div class="content">
		<a href="http://www.mysql.com/" title="MySQL">
			<img src="templates/images/mysql.png" alt="MySQL" style="float:right;margin-bottom:5px;margin-left:5px;" />
		</a>
		<span class="spacer">&nbsp;</span>
		{@H|db.parameters.config.explanation}
		# IF ERROR #
			<div class="message-helper error">{ERROR}</div>
		# END #
	</div>
	
	<footer>
		<div class="next-step">
			# INCLUDE DATABASE_FORM #
		</div>
	</footer>

