<script>
	# IF C_AUTOMATIC_REFRESH_ENABLED #refreshInterval = setInterval(shoutbox_refresh_messages_box, {SHOUT_REFRESH_DELAY});# ENDIF #

	function shoutbox_add_message()
	{
		var pseudo = jQuery("#shout-pseudo").val();
		var content = jQuery("#shout-content").val();

		if (pseudo && content)
		{
			jQuery.ajax({
				url: '${relative_url(ShoutboxUrlBuilder::ajax_add())}',
				type: "post",
				dataType: "json",
				data: {'pseudo' : pseudo, 'content' : content, 'token' : '{TOKEN}'},
				beforeSend: function(){
					jQuery('#shoutbox-refresh').html('<i class="fa fa-spin fa-spinner"></i>');
				},
				success: function(returnData){
					if(returnData.code > 0) {
						shoutbox_refresh_messages_box();
						jQuery('#shout-content').val('');
					} else {
						switch(returnData.code)
						{
							case -1:
								alert(${escapejs(@warning.flood)});
							break;
							case -2:
								alert(${escapejs(L_ALERT_LINK_FLOOD)});
							break;
							case -3:
								alert(${escapejs(@warning.incomplete)});
							break;
							case -4:
								alert(${escapejs(@warning.auth)});
							break;
						}
					}
					jQuery('#shoutbox-refresh').html('<i class="fa fa-sync"></i>');
				},
				error: function(e){
					alert(${escapejs(@warning.csrf.invalid.token)});
				}
			});
		} else {
			alert(${escapejs(@warning.text)});
			return false;
		}
	}

	function shoutbox_delete_message(id_message)
	{
		if (confirm(${escapejs(@warning.confirm.delete)}))
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
						alert(${escapejs(@shoutbox.warning.delete.message)});
					}
					jQuery('#shoutbox-refresh').html('<i class="fa fa-sync"></i>');
				},
				error: function(e){
					alert(${escapejs(@warning.csrf.invalid.token)});
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
# IF C_DISPLAY_SHOUT_BBCODE #
	<script src="{PATH_TO_ROOT}/BBCode/templates/js/bbcode# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
# ENDIF #

<div class="cell-body# IF C_HORIZONTAL # shout-horizontal# ENDIF #">
	<div id="shoutbox-messages-container" class="cell-content">
		# INCLUDE SHOUTBOX_MESSAGES #
	</div>
</div>
# IF C_DISPLAY_FORM #
	<form action="#" method="post" class="cell-form">
		# IF NOT C_MEMBER #
			<label for="shout-pseudo" class="cell-form grouped-inputs">
				<span class="grouped-element">{@common.name}</span>
				<input type="text" name="shout-pseudo" id="shout-pseudo" class="grouped-element shout-pseudo not-connected" value="{@user.guest}">
			</label>
		# ELSE #
			<input type="hidden" name="shout-pseudo" id="shout-pseudo" class="shout-pseudo connected" value="{SHOUTBOX_PSEUDO}">
		# ENDIF #
		<div class="cell-textarea">
			<label for="shout-content"><span class="sr-only">{@common.message}</span></label>
			<textarea id="shout-content" name="shout-content"# IF C_VALIDATE_ONKEYPRESS_ENTER # onkeypress="if(event.keyCode==13){shoutbox_add_message();}"# ENDIF # rows="3" cols="16" placeholder="{@common.message}..."></textarea>
		</div>
		<nav class="cell-body" id="shoutbox-bbcode-container">
			# IF C_DISPLAY_SHOUT_BBCODE #
				<ul class="bbcode-container modal-container cell-list cell-inline-list cell-modal cell-tile">
					<li class="bbcode-elements bbcode-block-shoutbox">
						<span class="bbcode-button# IF C_SMILEYS_DISABLED # icon-disabled# ENDIF #" # IF NOT C_SMILEYS_DISABLED #data-modal# ENDIF # data-target="bb-shoutbox-smileys" aria-label="{@bbcode.smileys}">
							<i class="far fa-fw fa-smile" aria-hidden="true"></i>
						</span>
						<div id="bb-shoutbox-smileys" class="modal modal-animation">
							<div class="close-modal" aria-label="{@common.close}"></div>
							<div class="content-panel cell">
								<div class="cell-header">
									{@bbcode.smileys}
								</div>
								<div class="cell-list cell-list-inline">
									<ul>
										# START smileys #
											<li>
												<span class="hide-modal" onclick="insertbbcode('{smileys.CODE}', 'smile', 'shout-content');return false;" aria-label="{smileys.CODE}">
													<img src="{smileys.URL}" alt="{smileys.CODE}" />
												</span>
											</li>
										# END smileys #
									</ul>
								</div>
							</div>
						</div>
					</li>
					<li id="emojis" class="bbcode-elements bbcode-block-shoutbox">
						<span class="bbcode-button# IF C_EMOJIS_DISABLED # icon-disabled# ENDIF #" # IF NOT C_EMOJIS_DISABLED #data-modal# ENDIF # data-target="block-emojis" role="button" aria-label="{@bbcode.emojis}">
							<span class="stacked">
								<i class="far fa-fw fa-smile" aria-hidden="true"></i>
								<span class="stack-event stack-top-right small">
									<i class="fa fa-fw fa-code" aria-hidden="true"></i>
								</span>
							</span>
						</span>
						<div id="block-emojis{FIELD}" class="modal modal-animation">
							<div class="close-modal" role="button" aria-label="{@common.close}"></div>
							<div class="content-panel cell">
								<div class="cell-header">
									<div class="cell-name">{@bbcode.emojis}</div>
								</div>
								<div class="cell-content align-center">
									{@H|bbcode.emojis.link}
								</div>
								<div class="cell-list cell-list-inline cell-overflow-y">
									<ul class="flex-start">
										# START emojis #
											# IF emojis.C_CATEGORY #
												</ul>
												<ul class="flex-start">
													<li> <h5>{emojis.CATEGORY_NAME}</h5> </li>
												</ul>
												<ul class="flex-start">
											# ELSE #
												# IF emojis.C_SUB_CATEGORY #
													</ul>
													<ul class="flex-start">
														<li> <h6>{emojis.CATEGORY_NAME}</h6> </li>
													</ul>
													<ul class="flex-start">
												# ELSE #
													<li# IF emojis.C_END_LINE # class="hidden"# ENDIF #>
														<span class="hide-modal bigger emoji-tag" onclick="insertbbcode('[emoji]{emojis.DECIMAL}[/emoji]', '', 'shout-content');" role="button"# IF emojis.C_NAME # aria-label="{emojis.NAME}"# ENDIF #>
															{emojis.DECIMAL}
														</span>
													</li>
												# ENDIF #
											# ENDIF #
										# END emojis #
									</ul>
								</div>
							</div>
						</div>
					</li>
					<li class="bbcode-elements">
						<a class="bbcode-button" href="#" onclick="# IF NOT C_BOLD_DISABLED #insertbbcode('[b]', '[/b]', 'shout-content');# ENDIF #return false;" aria-label="{@bbcode.bold}"><i class="fa fa-fw fa-bold# IF C_BOLD_DISABLED # icon-disabled# ENDIF #" aria-hidden="true"></i></a>
					</li>
					<li class="bbcode-elements">
						<a class="bbcode-button" href="#" onclick="# IF NOT C_ITALIC_DISABLED #insertbbcode('[i]', '[/i]', 'shout-content');# ENDIF #return false;" aria-label="{@bbcode.italic}"><i class="fa fa-fw fa-italic# IF C_ITALIC_DISABLED # icon-disabled# ENDIF #" aria-hidden="true"></i></a>
					</li>
					<li class="bbcode-elements">
						<a class="bbcode-button" href="#" onclick="# IF NOT C_UNDERLINE_DISABLED #insertbbcode('[u]', '[/u]', 'shout-content');# ENDIF #return false;" aria-label="{@bbcode.underline}"><i class="fa fa-fw fa-underline# IF C_UNDERLINE_DISABLED # icon-disabled# ENDIF #" aria-hidden="true"></i></a>
					</li>
					<li class="bbcode-elements">
						<a class="bbcode-button" href="#" onclick="# IF NOT C_STRIKE_DISABLED #insertbbcode('[s]', '[/s]', 'shout-content');# ENDIF #return false;" aria-label="{@bbcode.strike}"><i class="fa fa-fw fa-strikethrough# IF C_STRIKE_DISABLED # icon-disabled# ENDIF #" aria-hidden="true"></i></a>
					</li>
				</ul>
			# ENDIF #
		</nav>
		<div class="cell-form grouped-inputs">
			<button class="button submit flex-wide grouped-element" onclick="shoutbox_add_message();" type="button">{@form.submit}</button>
			<input type="hidden" name="token" value="{TOKEN}">
			<a class="grouped-element" href="#" onclick="shoutbox_refresh_messages_box();return false;" id="shoutbox-refresh" aria-label="{@shoutbox.refresh}"><i class="fa fa-sync" aria-hidden="true"></i></a>
		</div>
	</form>
# ELSE #
	# IF C_DISPLAY_NO_WRITE_AUTHORIZATION_MESSAGE #
		<div class="cell-alert">
			<div class="message-helper bgc warning">{@shoutbox.message.unauthorized}</div>
		</div>
	# ENDIF #
# ENDIF #
<div class="cell-footer">
	<div class="align-center"><a class="button small offload" href="${relative_url(ShoutboxUrlBuilder::home())}">{@shoutbox.archives}</a></div>
</div>
