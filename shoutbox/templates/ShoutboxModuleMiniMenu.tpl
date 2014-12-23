<script>
<!--
function shoutbox_add_message()
{
	var pseudo = $("shout_pseudo").value;
	var contents = $("shout_contents").value;
	
	if (pseudo && contents)
	{
		new Ajax.Request('${relative_url(ShoutboxUrlBuilder::ajax_add())}', {
			method:'post',
			parameters: {'pseudo' : pseudo, 'contents' : contents, 'token' : '{TOKEN}'},
			onLoading: function () {
				$('shoutbox-refresh').className = 'fa fa-spinner fa-spin';
			},
			onComplete: function(response) {
				if(response.readyState == 4 && response.status == 200 && response.responseJSON.code > 0) {
					shoutbox_refresh_messages_box();
					$('shout_contents').value = '';
				} else {
					switch(response.responseJSON.code)
					{
						case -1: 
							alert("${LangLoader::get_message('e_flood', 'errors')}");
						break;
						case -2: 
							alert("{L_ALERT_LINK_FLOOD}");
						break;
						case -3: 
							alert("${LangLoader::get_message('e_incomplete', 'errors')}");
						break;
						case -4: 
							alert("${LangLoader::get_message('error.auth', 'status-messages-common')}");
						break;
					}
				}
				$('shoutbox-refresh').className = 'fa fa-refresh';
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
		new Ajax.Request('${relative_url(ShoutboxUrlBuilder::ajax_delete())}', {
			method:'post',
			parameters: {'id' : id_message, 'token' : '{TOKEN}'},
			onLoading: function () {
				$('shoutbox-refresh').className = 'fa fa-spinner fa-spin';
			},
			onComplete: function(response) {
				if(response.readyState == 4 && response.status == 200 && response.responseJSON.code > 0) {
					var elementToDelete = $('shoutbox-message-' + response.responseJSON.code);
					elementToDelete.parentNode.removeChild(elementToDelete);
				} else {
					alert("{@error.message.delete}");
				}
				$('shoutbox-refresh').className = 'fa fa-refresh';
			}
		});
	}
}

function shoutbox_refresh_messages_box() {
	new Ajax.Updater(
		'shoutbox-messages-container',
		'${relative_url(ShoutboxUrlBuilder::ajax_refresh())}',
		{
			onLoading: function () {
				$('shoutbox-refresh').className = 'fa fa-spinner fa-spin';
			},
			onComplete: function(response) {
				$('shoutbox-refresh').className = 'fa fa-refresh';
			}
		}
	);
}

# IF C_AUTOMATIC_REFRESH_ENABLED #setInterval(shoutbox_refresh_messages_box, {SHOUT_REFRESH_DELAY});# ENDIF #
-->
</script>
<script src="{PATH_TO_ROOT}/BBCode/templates/js/bbcode.js"></script>

<div class="module-mini-container"# IF C_HORIZONTAL # style="width:auto;"# ENDIF #>
	<div class="module-mini-top">
		<h5 class="sub-title">{@module_title}</h5>
	</div>
	<div class="module-mini-contents">
		# IF C_HORIZONTAL #<div class="shout-horizontal">
			<div id="shoutbox-messages-container"># INCLUDE SHOUTBOX_MESSAGES #</div>
		</div>
		# ELSE #
		<div id="shoutbox-messages-container"># INCLUDE SHOUTBOX_MESSAGES #</div>
		# ENDIF #
		# IF C_DISPLAY_FORM #
		<form action="?token={TOKEN}" method="post">
			# IF NOT C_MEMBER #
			<div class="spacer">&nbsp;</div>
			<label for="shout_pseudo"><span class="small">${LangLoader::get_message('field.name', 'admin-user-common')}</span></label>
			<input size="16" maxlength="25" type="text" name="shout_pseudo" id="shout_pseudo" value="${LangLoader::get_message('guest', 'main')}">
			# ELSE #
			<input size="16" maxlength="25" type="hidden" name="shout_pseudo" id="shout_pseudo" value="{SHOUTBOX_PSEUDO}">
			# ENDIF #
			<br />
			# IF C_VERTICAL #<label for="shout_contents"><span class="small">${LangLoader::get_message('message', 'main')}</span></label># ENDIF #
			<textarea id="shout_contents" name="shout_contents"# IF C_VALIDATE_ONKEYPRESS_ENTER # onkeypress="if(event.keyCode==13){shoutbox_add_message();}"# ENDIF # rows="# IF C_VERTICAL #4# ELSE #2# ENDIF #" cols="16"></textarea>
			# IF C_DISPLAY_SHOUT_BBCODE #
			<div class="shout-spacing">
				<a href="javascript:bb_display_block('1', 'shout_contents');" onmouseover="bb_hide_block('1', 'shout_contents', 1);" onmouseout="bb_hide_block('1', 'shout_contents', 0);" class="fa bbcode-icon-smileys" title="${LangLoader::get_message('bb_smileys', 'common', 'BBCode')}"></a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block1shout_contents">
					<div class="bbcode-block" style="width:140px;" onmouseover="bb_hide_block('1', 'shout_contents', 1);" onmouseout="bb_hide_block('1', 'shout_contents', 0);">
						# START smileys #
							<a href="" onclick="insertbbcode('{smileys.CODE}', 'smile', 'shout_contents');return false;" class="bbcode-hover" title="{smileys.CODE}"><img src="{smileys.URL}" alt="{smileys.CODE}"></a># IF smileys.C_END_LINE #<br /># ENDIF #
						# END smileys #
						# IF C_BBCODE_SMILEY_MORE #
							<br /><br />
							<a href="" onclick="window.open('{PATH_TO_ROOT}/BBCode/formatting/smileys.php?field=shout_contents', '${LangLoader::get_message('smiley', 'main')}', 'height=550,width=650,resizable=yes,scrollbars=yes');return false;" title="${LangLoader::get_message('bb_smileys', 'common', 'BBCode')}" class="small">${LangLoader::get_message('all_smiley', 'main')}</a>
						# ENDIF #
					</div>
				</div>
				<a href="" class="fa bbcode-icon-bold# IF C_BOLD_DISABLED # shout-bbcode-icon-disabled# ENDIF #" onclick="# IF NOT C_BOLD_DISABLED #insertbbcode('[b]', '[/b]', 'shout_contents');# ENDIF #return false;" title="${LangLoader::get_message('bb_bold', 'common', 'BBCode')}"></a>
				<a href="" class="fa bbcode-icon-italic# IF C_ITALIC_DISABLED # shout-bbcode-icon-disabled# ENDIF #" onclick="# IF NOT C_ITALIC_DISABLED #insertbbcode('[i]', '[/i]', 'shout_contents');# ENDIF #return false;" title="${LangLoader::get_message('bb_italic', 'common', 'BBCode')}"></a>
				<a href="" class="fa bbcode-icon-underline# IF C_UNDERLINE_DISABLED # shout-bbcode-icon-disabled# ENDIF #" onclick="# IF NOT C_UNDERLINE_DISABLED #insertbbcode('[u]', '[/u]', 'shout_contents');# ENDIF #return false;" title="${LangLoader::get_message('bb_underline', 'common', 'BBCode')}"></a>
				<a href="" class="fa bbcode-icon-strike# IF C_STRIKE_DISABLED # shout-bbcode-icon-disabled# ENDIF #" onclick="# IF NOT C_STRIKE_DISABLED #insertbbcode('[s]', '[/s]', 'shout_contents');# ENDIF #return false;" title="${LangLoader::get_message('bb_strike', 'common', 'BBCode')}"></a>
			</div>
			# ENDIF #
			<p class="shout-spacing">
				<button onclick="shoutbox_add_message();" type="button">${LangLoader::get_message('submit', 'main')}</button>
				<a href="" onclick="shoutbox_refresh_messages_box();return false;" class="fa fa-refresh" id="shoutbox-refresh" title="${LangLoader::get_message('refresh', 'main')}"></a>
			</p>
		</form>
		# ELSE #
		<div class="spacer">&nbsp;</div>
		<span class="warning">${LangLoader::get_message('e_unauthorized', 'errors')}</span>
		# ENDIF #
		<a class="small" href="${relative_url(ShoutboxUrlBuilder::home())}" title="">{@archives}</a>
	</div>
	<div class="module-mini-bottom"></div>
</div>
