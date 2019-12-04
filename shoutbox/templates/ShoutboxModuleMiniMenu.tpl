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

<div id="shoutbox-messages-container" class="cell-body cell-content# IF C_HORIZONTAL # shout-horizontal# ENDIF #"># INCLUDE SHOUTBOX_MESSAGES #</div>
# IF C_DISPLAY_FORM #
	<form class="cell-form" action="#" method="post">
		<div class="shout-form-container shout-pseudo-container">
			# IF NOT C_MEMBER #
				<label for="shout-pseudo"><span class="small">${LangLoader::get_message('form.name', 'common')}</span></label>
				<input maxlength="25" type="text" name="shout-pseudo" id="shout-pseudo" class="shout-pseudo not-connected" value="	${LangLoader::get_message('visitor', 'user-common')}">
			# ELSE #
				<input type="hidden" name="shout-pseudo" id="shout-pseudo" class="shout-pseudo connected" value="{SHOUTBOX_PSEUDO}">
			# ENDIF #
		</div>
		<div class="shout-form-container shout-contents-container">
			<label for="shout-contents"><span class="small">${LangLoader::get_message('message', 'main')}</span></label>
			<textarea id="shout-contents" name="shout-contents"# IF C_VALIDATE_ONKEYPRESS_ENTER # onkeypress="if(event.keyCode==13){shoutbox_add_message();}"# ENDIF # rows="2" cols="16"></textarea>
		</div>
		<nav id="shoutbox-bbcode-container" class="shout-spacing">
			# IF C_DISPLAY_SHOUT_BBCODE #
			<ul class="bbcode-container">
				<li class="bbcode-elements bbcode-block-shoutbox">
					<a href="javascript:bb_display_block('1', 'shout-contents');" onmouseover="bb_hide_block('1', 'shout-contents', 1);" onmouseout="bb_hide_block('1', 'shout-contents', 0);"> <i class="far fa-fw fa-smile" aria-hidden="true"></i><span class="sr-only">${LangLoader::get_message('bbcode.smileys', 'common', 'BBCode')}</span></a>
					<div class="bbcode-block-container" style="display: none;" id="bb-block1shout-contents">
						<ul class="bbcode-container shoutbox-smileys bkgd-main" onmouseover="bb_hide_block('1', 'shout-contents', 1);" onmouseout="bb_hide_block('1', 'shout-contents', 0);">
							# START smileys #
								<li>
									<a href="" onclick="insertbbcode('{smileys.CODE}', 'smile', 'shout-contents');return false;" class="bbcode-hover" aria-label="{smileys.CODE}"><img src="{smileys.URL}" alt="{smileys.CODE}" /></a>
								</li>
							# END smileys #
						</ul>
					</div>
				</li>
				<li class="bbcode-elements">
					<a href="" onclick="# IF NOT C_BOLD_DISABLED #insertbbcode('[b]', '[/b]', 'shout-contents');# ENDIF #return false;" aria-label="${LangLoader::get_message('bbcode.bold', 'common', 'BBCode')}"><i class="fa fa-fw fa-bold# IF C_BOLD_DISABLED # icon-disabled# ENDIF #" aria-hidden="true"></i></a>
				</li>
				<li class="bbcode-elements">
					<a href="" onclick="# IF NOT C_ITALIC_DISABLED #insertbbcode('[i]', '[/i]', 'shout-contents');# ENDIF #return false;" aria-label="${LangLoader::get_message('bbcode.italic', 'common', 'BBCode')}"><i class="fa fa-fw fa-italic# IF C_ITALIC_DISABLED # icon-disabled# ENDIF #" aria-hidden="true"></i></a>
				</li>
				<li class="bbcode-elements">
					<a href="" onclick="# IF NOT C_UNDERLINE_DISABLED #insertbbcode('[u]', '[/u]', 'shout-contents');# ENDIF #return false;" aria-label="${LangLoader::get_message('bbcode.underline', 'common', 'BBCode')}"><i class="fa fa-fw fa-underline# IF C_UNDERLINE_DISABLED # icon-disabled# ENDIF #" aria-hidden="true"></i></a>
				</li>
				<li class="bbcode-elements">
					<a href="" onclick="# IF NOT C_STRIKE_DISABLED #insertbbcode('[s]', '[/s]', 'shout-contents');# ENDIF #return false;" aria-label="${LangLoader::get_message('bbcode.strike', 'common', 'BBCode')}"><i class="fa fa-fw fa-strikethrough# IF C_STRIKE_DISABLED # icon-disabled# ENDIF #" aria-hidden="true"></i></a>
				</li>
			</ul>
			# ENDIF #
		</nav>
		<div class="shout-spacing small">
			<div class="grouped-inputs">
				<button class="grouped-element" onclick="shoutbox_add_message();" type="button">${LangLoader::get_message('submit', 'main')}</button>
				<input type="hidden" name="token" value="{TOKEN}">
				<a class="grouped-element" href="" onclick="shoutbox_refresh_messages_box();return false;" id="shoutbox-refresh" aria-label="${LangLoader::get_message('refresh', 'main')}"><i class="fa fa-sync" aria-hidden="true"></i></a>
			</div>
		</div>
	</form>
# ELSE #
	# IF C_DISPLAY_NO_WRITE_AUTHORIZATION_MESSAGE #
		<span class="message-helper bgc warning">{@error.post.unauthorized}</span>
		<p class="shout-spacing">
			<a href="" onclick="shoutbox_refresh_messages_box();return false;" id="shoutbox-refresh" aria-label="${LangLoader::get_message('refresh', 'main')}"><i class="fa fa-sync" aria-hidden="true"></i></a>
		</p>
	# ENDIF #
# ENDIF #
<div class="cell-body">
	<div class="cell-content align-center"><a class="button small" href="${relative_url(ShoutboxUrlBuilder::home())}">{@archives}</a></div>
</div>
