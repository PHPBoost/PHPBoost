# IF C_TIMEOUT #
<div id="msg-helper-{ID}" class="message-ephemeral-container {MESSAGE_CSS_CLASS}">
	<div class="message-helper {MESSAGE_CSS_CLASS}">{MESSAGE_CONTENT}</div>
</div>
<script>
<!--
	//Javascript timeout to hide this message
	setTimeout('jQuery("#msg-helper-{ID}").fadeOut();', {TIMEOUT});
-->
</script>
# ELSE #
<div id="msg-helper-{ID}" class="message-helper {MESSAGE_CSS_CLASS}">{MESSAGE_CONTENT}</div>
# ENDIF #