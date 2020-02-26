<script>
	# IF C_AUTOMATIC_REFRESH_ENABLED #refreshInterval = setInterval(shoutbox_refresh_messages_box, {SHOUT_REFRESH_DELAY});# ENDIF #

	function shoutbox_add_message()
	{
		var pseudo = jQuery("#shout-pseudo").val();
		var contents = jQuery("#shout-contents").val();

		if (pseudo && contents)
		{
			jQuery.ajax({
				url: '${relative_url(ShoutboxUrlBuilder::ajax_add())}',
				type: "post",
				dataType: "json",
				data: {'pseudo' : pseudo, 'contents' : contents, 'token' : '{TOKEN}'},
				beforeSend: function(){
					jQuery('#shoutbox-refresh').html('<i class="fa fa-spin fa-spinner"></i>');
				},
				success: function(returnData){
					if(returnData.code > 0) {
						shoutbox_refresh_messages_box();
						jQuery('#shout-contents').val('');
					} else {
						switch(returnData.code)
						{
							case -1:
								alert(${escapejs(LangLoader::get_message('e_flood', 'errors'))});
							break;
							case -2:
								alert("{L_ALERT_LINK_FLOOD}");
							break;
							case -3:
								alert(${escapejs(LangLoader::get_message('e_incomplete', 'errors'))});
							break;
							case -4:
								alert(${escapejs(LangLoader::get_message('error.auth', 'status-messages-common'))});
							break;
						}
					}
					jQuery('#shoutbox-refresh').html('<i class="fa fa-sync"></i>');
				},
				error: function(e){
					alert(${escapejs(LangLoader::get_message('csrf_invalid_token', 'status-messages-common'))});
				}
			});
		} else {
			alert("${LangLoader::get_message('require_text', 'main')}");
			return false;
		}
	}

	function shoutbox_delete_message(id_message)
	{
		if (confirm(${escapejs(LangLoader::get_message('confirm.delete', 'status-messages-common'))}))
		{
			jQuery.ajax({
				url: '${relative_url(ShoutboxUrlBuilder::ajax_delete())}',
				type: "post",
				dataType: "json",
				data: {'id' : id_message, 'token' : '{TOKEN}'},
				beforeSend: function(){
					jQuery('#shoutbox-refresh').html('<i class="fa fa-spin fa-spinner"></i>');
				},
				success: function(returnData){
					var code = returnData.code;

					if(code > 0) {
						jQuery('#shoutbox-message-' + code).remove();
					} else {
						alert(${escapejs(LangLoader::get_message('error.message.delete', 'common', 'shoutbox'))});
					}
					jQuery('#shoutbox-refresh').html('<i class="fa fa-sync"></i>');
				},
				error: function(e){
					alert(${escapejs(LangLoader::get_message('csrf_invalid_token', 'status-messages-common'))});
				}
			});
		}
	}

	function shoutbox_refresh_messages_box() {
		jQuery.ajax({
			url: '${relative_url(ShoutboxUrlBuilder::ajax_refresh())}',
			type: "post",
			dataType: "json",
			data: {'token' : '{TOKEN}'},
			beforeSend: function(){
				jQuery('#shoutbox-refresh').html('<i class="fa fa-spin fa-spinner"></i>');
			},
			success: function(returnData){
				jQuery('#shoutbox-messages-container').html(returnData.messages);
				jQuery('#shoutbox-refresh').html('<i class="fa fa-sync"></i>');
			},
			error: function(e){
				# IF C_AUTOMATIC_REFRESH_ENABLED #clearInterval(refreshInterval);# ENDIF #
				jQuery('#shoutbox-refresh').html('<i class="fa fa-sync"></i>');
			}
		});
	}
</script>
# IF C_DISPLAY_SHOUT_BBCODE #<script src="{PATH_TO_ROOT}/BBCode/templates/js/bbcode.js"></script># ENDIF #

<div class="cell-body# IF C_HORIZONTAL # shout-horizontal# ENDIF #">
	<div id="shoutbox-messages-container" class="cell-content">
		# INCLUDE SHOUTBOX_MESSAGES #
	</div>
