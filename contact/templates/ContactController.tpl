<section>
	<header>
		<h1>{@title_contact}</h1>
	</header>
	<div class="content">
		# INCLUDE MSG #
		
		# IF C_INFORMATIONS_TOP #
		<p>{INFORMATIONS}</p>
		<div class="spacer">&nbsp;</div>
		# ENDIF #
		
		# IF C_INFORMATIONS_SIDE #
		<div>
		# ENDIF #
		
		# IF C_INFORMATIONS_LEFT #
		<div class="float_left informations_side">
			<p>{INFORMATIONS}</p>
		</div>
		# ENDIF #
		
		# IF C_INFORMATIONS_SIDE #
		<div class="# IF C_INFORMATIONS_LEFT #float_right# ELSE #float_left# ENDIF # form_side">
		# ENDIF #
		# INCLUDE FORM #
		# IF C_INFORMATIONS_SIDE #
		</div>
		# ENDIF #
		
		# IF C_INFORMATIONS_RIGHT #
		<div class="float_right informations_side">
			<p>{INFORMATIONS}</p>
		</div>
		# ENDIF #
		
		# IF C_INFORMATIONS_SIDE #
			<div class="spacer">&nbsp;</div>
		</div>
		# ENDIF #
		
		# IF C_INFORMATIONS_BOTTOM #
		<p>{INFORMATIONS}</p>
		<div class="spacer">&nbsp;</div>
		# ENDIF #
	</div>
	<footer></footer>
</section>