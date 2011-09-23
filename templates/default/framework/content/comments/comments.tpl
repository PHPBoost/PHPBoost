<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/CommentsService.js"></script>
<script type="text/javascript">
<!--
	var CommentsService = new CommentsService();
	Event.observe(window, 'load', function() {
		Event.observe($('refresh_comments'), 'click', function() {
			CommentsService.refresh_comments_list(${escapejs(MODULE_ID)}, ${escapejs(ID_IN_MODULE)});
		});
	});
//-->
</script>
# INCLUDE KEEP_MESSAGE #

# IF C_DISPLAY_FORM #
	<div id="comment_form">
		# INCLUDE COMMENT_FORM #
	</div>
# ENDIF #

<div id="comments_list">
# INCLUDE COMMENTS_LIST #
</div>
<button type="submit" id="refresh_comments" class="submit">Voir les autres commentaires</button>