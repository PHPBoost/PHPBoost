<section>
	<header>
		<h1>{@module_title}</h1>
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
		<div class="float-left informations_side">
			<p>{INFORMATIONS}</p>
		</div>
		# ENDIF #
		
		# IF C_INFORMATIONS_SIDE #
		<div class="# IF C_INFORMATIONS_LEFT #float-right# ELSE #float-left# ENDIF # form_side">
		# ENDIF #
		# INCLUDE FORM #
		# IF C_INFORMATIONS_SIDE #
		</div>
		# ENDIF #
		
		# IF C_INFORMATIONS_RIGHT #
		<div class="float-right informations_side">
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
