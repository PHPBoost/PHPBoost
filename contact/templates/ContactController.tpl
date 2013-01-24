<div class="module_position">
	<div class="module_top_l"></div>
	<div class="module_top_r"></div>
	<div class="module_top">{@title_contact} </div>
	<div class="module_contents">
		# IF C_SUBMITED #
			# IF C_SUCCESS #
				<div class="success" id="mail_success">{L_SUCCESS_MAIL}</div>
				<script type="text/javascript">
				<!--
				window.setTimeout(function() { Effect.Fade("mail_success"); }, 5000);
				-->
				</script>
			# ELSE #
				<div class="error">{L_ERROR_MAIL}</div>
			# ENDIF #
		# ENDIF #
		
		# INCLUDE FORM #
	</div>
	<div class="module_bottom_l"></div>
	<div class="module_bottom_r"></div>
	<div class="module_bottom"></div>
</div>