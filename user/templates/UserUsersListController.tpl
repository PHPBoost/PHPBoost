<section id="module-user-users-list">
	<header>
		<h1>{@users}</h1>
	</header>
	<div class="content">
		# IF C_ARE_GROUPS #
		<div class="options user-group-select">
			# INCLUDE SELECT_GROUP #
		</div>
		# ENDIF #

		# INCLUDE table #
	</div>
	<footer></footer>
</section>