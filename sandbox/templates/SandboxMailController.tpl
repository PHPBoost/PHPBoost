<section>
	<header>
		<h1>{@title.mail_sender}</h1>
	</header>
	<div class="content">
		# IF C_MAIL_SENT # 
			# IF C_SUCCESS # 
				<div class="success">{@mail.success}</div>
			# ELSE # 
				<div class="error">{ERROR}</div>
			# ENDIF #
		# ENDIF #
		
		# INCLUDE SMTP_FORM #
	</div>
</section>