</div>
# IF C_DISPLAY_FORM #
	<form action="#" method="post">
		# IF NOT C_MEMBER #
			<label for="shout-pseudo" class="cell-form grouped-inputs">
				<span class="grouped-element">${LangLoader::get_message('form.name', 'common')}</span>
				<input type="text" name="shout-pseudo" id="shout-pseudo" class="grouped-element shout-pseudo not-connected" value="${LangLoader::get_message('visitor', 'user-common')}">
			</label>
		# ELSE #
			<input type="hidden" name="shout-pseudo" id="shout-pseudo" class="shout-pseudo connected" value="{SHOUTBOX_PSEUDO}">
		# ENDIF #
		<div class="cell-textarea">
			<label for="shout-contents"><span class="sr-only">${LangLoader::get_message('message', 'main')}</span></label>
			<textarea id="shout-contents" name="shout-contents"# IF C_VALIDATE_ONKEYPRESS_ENTER # onkeypress="if(event.keyCode==13){shoutbox_add_message();}"# ENDIF # rows="3" cols="16" placeholder="${LangLoader::get_message('message', 'main')}..."></textarea>
		</div>
		<nav class="cell-body" id="shoutbox-bbcode-container">
			# IF C_DISPLAY_SHOUT_BBCODE #
			<ul class="bbcode-container modal-container cell-flex cell-modal cell-tile">
				<li class="bbcode-elements bbcode-block-shoutbox">
					<span class="bbcode-button" data-modal data-target="bb-shoutbox-smileys" aria-label="${LangLoader::get_message('bbcode.smileys', 'common', 'BBCode')}">
						<i class="far fa-fw fa-smile" aria-hidden="true"></i>
					</span>
					<div id="bb-shoutbox-smileys" class="modal modal-animation">
						<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
						<div class="content-panel cell">
							<div class="cell-header">
								${LangLoader::get_message('bbcode.smileys', 'common', 'BBCode')}
							</div>
							<div class="cell-list cell-list-inline">
								<ul>
									# START smileys #
										<li>
											<span class="hide-modal" onclick="insertbbcode('{smileys.CODE}', 'smile', 'shout-contents');return false;" aria-label="{smileys.CODE}">
												<img src="{smileys.URL}" alt="{smileys.CODE}" />
											</span>
										</li>
									# END smileys #
								</ul>
							</div>
						</div>
					</div>
				</li>
				<li class="bbcode-elements">
					<a class="bbcode-button" href="#" onclick="# IF NOT C_BOLD_DISABLED #insertbbcode('[b]', '[/b]', 'shout-contents');# ENDIF #return false;" aria-label="${LangLoader::get_message('bbcode.bold', 'common', 'BBCode')}"><i class="fa fa-fw fa-bold# IF C_BOLD_DISABLED # icon-disabled# ENDIF #" aria-hidden="true"></i></a>
				</li>
				<li class="bbcode-elements">
					<a class="bbcode-button" href="#" onclick="# IF NOT C_ITALIC_DISABLED #insertbbcode('[i]', '[/i]', 'shout-contents');# ENDIF #return false;" aria-label="${LangLoader::get_message('bbcode.italic', 'common', 'BBCode')}"><i class="fa fa-fw fa-italic# IF C_ITALIC_DISABLED # icon-disabled# ENDIF #" aria-hidden="true"></i></a>
				</li>
				<li class="bbcode-elements">
					<a class="bbcode-button" href="#" onclick="# IF NOT C_UNDERLINE_DISABLED #insertbbcode('[u]', '[/u]', 'shout-contents');# ENDIF #return false;" aria-label="${LangLoader::get_message('bbcode.underline', 'common', 'BBCode')}"><i class="fa fa-fw fa-underline# IF C_UNDERLINE_DISABLED # icon-disabled# ENDIF #" aria-hidden="true"></i></a>
				</li>
				<li class="bbcode-elements">
					<a class="bbcode-button" href="#" onclick="# IF NOT C_STRIKE_DISABLED #insertbbcode('[s]', '[/s]', 'shout-contents');# ENDIF #return false;" aria-label="${LangLoader::get_message('bbcode.strike', 'common', 'BBCode')}"><i class="fa fa-fw fa-strikethrough# IF C_STRIKE_DISABLED # icon-disabled# ENDIF #" aria-hidden="true"></i></a>
				</li>
			</ul>
			# ENDIF #
		</nav>
		<div class="cell-form grouped-inputs">
			<button class="button submit flex-wide" onclick="shoutbox_add_message();" type="button">${LangLoader::get_message('submit', 'main')}</button>
			<input type="hidden" name="token" value="{TOKEN}">
			<a class="grouped-element" href="#" onclick="shoutbox_refresh_messages_box();return false;" id="shoutbox-refresh" aria-label="${LangLoader::get_message('refresh', 'main')}"><i class="fa fa-sync" aria-hidden="true"></i></a>
		</div>
	</form>
# ELSE #
	# IF C_DISPLAY_NO_WRITE_AUTHORIZATION_MESSAGE #
		<div class="cell-alert">
			<div class="message-helper bgc warning">{@error.post.unauthorized}</div>
		</div>
	# ENDIF #
# ENDIF #
<div class="cell-footer">
	<div class="align-center"><a class="button small" href="${relative_url(ShoutboxUrlBuilder::home())}">{@archives}</a></div>
</div>
