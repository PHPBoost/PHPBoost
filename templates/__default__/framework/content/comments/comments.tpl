<script>
	function refresh_comments() {
		jQuery.ajax({
			url: PATH_TO_ROOT + '/kernel/framework/ajax/dispatcher.php?url=/comments/display/',
			type: "post",
			dataType: "html",
			data: {module_id: ${escapejs(MODULE_ID)}, id_in_module: ${escapejs(ID_IN_MODULE)}, topic_identifier: ${escapejs(TOPIC_IDENTIFIER)}, token: ${escapejs(TOKEN)}},
			success: function(returnData){
				jQuery("#comments-section").append(returnData);
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

<hr />
# INCLUDE KEEP_MESSAGE #
<div id="comments-list" class="tabs-container">
    <!-- # IF C_DISPLAY_FORM #
		<nav class="tabs-nav">
			<ul class="flex-between flex-between-large">
				<li><a href="#" data-tabs="" data-target="comments-section"><h5>{@comment.comments}</h5></a></li>
				# IF NOT C_IS_LOCKED #<li><a class="pinned question" href="#" data-tabs="" data-target="add-comment">{@comment.add}</a></li># ENDIF #
			</ul>
		</nav>
	# ENDIF # -->
	# IF NOT C_IS_LOCKED #
		# IF C_DISPLAY_FORM #
			<div id="add-comment"> <!--  class="tabs tabs-animation" -->
				<div class="content-panel">
						<div id="comment-form">
							# INCLUDE COMMENT_FORM #
						</div>
				</div>
			</div>
		# ENDIF #
	# ENDIF #
    <div id="comments-section"> <!-- class="first-tab tabs tabs-animation" -->
        <div class="content-panel">
			# IF C_COMMENTS #
				# IF C_DISPLAY_DELETE_FORM #
					<form action="{FORM_URL}" method="post" class="fieldset-content">
				# ENDIF #
					# IF C_MODERATE #
						<div class="controls align-right">
							# IF C_IS_LOCKED #
								<a href="{U_UNLOCK}" class="user-locked offload"><i class="fa fa-user-lock" aria-hidden="true"></i> {@comment.unlock}</a>
							# ELSE #
								<a href="{U_LOCK}" class="user-unlocked offload"><i class="fa fa-user-lock" aria-hidden="true"></i> {@comment.lock}</a>
							# ENDIF #
						</div>
					# ENDIF #

					# INCLUDE COMMENTS_LIST #

				# IF C_DISPLAY_DELETE_FORM #
						# IF C_DISPLAY_DELETE_BUTTON #
							<label for="delete-all-checkbox" class="checkbox" aria-label="{@common.select.all.elements}">
								<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {COMMENTS_NUMBER});">
								<span>&nbsp;</span>
							</label>
							<input type="hidden" name="token" value="{TOKEN}" />
							<button type="submit" id="delete-all-button" name="delete-selected-comments" value="true" class="button submit" data-confirmation="delete-element" disabled="disabled">{@common.delete}</button>
						# ENDIF #
					</form>
				# ENDIF #
			# ELSE #
				<div class="message-helper bgc notice">{@comment.no.comment}</div>
			# ENDIF #
		</div>
    </div>
</div>

# IF C_DISPLAY_VIEW_ALL_COMMENTS #
	<div class="align-center">
		<button type="submit" class="button submit" id="refresh-comments">{@comment.see.all.comments}</button>
	</div>
# ENDIF #
