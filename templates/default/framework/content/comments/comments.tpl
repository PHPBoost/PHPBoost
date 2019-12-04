<script>
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
</script>

<section>
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
					<a href="{U_UNLOCK}" class="user-locked"><i class="fa fa-user-lock" aria-hidden="true"></i> {@unlock}</a>
				# ELSE #
					<a href="{U_LOCK}" class="user-unlocked"><i class="fa fa-user-lock" aria-hidden="true"></i> {@lock}</a>
				# ENDIF #
			</div>
		# ENDIF #

		# IF C_DISPLAY_FORM #
		<div class="spacer"></div>
		<form id="comments_list_form" action="{FORM_URL}" method="post" class="fieldset-content">
		# ENDIF #
			<div id="comments-list">
			# INCLUDE COMMENTS_LIST #
			</div>
		# IF C_DISPLAY_FORM #
			# IF C_DISPLAY_DELETE_BUTTON #
			<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {COMMENTS_NUMBER});" aria-label="{@select.all.comments}"><input type="hidden" name="token" value="{TOKEN}" /><button type="submit" name="delete-selected-comments" value="true" class="submit" data-confirmation="delete-elements">${LangLoader::get_message('delete', 'common')}</button>
			# ENDIF #
		</form>
		# ENDIF #
	</div>
	<footer></footer>
</section>

# IF C_DISPLAY_VIEW_ALL_COMMENTS #
	<div class="align-center">
		<button type="submit" class="submit" id="refresh-comments">{@allComments}</button>
	</div>
# ENDIF #
