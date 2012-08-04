
<script type="text/javascript">
<!--
	Event.observe(window, 'load', function() {
		# IF C_DISPLAY_VIEW_ALL_COMMENTS #
		$('refresh_comments').observe('click', function() {
			new Ajax.Updater('comments_list', PATH_TO_ROOT + '/kernel/framework/ajax/dispatcher.php?url=/comments/display/', {
				parameters: {module_id: ${escapejs(MODULE_ID)}, id_in_module: ${escapejs(ID_IN_MODULE)}, topic_identifier: ${escapejs(TOPIC_IDENTIFIER)})},
				insertion: Insertion.Bottom,
				onComplete: function() { 
					$('refresh_comments').remove() 
				}
			})
		});
		# ENDIF #

		var is_locked = true;
		
		$('comments_locked').observe('click', function() {
			new Ajax.Request(PATH_TO_ROOT + '/kernel/framework/ajax/dispatcher.php?url=/comments/lock/', {
				  method: 'post',
				  parameters: {locked: is_locked, module_id: ${escapejs(MODULE_ID)}, id_in_module: ${escapejs(ID_IN_MODULE)}, topic_identifier: ${escapejs(TOPIC_IDENTIFIER)}},
				  onComplete: function(response) {				  
					  if (response.responseJSON.success) {
						if (response.responseJSON.locked) {
							$('comments_locked').src = PATH_TO_ROOT + '/templates/' + THEME + '/images/' + LANG + '/lock.png';
							is_locked = true;
						}
						else {
							$('comments_locked').src = PATH_TO_ROOT + '/templates/' + THEME + '/images/' + LANG + '/unlock.png';
							is_locked = false;
						}
					  }
					  alert(response.responseJSON.message);
				  }
			});
		});
	});
//-->
</script>


<style>
<!--
img#comments_locked {
	cursor:pointer;
}
-->
</style>

# IF C_DISPLAY_FORM #
	<div id="comment_form">
		# INCLUDE COMMENT_FORM #
	</div>
# ENDIF #

# INCLUDE KEEP_MESSAGE #

# IF C_IS_LOCKED #
<img id="comments_locked" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/unlock.png">
# ELSE #
<img id="comments_locked" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/lock.png">
# ENDIF #

<div id="comments_list">
# INCLUDE COMMENTS_LIST #
</div>

# IF C_DISPLAY_VIEW_ALL_COMMENTS #
<div style="text-align:center;">
	<button type="submit" id="refresh_comments" class="submit">Voir les autres commentaires</button>
</div>
# ENDIF #