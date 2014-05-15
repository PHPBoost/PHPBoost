<script>
<!--
function refresh_comments()
{
	new Ajax.Updater('comments_list', PATH_TO_ROOT + '/kernel/framework/ajax/dispatcher.php?url=/comments/display/', {
		parameters: {module_id: ${escapejs(MODULE_ID)}, id_in_module: ${escapejs(ID_IN_MODULE)}, topic_identifier: ${escapejs(TOPIC_IDENTIFIER)}},
		insertion: Insertion.Bottom,
		onComplete: function() { 
			$('refresh_comments').remove() 
		}
	})
}

# IF C_DISPLAY_VIEW_ALL_COMMENTS #
Event.observe(window, 'load', function() {
	
	$('refresh_comments').observe('click', function() {
		refresh_comments();
	});
});
# ENDIF #

# IF C_REFRESH_ALL #
	refresh_comments();
# ENDIF #
//-->
</script>

<section id="comments_list">
	# IF C_DISPLAY_FORM #
		<div id="comment-form">
			# INCLUDE COMMENT_FORM #
		</div>
	# ENDIF #
	
	# INCLUDE KEEP_MESSAGE #
	
	# IF C_MODERATE #
		<div class="message-moderate">
			# IF C_IS_LOCKED #
			<a href="{U_UNLOCK}"><i class="fa fa-ban"></i> {@unlock}</a>
			# ELSE #
			<a href="{U_LOCK}"><i class="fa fa-unban"></i> {@lock}</a>
			# ENDIF #
		</div>
	# ENDIF #
	
	<div class="spacer"></div>
	
	# INCLUDE COMMENTS_LIST #
</section>

# IF C_DISPLAY_VIEW_ALL_COMMENTS #
<div style="text-align:center;">
	<button type="submit" class="submit" id="refresh_comments">{@allComments}</button>
</div>
# ENDIF #