<section>
	<header>
		<h1>{@title.mail_sender}</h1>
	</header>
	<div class="content">
		# IF C_MAIL_SENT # 
			# IF C_SUCCESS # 
				<div class="message-helper success">
					<i class="fa fa-success"></i>
					<div class="message-helper-content">{@mail.success}</div>
				</div>
			# ELSE # 
				<div class="message-helper error">
					<i class="fa fa-error"></i>
					<div class="message-helper-content">{ERROR}</div>
				</div> 
			# ENDIF #
		# ENDIF #
		
		# INCLUDE SMTP_FORM #
	</div>
</section>
