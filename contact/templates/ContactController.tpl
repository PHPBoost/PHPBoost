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