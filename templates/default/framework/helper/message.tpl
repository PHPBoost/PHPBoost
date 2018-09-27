# IF C_FLOATING #
<div id="message-helper-{ID}" class="message-helper {MESSAGE_CSS_CLASS}" style="display: none;">
	<a id="message-helper-button-{ID}" class="{MESSAGE_CSS_CLASS}" title="${LangLoader::get_message('message.close_ephemeral_message', 'status-messages-common')}"><i class="fa fa-close-message"></i></a>
	{MESSAGE_CONTENT}
</div>
<script>
<!--
	jQuery(document).ready(function(){
		var container = document.getElementsByClassName("floating-message-container");
		if (container.lenght == null)
			jQuery('<div class="floating-message-container"></div>').appendTo('body');

		jQuery( jQuery('#message-helper-{ID}') ).appendTo( jQuery('.floating-message-container') );

		jQuery("#message-helper-{ID}").fadeTo("fast", 1);
		
		# IF C_TIMEOUT #
		setTimeout('jQuery("#message-helper-{ID}").fadeOut("slow");', {TIMEOUT});
		# ENDIF #

		$('#message-helper-button-{ID}').click(function() {
			jQuery('#message-helper-{ID}').fadeTo('slow', 0);
		});
	});
-->
</script>
# ELSE #
<div id="message-helper-{ID}" class="message-helper {MESSAGE_CSS_CLASS}">{MESSAGE_CONTENT}</div>
# ENDIF #