		<div id="id-message-helper" class="message-helper {MESSAGE_CSS_CLASS}">
			<i class="icon-{MESSAGE_CSS_CLASS}"></i>
			<div class="message-helper-content">{MESSAGE_CONTENT}</div>
		</div>	
		# IF C_TIMEOUT #
		<script type="text/javascript">
		<!--
			//Javascript timeout to hide this message
			setTimeout('Effect.Fade(id-message-helper);', {TIMEOUT});
		-->
		</script>
		# ENDIF #