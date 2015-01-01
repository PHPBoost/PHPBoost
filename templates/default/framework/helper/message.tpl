		<div id="msg-helper-{ID}" class="{MESSAGE_CSS_CLASS}">{MESSAGE_CONTENT}</div>
		# IF C_TIMEOUT #
		<script>
		<!--
			//Javascript timeout to hide this message
			setTimeout('jQuery("#msg-helper-{ID}").fadeOut();', {TIMEOUT});
		-->
		</script>
		# ENDIF #