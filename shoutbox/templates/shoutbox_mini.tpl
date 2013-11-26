		<script type="text/javascript">
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
				document.getElementById('shoutimg').className = 'icon-spinner icon-spin';

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
						document.getElementById('shout_container').innerHTML = '<p id="shout_container_' + array_shout[2] + '">' + array_shout[0] + '<span class="smaller">: ' + array_shout[1] + '</span></p>' + document.getElementById('shout_container').innerHTML;
						document.getElementById('shout_contents').value = '';
						document.getElementById('shoutimg').className = 'icon-refresh';
					}
					else if( xhr_object.readyState == 4 )
					{	
						document.getElementById('shoutimg').className = 'icon-refresh';
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
			document.getElementById('shoutimg').className = 'icon-spinner icon-spin';
			data = "idmsg=" + idmsg;
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/shoutbox/xmlhttprequest.php?del=1&token={TOKEN}');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText == '1' )
				{
					document.getElementById('shoutimg').className = 'icon-refresh';
					document.getElementById('shout_container_' + idmsg).style.display = 'none';
				}
				else if( xhr_object.readyState == 4 )
					document.getElementById('shoutimg').className = 'icon-refresh';
			}
			xmlhttprequest_sender(xhr_object, data);
		}

		function XMLHttpRequest_shoutrefresh()
		{
			document.getElementById('shoutimg').className = 'icon-spinner icon-spin';
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/shoutbox/xmlhttprequest.php?refresh=1# IF C_HORIZONTAL #&display_date=1# ENDIF #&token={TOKEN}');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
				{
					document.getElementById('shoutimg').className = 'icon-refresh';
					document.getElementById('shout_container').innerHTML = xhr_object.responseText;
				}
				else if( xhr_object.readyState == 4 )
					document.getElementById('shoutimg').className = 'icon-refresh';
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
			<div class="module_mini_container">
				<div class="module_mini_top">
					<h5 class="sub_title">{L_SHOUTBOX}</h5>
				</div>
				<div class="module_mini_contents">
					<div id="shout_container">					
						# START shout #
						<p id="shout_container_{shout.IDMSG}">{shout.PSEUDO}<span class="smaller"> : {shout.CONTENTS}</span></p>
						# END shout #					
					</div>
					# IF C_VISIBLE_SHOUT #
					<label for="shout_pseudo"><span class="smaller">{L_PSEUDO}</span></label>
					<input size="16" maxlength="25" type="text" name="shout_pseudo" id="shout_pseudo" value="{SHOUTBOX_PSEUDO}">
					# ENDIF #					
					# IF C_HIDDEN_SHOUT #
					<input size="16" maxlength="25" type="hidden" name="shout_pseudo" id="shout_pseudo" value="{SHOUTBOX_PSEUDO}">
					# ENDIF #
					<br />
					<label for="shout_contents"><span class="smaller">{L_MESSAGE}</span></label>
					<textarea id="shout_contents" name="shout_contents" rows="4" cols="16"></textarea>
					
					<p class="shout_spacing">
						<button onclick="XMLHttpRequest_shoutmsg();" type="button">{L_SUBMIT}</button>
						<a href="javascript:XMLHttpRequest_shoutrefresh();" title="{L_REFRESH}"><i class="icon-refresh" id="shoutimg"></i></a>
					</p>
					<a class="small" href="{PATH_TO_ROOT}/shoutbox/shoutbox.php{SID}" title="">{L_ARCHIVES}</a>
				</div>
				<div class="module_mini_bottom"></div>
			</div>
		# ELSE #
			<div class="module_mini_container" style="width:auto;">
				<div class="module_mini_top">
					<h5 class="sub_title">{L_SHOUTBOX}</h5>
				</div>
				<div class="module_mini_contents" style="width:auto;">
					<div class="shout_horizontal">
						<div id="shout_container">
							# START shout #
							<p id="shout_container_{shout.IDMSG}">{shout.DEL_MSG}<span class="smaller"> {shout.DATE} : </span>{shout.PSEUDO}<span class="smaller"> : {shout.CONTENTS}</span></p>
							# END shout #
						</div>
						# IF C_VISIBLE_SHOUT #
							<label for="shout_pseudo"><span class="smaller">{L_PSEUDO}</span></label>
							<input size="16" maxlength="25" type="text" name="shout_pseudo" id="shout_pseudo" value="{SHOUTBOX_PSEUDO}">
						# ENDIF #
						# IF C_HIDDEN_SHOUT #
							<input size="16" maxlength="25" type="hidden" name="shout_pseudo" id="shout_pseudo" value="{SHOUTBOX_PSEUDO}">
						# ENDIF #
						<br />
						<textarea id="shout_contents" name="shout_contents" rows="2" cols="16"></textarea>
						<p class="shout_spacing">
							<button onclick="XMLHttpRequest_shoutmsg();" type="button">{L_SUBMIT}</button>
							<a href="javascript:XMLHttpRequest_shoutrefresh();" title="{L_REFRESH}"><i class="icon-refresh" id="shoutimg"></i></a>
						</p>
						<a class="small" href="{PATH_TO_ROOT}/shoutbox/shoutbox.php{SID}" title="">{L_ARCHIVES}</a>
					</div>
				</div>
				<div class="module_mini_bottom"></div>
			</div>
		# ENDIF #
		</form>