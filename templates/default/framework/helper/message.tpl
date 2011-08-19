		<div class="error_handler_position" id="message_helper">
			<div class="{MESSAGE_CSS_CLASS}" style="width:500px;margin:auto;padding:10px;text-indent:35px;line-height:22px;">
				{MESSAGE_CONTENT}
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
