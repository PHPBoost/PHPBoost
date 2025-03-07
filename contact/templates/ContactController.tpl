<section id="module-contact">
	<header class="section-header">
		<h1>{@contact.module.title}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				# INCLUDE MESSAGE_HELPER #

				# IF NOT C_MAIL_SENT #

					# IF C_INFORMATIONS_TOP #
					<p>{INFORMATIONS}</p>
					<div class="spacer"></div>
					# ENDIF #

					# IF C_MAP_ENABLED #
						# IF C_MAP_TOP #
							{MAP}
						# ENDIF #
					# ENDIF #

					# IF C_INFORMATIONS_SIDE #
					<div>
					# ENDIF #

					# IF C_INFORMATIONS_LEFT #
					<div class="float-left informations-side">
						<p>{INFORMATIONS}</p>
					</div>
					# ENDIF #

					# IF C_INFORMATIONS_SIDE #
					<div class="# IF C_INFORMATIONS_LEFT #float-right# ELSE #float-left# ENDIF # form-side">
					# ENDIF #

					# INCLUDE FORM #

					# IF C_INFORMATIONS_SIDE #
					</div>
					# ENDIF #

					# IF C_INFORMATIONS_RIGHT #
					<div class="float-right informations-side">
						<p>{INFORMATIONS}</p>
					</div>
					# ENDIF #

					# IF C_INFORMATIONS_SIDE #
						<div class="spacer"></div>
					</div>
					# ENDIF #

					# IF C_INFORMATIONS_BOTTOM #
					<p>{INFORMATIONS}</p>
					<div class="spacer"></div>
					# ENDIF #

					# IF C_MAP_ENABLED #
						# IF C_MAP_BOTTOM #
							{MAP}
						# ENDIF #
					# ENDIF #

				# ELSE #
					<div class="spacer"></div>
					<div class="align-center"><a class="offload" href="${relative_url(ContactUrlBuilder::home())}">{@contact.send.another.email}</a></div>
				# ENDIF #
			</div>
		</div>
	</div>
	<footer></footer>
</section>
