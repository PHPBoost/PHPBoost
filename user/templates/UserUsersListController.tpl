<section id="module-user-users-list">
	<header>
		<h1>{@users}</h1>
	</header>
	<div class="content">
		# INCLUDE FORM #

		# IF C_ARE_GROUPS #
			# INCLUDE SELECT_GROUP #
		# ENDIF #
		<div class="spacer"></div>
		# INCLUDE TABLE #
	</div>
	<footer></footer>
</section>
