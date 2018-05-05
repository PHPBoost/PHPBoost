<script>
<!--
function refresh_comments() {
	jQuery.ajax({
		url: PATH_TO_ROOT + '/kernel/framework/ajax/dispatcher.php?url=/comments/display/',
		type: "post",
		dataType: "html",
		data: {module_id: ${escapejs(MODULE_ID)}, id_in_module: ${escapejs(ID_IN_MODULE)}, topic_identifier: ${escapejs(TOPIC_IDENTIFIER)}, token: ${escapejs(TOKEN)}},
		success: function(returnData){
			jQuery("#comments-list").append(returnData);
			jQuery('#refresh-comments').remove();
		}
	});
}

# IF C_DISPLAY_VIEW_ALL_COMMENTS #
jQuery(document).ready(function(){ 
	jQuery("#refresh-comments").click(function() {
		refresh_comments();
	});
});
# ENDIF #

-->
</script>

<section id="comments-list">
	<header>
		<h2>${LangLoader::get_message('comments', 'comments-common')}</h2>
	</header>
	<div class="content">
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
	</div>
	<footer></footer>
</section>

# IF C_DISPLAY_VIEW_ALL_COMMENTS #
<div class="center">
	<button type="submit" class="submit" id="refresh-comments">{@allComments}</button>
</div>
# ENDIF #
