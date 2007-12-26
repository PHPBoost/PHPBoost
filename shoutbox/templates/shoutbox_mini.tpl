		<script type="text/javascript">
		<!--
		function XMLHttpRequest_shoutmsg()
		{
			var xhr_object = null;
			var data = null;
			var filename = "../shoutbox/xmlhttprequest.php?add=1";
			var pseudo = document.getElementById("shout_pseudo").value;
			var contents = document.getElementById("shout_contents").value;
			
			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non supporté par le navigateur
			    return;
				
			if( pseudo != "" && contents != "" )
			{
				document.getElementById('shoutimg').innerHTML = '<img src="../templates/{THEME}/images/loading_mini.gif" alt="" />';

				data = "pseudo=" + pseudo + "&contents=" + contents;
				xhr_object.open("POST", filename, true);
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '-1' && xhr_object.responseText != '-2' && xhr_object.responseText != '-3' && xhr_object.responseText != '-4' )
					{	
						var array_shout = new Array('', '');
						eval(xhr_object.responseText);
						document.getElementById('shout_container').innerHTML = '<p id="shout_container_' + array_shout[2] + '">' + array_shout[0] + '<span class="text_small">: ' + array_shout[1] + '</span></p>' + document.getElementById('shout_container').innerHTML;
						document.getElementById('shout_contents').value = '';
						document.getElementById('shoutimg').innerHTML = '';
					}
					else if( xhr_object.readyState == 4 )
					{	
						document.getElementById('shoutimg').innerHTML = '';
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
							case '-5': 
								alert("{L_ALERT_INCOMPLETE}");
							break;
						}
					}
				}
				xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr_object.send(data);
			}
			else
				alert("{L_ALERT_INCOMPLETE}");
		}
		function XMLHttpRequest_shoutdelmsg(idmsg)
		{
			var xhr_object = null;
			var data = null;
			var filename = "../shoutbox/xmlhttprequest.php?del=1";

			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non supporté par le navigateur
			    return;
				
			document.getElementById('shoutimg').innerHTML = '<img src="../templates/{THEME}/images/loading_mini.gif" alt="" />';
			data = "idmsg=" + idmsg;
			xhr_object.open("POST", filename, true);
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText == '1' )
				{
					document.getElementById('shoutimg').innerHTML = '';
					document.getElementById('shout_container_' + idmsg).style.display = 'none';
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText != '1' )
					document.getElementById('shoutimg').innerHTML = '';
			}
			xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr_object.send(data);
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
		var str = "array_str = new Array('test', 'test2');"
		
		-->
		</script>

		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_SHOUTBOX}</h5>
			</div>
			<div class="module_mini_table">
				<form action="" method="post" onsubmit="return check_form_shout();">
					<div style="width:142px;height:150px;overflow:auto;text-align:left;margin:auto;" id="shout_container">					
						# START shout #
						<p id="shout_container_{shout.IDMSG}">{shout.PSEUDO}<span class="text_small">: {shout.CONTENTS}</span></p>						
						# END shout #					
					</div>
					<p>
						# START visible_shout #
						<label><span style="font-size:10px;">Pseudo</span>
						<br />
						<input size="16" maxlength="25" type="text" class="text" name="shout_pseudo" id="shout_pseudo" value="{visible_shout.PSEUDO}" /></label>
						# END visible_shout #
						
						# START hidden_shout #
						<input size="16" maxlength="25" type="hidden" class="text" name="shout_pseudo" id="shout_pseudo" value="{hidden_shout.PSEUDO}" /></label>
						# END hidden_shout #
						
						<label><span style="font-size:10px;">{L_MESSAGE}</span>
						<textarea class="post" id="shout_contents" name="shout_contents" rows="4" cols="16"></textarea></label>
						
						<script type="text/javascript">
						<!--				
						document.write('<input value="{L_SUBMIT}" onclick="XMLHttpRequest_shoutmsg();" type="button" class="submit" />');
						-->
						</script>
						<noscript><input type="submit" name="shoutbox" value="{L_SUBMIT}" class="submit" /></noscript>
						<div id="shoutimg"></div>			
					</p>				
					<p>
						<a class="small_link" href="../shoutbox/shoutbox.php{SID}" title="">{L_ARCHIVE}</a>
					</p>
				</form>
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
		