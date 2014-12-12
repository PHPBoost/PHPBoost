<script>
<!--
function shoutbox_delete_message(id_question)
{
	if (confirm(${escapejs(LangLoader::get_message('confirm.delete', 'status-messages-common'))}))
	{
		new Ajax.Request('${relative_url(ShoutboxUrlBuilder::ajax_delete())}', {
			method:'post',
			parameters: {'id' : id_question, 'token' : '{TOKEN}'},
			onLoading: function () {
				$('shoutbox-refresh').className = 'fa fa-spinner fa-spin';
			},
			onComplete: function(response) {
				if(response.readyState == 4 && response.status == 200 && response.responseText > 0) {
					var elementToDelete = $('shoutbox-message-' + response.responseText);
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

if( {SHOUT_REFRESH_DELAY} > 0 )
	setInterval(shoutbox_refresh_messages_box, {SHOUT_REFRESH_DELAY});

function XMLHttpRequest_shoutmsg()
{
	# IF C_BBCODE_TINYMCE_MODE #
		tinyMCE.triggerSave();
	# ENDIF #
	
	var pseudo = $("shout_pseudo").value;
	var contents = $("shout_contents").value;

	if( pseudo != '' && contents != '' )
	{
		$('shoutbox-refresh').className = 'fa fa-spinner fa-spin';

		pseudo = escape_xmlhttprequest(pseudo);
		contents = escape_xmlhttprequest(contents);
		data = "pseudo=" + pseudo + "&contents=" + contents;
		var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/shoutbox/xmlhttprequest.php?add=1&token={TOKEN}');
		xhr_object.onreadystatechange = function() 
		{
			if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '-1' && xhr_object.responseText != '-2' && xhr_object.responseText != '-3' && xhr_object.responseText != '-4')
			{
				shoutbox_refresh_messages_box();
				$('shout_contents').value = '';
			}
			else if( xhr_object.readyState == 4 )
			{
				switch( xhr_object.responseText )
				{
					case '-1': 
						alert("${LangLoader::get_message('e_flood', 'errors')}");
					break;
					case '-2': 
						alert("{L_ALERT_LINK_FLOOD}");
					break;
					case '-3': 
						alert("${LangLoader::get_message('e_incomplete', 'errors')}");
					break;
				}
				$('shoutbox-refresh').className = 'fa fa-refresh';
			}
		}
		xmlhttprequest_sender(xhr_object, data);
	}
	else
		alert("${LangLoader::get_message('e_incomplete', 'errors')}");
}

function check_form_shout(){
	if($('shout_contents').value == "") {
		alert("${LangLoader::get_message('require_text', 'main')}");
		return false;
	}
	return true;
}
-->
</script>

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
		<form action="?token={TOKEN}" method="post" onsubmit="return check_form_shout();">
			# IF NOT C_MEMBER #
			<label for="shout_pseudo"><span class="small">${LangLoader::get_message('pseudo', 'main')}</span></label>
			<input size="16" maxlength="25" type="text" name="shout_pseudo" id="shout_pseudo" value="${LangLoader::get_message('guest', 'main')}">
			# ELSE #
			<input size="16" maxlength="25" type="hidden" name="shout_pseudo" id="shout_pseudo" value="{SHOUTBOX_PSEUDO}">
			# ENDIF #
			<br />
			# IF C_VERTICAL #
			<label for="shout_contents"><span class="small left">${LangLoader::get_message('message', 'main')}</span></label>
			<textarea id="shout_contents" name="shout_contents" rows="4" cols="16"></textarea>
			# ELSE #
			<textarea id="shout_contents" name="shout_contents" rows="2" cols="16"></textarea>
			# ENDIF #
			<p class="shout-spacing">
				<button onclick="XMLHttpRequest_shoutmsg();" type="button">${LangLoader::get_message('submit', 'main')}</button>
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
