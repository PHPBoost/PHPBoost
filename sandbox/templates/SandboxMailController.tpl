<section>
	<header>
		<h1>{@sandbox.module.title} - {@title.mail.sender}</h1>
	</header>
	<div class="content">
		# IF C_MAIL_SENT #
			# IF C_SUCCESS #
				<div class="message-helper bgc success">{@mail.success}</div>
			# ELSE #
				<div class="message-helper bgc error">{ERROR}</div>
			# ENDIF #
		# ENDIF #

		# INCLUDE SMTP_FORM #
	</div>
</section>
