		<div id="message-helper" class="message-helper-{MESSAGE_CSS_CLASS}">
			<div>{MESSAGE_CONTENT}</div>
		</div>	
		# IF C_TIMEOUT #
		<script type="text/javascript">
		<!--
			//Javascript timeout to hide this message
			setTimeout('Effect.Fade(message-helper);', {TIMEOUT});
		-->
		</script>
		# ENDIF #