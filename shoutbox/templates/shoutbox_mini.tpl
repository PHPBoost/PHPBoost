		<script>
		<!--
		function XMLHttpRequest_shoutmsg()
		{
			# IF C_BBCODE_TINYMCE_MODE #
				tinyMCE.triggerSave();
			# ENDIF #
			
			var pseudo = document.getElementById("shout_pseudo").value;
			var contents = document.getElementById("shout_contents").value;

			if( pseudo != '' && contents != '' )
			{
				document.getElementById('shoutimg').className = 'fa fa-spinner fa-spin';

				pseudo = escape_xmlhttprequest(pseudo);
				contents = escape_xmlhttprequest(contents);
				data = "pseudo=" + pseudo + "&contents=" + contents;
				var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/shoutbox/xmlhttprequest.php?add=1# IF C_HORIZONTAL #&display_date=1# ENDIF #&token={TOKEN}');
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '-1' && xhr_object.responseText != '-2' && xhr_object.responseText != '-3' && xhr_object.responseText != '-4' && xhr_object.responseText != '-5' && xhr_object.responseText != '-6' )
					{	
						var array_shout = new Array('', '');
						eval(xhr_object.responseText);
						document.getElementById('shout-container').innerHTML = '<p id="shout-container-' + array_shout[2] + '">' + array_shout[0] + '<span class="small">: ' + array_shout[1] + '</span></p>' + document.getElementById('shout-container').innerHTML;
						document.getElementById('shout_contents').value = '';
						document.getElementById('shoutimg').className = 'fa fa-refresh';
					}
					else if( xhr_object.readyState == 4 )
					{	
						document.getElementById('shoutimg').className = 'fa fa-refresh';
						switch( xhr_object.responseText )
						{
							case '-1': 
								alert("{L_ALERT_UNAUTH_POST}");
							break;
							case '-2': 
								alert("{L_ALERT_FLOOD}");
							break;
							case '-3': 
								alert("{L_ALERT_LINK_PSEUDO}");
							break;
							case '-4': 
								alert("{L_ALERT_LINK_FLOOD}");
							break;
							case '-5': 
								alert("{L_ALERT_INCOMPLETE}");
							break;
							case '-6': 
								alert("{L_ALERT_READONLY}");
							break;
						}
					}
				}
				xmlhttprequest_sender(xhr_object, data);
			}
			else
				alert("{L_ALERT_INCOMPLETE}");
		}
		
		function XMLHttpRequest_shoutdelmsg(idmsg)
		{
			document.getElementById('shoutimg').className = 'fa fa-spinner fa-spin';
			data = "idmsg=" + idmsg;
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/shoutbox/xmlhttprequest.php?del=1&token={TOKEN}');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText == '1' )
				{
					document.getElementById('shoutimg').className = 'fa fa-refresh';
					document.getElementById('shout-container-' + idmsg).style.display = 'none';
				}
				else if( xhr_object.readyState == 4 )
					document.getElementById('shoutimg').className = 'fa fa-refresh';
			}
			xmlhttprequest_sender(xhr_object, data);
		}

		function XMLHttpRequest_shoutrefresh()
		{
			document.getElementById('shoutimg').className = 'fa fa-spinner fa-spin';
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/shoutbox/xmlhttprequest.php?refresh=1# IF C_HORIZONTAL #&display_date=1# ENDIF #&token={TOKEN}');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
				{
					document.getElementById('shoutimg').className = 'fa fa-refresh';
					document.getElementById('shout-container').innerHTML = xhr_object.responseText;
				}
				else if( xhr_object.readyState == 4 )
					document.getElementById('shoutimg').className = 'fa fa-refresh';
			}
			xmlhttprequest_sender(xhr_object, null);
			if( {SHOUT_REFRESH_DELAY} > 0 )
				setTimeout('XMLHttpRequest_shoutrefresh()', {SHOUT_REFRESH_DELAY});
		}
		function check_form_shout(){
			if(document.getElementById('shout_contents').value == "") {
				alert("{L_ALERT_TEXT}");
				return false;
			}
			return true;
		}
		
		function Confirm_del_shout(idmsg) {
			if( confirm("{L_DELETE_MSG}") )
				XMLHttpRequest_shoutdelmsg(idmsg);
		}
		
		if( {SHOUT_REFRESH_DELAY} > 0 )
			setTimeout('XMLHttpRequest_shoutrefresh()', {SHOUT_REFRESH_DELAY});
		-->
		</script>

			
		<form action="?token={TOKEN}" method="post" onsubmit="return check_form_shout();">
		# IF C_VERTICAL #
			<div class="module-mini-container">
				<div class="module-mini-top">
					<h5 class="sub-title">{L_SHOUTBOX}</h5>
				</div>
				<div class="module-mini-contents">
					<div id="shout-container">
						# START shout #
						<p id="shout-container-{shout.IDMSG}"><span class="small">{shout.PSEUDO} : {shout.CONTENTS}</span></p>
						# END shout #
					</div>
					# IF C_VISIBLE_SHOUT #
					<label for="shout_pseudo"><span class="small">{L_PSEUDO}</span></label>
					<input size="16" maxlength="25" type="text" name="shout_pseudo" id="shout_pseudo" value="{SHOUTBOX_PSEUDO}">
					# ENDIF #
					# IF C_HIDDEN_SHOUT #
					<input size="16" maxlength="25" type="hidden" name="shout_pseudo" id="shout_pseudo" value="{SHOUTBOX_PSEUDO}">
					# ENDIF #
					<br />
					<label for="shout_contents"><span class="small">{L_MESSAGE}</span></label>
					<textarea id="shout_contents" name="shout_contents" rows="4" cols="16"></textarea>
					
					<p class="shout-spacing">
						<button onclick="XMLHttpRequest_shoutmsg();" type="button">{L_SUBMIT}</button>
						<a href="javascript:XMLHttpRequest_shoutrefresh();" title="{L_REFRESH}"><i class="fa fa-refresh" id="shoutimg"></i></a>
					</p>
					<a class="small" href="{PATH_TO_ROOT}/shoutbox/shoutbox.php{SID}" title="">{L_ARCHIVES}</a>
				</div>
				<div class="module-mini-bottom"></div>
			</div>
		# ELSE #
			<div class="module-mini-container" style="width:auto;">
				<div class="module-mini-top">
					<h5 class="sub-title">{L_SHOUTBOX}</h5>
				</div>
				<div class="module-mini-contents" style="width:auto;">
					<div class="shout-horizontal">
						<div id="shout-container">
							# START shout #
							<p id="shout-container-{shout.IDMSG}">{shout.DEL_MSG}<span class="small"> {shout.DATE} : </span><span class="small">{shout.PSEUDO} : {shout.CONTENTS}</span></p>
							# END shout #
						</div>
						# IF C_VISIBLE_SHOUT #
							<label for="shout_pseudo"><span class="small">{L_PSEUDO}</span></label>
							<input size="16" maxlength="25" type="text" name="shout_pseudo" id="shout_pseudo" value="{SHOUTBOX_PSEUDO}">
						# ENDIF #
						# IF C_HIDDEN_SHOUT #
							<input size="16" maxlength="25" type="hidden" name="shout_pseudo" id="shout_pseudo" value="{SHOUTBOX_PSEUDO}">
						# ENDIF #
						<br />
						<textarea id="shout_contents" name="shout_contents" rows="2" cols="16"></textarea>
						<p class="shout-spacing">
							<button onclick="XMLHttpRequest_shoutmsg();" type="button">{L_SUBMIT}</button>
							<a href="javascript:XMLHttpRequest_shoutrefresh();" title="{L_REFRESH}"><i class="fa fa-refresh" id="shoutimg"></i></a>
						</p>
						<a class="small" href="{PATH_TO_ROOT}/shoutbox/shoutbox.php{SID}" title="">{L_ARCHIVES}</a>
					</div>
				</div>
				<div class="module-mini-bottom"></div>
			</div>
		# ENDIF #
		</form>