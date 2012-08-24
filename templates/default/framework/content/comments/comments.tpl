<script type="text/javascript">
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

Event.observe(window, 'load', function() {
	# IF C_DISPLAY_VIEW_ALL_COMMENTS #
	$('refresh_comments').observe('click', function() {
		refresh_comments();
	});
	# ENDIF #
});

# IF C_REFRESH_ALL #
	refresh_comments();
# ENDIF #
//-->
</script>

<div id="comments_list">
# IF C_DISPLAY_FORM #
	<div id="comment_form">
		# INCLUDE COMMENT_FORM #
	</div>
# ENDIF #

# INCLUDE KEEP_MESSAGE #

# IF C_MODERATE #
	<div class="comment-moderate">
		# IF C_IS_LOCKED #
		<a href="{U_UNLOCK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/unlock.png"> {@unlock}</a>
		# ELSE #
		<a href="{U_LOCK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/lock.png"> {@lock}</a>
		# ENDIF #
	</div>
# ENDIF #

<div class="spacer">&nbsp;</div>

# INCLUDE COMMENTS_LIST #
</div>

# IF C_DISPLAY_VIEW_ALL_COMMENTS #
<div style="text-align:center;">
	<button type="submit" id="refresh_comments" class="submit">{@allComments}</button>
</div>
# ENDIF #