# IF C_TIMEOUT #
<div id="msg-helper-{ID}" class="message-ephemeral-container">
	<div class="message-helper {MESSAGE_CSS_CLASS}">
		<a id="msg-helper-button-{ID}" class="{MESSAGE_CSS_CLASS}" title="${LangLoader::get_message('message.close_ephemeral_message', 'admin-common')}"><i class="fa fa-close-message"></i></a>
		{MESSAGE_CONTENT}
	</div>
</div>
<script>
<!--
	jQuery(document).ready(function(){
		//Show the message with animation
		jQuery("#msg-helper-{ID}").fadeTo("slow", 1);
		
		//Timeout to hide the message
		setTimeout('jQuery("#msg-helper-{ID}").fadeOut("slow");', {TIMEOUT});

		//Hide the message if you click on the close button
		$('#msg-helper-button-{ID}').click(function() {
		   jQuery('#msg-helper-{ID}').fadeTo('slow', 0);
		});
	});
-->
</script>
# ELSE #
<div id="msg-helper-{ID}" class="message-helper {MESSAGE_CSS_CLASS}">{MESSAGE_CONTENT}</div>
# ENDIF #