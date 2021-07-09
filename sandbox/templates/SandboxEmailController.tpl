<section id="module-sandbox-email">
	<header class="section-header">
		<h1>{@sandbox.module.title} - {@sandbox.email}</h1>
	</header>
	
	# INCLUDE SANDBOX_SUBMENU #
	
	<div class="sub-section">
		<div class="content-container">
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
		</div>
	</div>
	<footer></footer>
</section>
