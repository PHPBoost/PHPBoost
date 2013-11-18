		<div id="message_helper">
			<div class="{MESSAGE_CSS_CLASS}">
				<i class="icon-{MESSAGE_CSS_CLASS}"></i>
				<div>{MESSAGE_CONTENT}</div>
			</div>	
		</div>
		# IF C_TIMEOUT #
		<script type="text/javascript">
		<!--
			//Javascript timeout to hide this message
			setTimeout('Effect.Fade("message_helper");', {TIMEOUT});
		-->
		</script>
		# ENDIF #
