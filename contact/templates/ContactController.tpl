<div class="module_position">
	<div class="module_top_l"></div>
	<div class="module_top_r"></div>
	<div class="module_top">{@title_contact} </div>
	<div class="module_contents">
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
	<div class="module_bottom_l"></div>
	<div class="module_bottom_r"></div>
	<div class="module_bottom"></div>
</div>