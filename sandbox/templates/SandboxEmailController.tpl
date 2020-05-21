<section id="sandbox-email">
	# INCLUDE SANDBOX_SUBMENU #
	<header>
		<h1>{@sandbox.module.title} - {@title.email}</h1>
	</header>
	<div class="content">
		# IF C_EMAIL_SENT #
			# IF C_SUCCESS #
				<div class="message-helper bgc success">{@email.success}</div>
			# ELSE #
				<div class="message-helper bgc error">{ERROR}</div>
			# ENDIF #
		# ENDIF #

		# INCLUDE SMTP_FORM #
	</div>
</section>
