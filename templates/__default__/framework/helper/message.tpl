# IF C_FLOATING #
	<div id="message-helper-{ID}" class="floating-element" style="display: none;">
		<div class="message-helper bgc {MESSAGE_CSS_CLASS}">
			# IF NOT C_TIMEOUT #
				<a id="message-helper-button-{ID}" class="bgc-full {MESSAGE_CSS_CLASS} close-message" aria-label="${LangLoader::get_message('common.close', 'common-lang')}">
					<i class="fa fa-times" aria-hidden="true"></i>
				</a>
			# ENDIF #
			{MESSAGE_CONTENT}
		</div>
	</div>
	<script>
		/**
		 *  hide the message-helper named by {element}.
		 *  @param {jQuery object} element
		 */
		function hide_message_helper(element)
		{
			$(element).fadeTo('slow', 0);
			$(element).fadeOut('fast', 0);
			$(element).addClass('hide-message');
			hide_floating_message_container();
		}

		/**
		 *  Check if the floating message-helper container can be hidden.
		 *  @param none
		 */
		function hide_floating_message_container()
		{
			var elements = $(".floating-message-container").children();
			var last = true;

			for (var i=0; i < elements.length; i++)
			{
				if ($(elements[i]).hasClass('hide-message') == false)
					last = false;
			}

			if (last == true)
				$(".floating-message-container").removeClass("active-message");
		}

		/**
		 *  Display the message-helper into the floating message container.
		 *  @param none
		 */
		$(function(){
			if ($(".floating-message-container").length == 0)
				$('<div class="floating-message-container"></div>').appendTo('body');

			$(".floating-message-container").addClass("active-message");

			$( $("#message-helper-{ID}") ).appendTo( $(".floating-message-container") );
			$("#message-helper-{ID}").fadeTo("fast", 1);

			# IF C_TIMEOUT #
				setTimeout('hide_message_helper("#message-helper-{ID}");', {TIMEOUT});
			# ENDIF #

			$('#message-helper-button-{ID}').on('click',function() { hide_message_helper($(this).closest('.floating-element')); });
		});
	</script>
# ELSE #
	<div id="message-helper-{ID}" class="message-helper bgc {MESSAGE_CSS_CLASS}">{MESSAGE_CONTENT}</div>
# ENDIF #
