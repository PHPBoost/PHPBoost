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
				document.getElementById('shoutimg').src = '{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif';

				pseudo = escape_xmlhttprequest(pseudo);
				contents = escape_xmlhttprequest(contents);
				data = "pseudo=" + pseudo + "&contents=" + contents;
				var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/shoutbox/xmlhttprequest.php?token={TOKEN}&add=1&token={TOKEN}');
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '-1' && xhr_object.responseText != '-2' && xhr_object.responseText != '-3' && xhr_object.responseText != '-4' && xhr_object.responseText != '-5' && xhr_object.responseText != '-6' )
					{	
						var array_shout = new Array('', '');
						eval(xhr_object.responseText);
						document.getElementById('shout_container').innerHTML = '<p id="shout_container_' + array_shout[2] + '">' + array_shout[0] + '<span class="text_small">: ' + array_shout[1] + '</span></p>' + document.getElementById('shout_container').innerHTML;
						document.getElementById('shout_contents').value = '';
						document.getElementById('shoutimg').src = '{PATH_TO_ROOT}/templates/{THEME}/images/refresh_mini.png';
					}
					else if( xhr_object.readyState == 4 )
					{	
						document.getElementById('shoutimg').src = '{PATH_TO_ROOT}/templates/{THEME}/images/refresh_mini.png';
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
			document.getElementById('shoutimg').src = '{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif';
			data = "idmsg=" + idmsg;
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/shoutbox/xmlhttprequest.php?del=1&token={TOKEN}');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText == '1' )
				{
					document.getElementById('shoutimg').src = '{PATH_TO_ROOT}/templates/{THEME}/images/refresh_mini.png';
					document.getElementById('shout_container_' + idmsg).style.display = 'none';
				}
				else if( xhr_object.readyState == 4 )
					document.getElementById('shoutimg').src = '{PATH_TO_ROOT}/templates/{THEME}/images/refresh_mini.png';
			}
			xmlhttprequest_sender(xhr_object, data);
		}

		function XMLHttpRequest_shoutrefresh()
		{
			document.getElementById('shoutimg').src = '{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif';
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/shoutbox/xmlhttprequest.php?refresh=1&token={TOKEN}');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
				{
					document.getElementById('shoutimg').src = '{PATH_TO_ROOT}/templates/{THEME}/images/refresh_mini.png';
					document.getElementById('shout_container').innerHTML = xhr_object.responseText;
				}
				else if( xhr_object.readyState == 4 )
					document.getElementById('shoutimg').src = '{PATH_TO_ROOT}/templates/{THEME}/images/refresh_mini.png';
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
		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_SHOUTBOX}</h5>
			</div>
			<div class="module_mini_contents">
				<div id="shout_container">					
					# START shout #
					<p id="shout_container_{shout.IDMSG}">{shout.PSEUDO}<span class="text_small">: {shout.CONTENTS}</span></p>						
					# END shout #					
				</div>
				# IF C_VISIBLE_SHOUT #
				<label for="shout_pseudo"><span class="text_small">{L_PSEUDO}</span></label>
				<input size="16" maxlength="25" type="text" class="text" name="shout_pseudo" id="shout_pseudo" value="{SHOUTBOX_PSEUDO}" />
				# ENDIF #					
				# IF C_HIDDEN_SHOUT #
				<input size="16" maxlength="25" type="hidden" class="text" name="shout_pseudo" id="shout_pseudo" value="{SHOUTBOX_PSEUDO}" />
				# ENDIF #
				<br />
				<label for="shout_contents"><span class="text_small">{L_MESSAGE}</span></label>
				<textarea class="post" id="shout_contents" name="shout_contents" rows="4" cols="16"></textarea>					
				
				<p style="margin:0;margin-bottom:10px;">
					<input type="submit" name="shoutbox" id="shoutbox_submit" value="{L_SUBMIT}" class="submit" />
					<script type="text/javascript">
					<!--				
					document.getElementById('shoutbox_submit').style.display = 'none';
					document.write('<input value="{L_SUBMIT}" onclick="XMLHttpRequest_shoutmsg();" type="button" class="submit" />');
					-->
					</script>
					<a href="javascript:XMLHttpRequest_shoutrefresh();" title="{L_REFRESH}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/refresh_mini.png" id="shoutimg" alt="{L_REFRESH}" class="valign_middle" /></a>					
				</p>
				<a class="small_link" href="{PATH_TO_ROOT}/shoutbox/shoutbox.php{SID}" title="">{L_ARCHIVES}</a>
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
		</form>
		