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

# IF C_DISPLAY_FORM #
	<div id="comment_form">
		# INCLUDE COMMENT_FORM #
	</div>
# ENDIF #

# INCLUDE KEEP_MESSAGE #

<div id="comments_list">
# INCLUDE COMMENTS_LIST #
</div>

</br>

# IF C_DISPLAY_VIEW_ALL_COMMENTS #
<div style="text-align:center;">
	<button type="submit" id="refresh_comments" class="submit">Voir les autres commentaires</button>
</div>
# ENDIF #