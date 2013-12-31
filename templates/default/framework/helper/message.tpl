		<div id="msg-helper-{TOKEN}" class="message-helper {MESSAGE_CSS_CLASS}">
			<i class="fa fa-{MESSAGE_CSS_CLASS}"></i>
			<div class="message-helper-content">{MESSAGE_CONTENT}</div>
		</div>
		# IF C_TIMEOUT #
		<script type="text/javascript">
		<!--
			//Javascript timeout to hide this message
			setTimeout('Effect.Fade("msg-helper-{TOKEN}");', {TIMEOUT});
		-->
		</script>
		# ENDIF #