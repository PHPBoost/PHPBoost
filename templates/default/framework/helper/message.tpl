		<div id="msg-helper-{ID}" class="{MESSAGE_CSS_CLASS}">{MESSAGE_CONTENT}</div>
		# IF C_TIMEOUT #
		<script>
		<!--
			//Javascript timeout to hide this message
			setTimeout('Effect.Fade("msg-helper-{ID}");', {TIMEOUT});
		-->
		</script>
		# ENDIF #