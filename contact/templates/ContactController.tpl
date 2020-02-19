<section id="module-contact">
	<header>
		<h1>{@module_title}</h1>
	</header>
	<div class="content">
		# INCLUDE MSG #
		
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
			<div class="align-center"><a href="#">{@contact.send_another_mail}</a></div>
		# ENDIF #
	</div>
	<footer></footer>
</section>
