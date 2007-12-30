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
				document.getElementById('shoutimg').src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/loading_mini.gif';

				pseudo = escape_xmlhttprequest(pseudo);
				contents = escape_xmlhttprequest(contents);
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
						document.getElementById('shoutimg').src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/refresh_mini.png';
					}
					else if( xhr_object.readyState == 4 )
					{	
						document.getElementById('shoutimg').src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/refresh_mini.png';
						switch( xhr_object.responseText )
						{
							case '-1': 
								alert("<?php echo isset($this->_var['L_ALERT_UNAUTH_POST']) ? $this->_var['L_ALERT_UNAUTH_POST'] : ''; ?>");
							break;
							case '-2': 
								alert("<?php echo isset($this->_var['L_ALERT_FLOOD']) ? $this->_var['L_ALERT_FLOOD'] : ''; ?>");
							break;
							case '-3': 
								alert("<?php echo isset($this->_var['L_ALERT_LINK_PSEUDO']) ? $this->_var['L_ALERT_LINK_PSEUDO'] : ''; ?>");
							break;
							case '-4': 
								alert("<?php echo isset($this->_var['L_ALERT_LINK_FLOOD']) ? $this->_var['L_ALERT_LINK_FLOOD'] : ''; ?>");
							case '-5': 
								alert("<?php echo isset($this->_var['L_ALERT_INCOMPLETE']) ? $this->_var['L_ALERT_INCOMPLETE'] : ''; ?>");
							break;
						}
					}
				}
				xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr_object.send(data);
			}
			else
				alert("<?php echo isset($this->_var['L_ALERT_INCOMPLETE']) ? $this->_var['L_ALERT_INCOMPLETE'] : ''; ?>");
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
				
			document.getElementById('shoutimg').src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/loading_mini.gif';
			data = "idmsg=" + idmsg;
			xhr_object.open("POST", filename, true);
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText == '1' )
				{
					document.getElementById('shoutimg').src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/refresh_mini.png';
					document.getElementById('shout_container_' + idmsg).style.display = 'none';
				}
				else if( xhr_object.readyState == 4 )
					document.getElementById('shoutimg').src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/refresh_mini.png';
			}
			xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr_object.send(data);
		}
		function XMLHttpRequest_shoutrefresh()
		{
			var xhr_object = null;
			var data = null;
			var filename = "../shoutbox/xmlhttprequest.php?refresh=1";

			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non supporté par le navigateur
			    return;
				
			document.getElementById('shoutimg').src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/loading_mini.gif';
			xhr_object.open("POST", filename, true);
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
				{
					document.getElementById('shoutimg').src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/refresh_mini.png';
					document.getElementById('shout_container').innerHTML = xhr_object.responseText;
				}
				else if( xhr_object.readyState == 4 )
					document.getElementById('shoutimg').src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/refresh_mini.png';
			}
			xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr_object.send(data);
		}
		function check_form_shout(){
			if(document.getElementById('shout_contents').value == "") {
				alert("<?php echo isset($this->_var['L_ALERT_TEXT']) ? $this->_var['L_ALERT_TEXT'] : ''; ?>");
				return false;
			}
			return true;
		}
		function Confirm_del_shout(idmsg) {
			if( confirm("<?php echo isset($this->_var['L_DELETE_MSG']) ? $this->_var['L_DELETE_MSG'] : ''; ?>") )
				XMLHttpRequest_shoutdelmsg(idmsg);
		}
		
		-->
		</script>

		<form action="" method="post" onsubmit="return check_form_shout();">
		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title"><?php echo isset($this->_var['L_SHOUTBOX']) ? $this->_var['L_SHOUTBOX'] : ''; ?></h5>
			</div>
			<div class="module_mini_table">
				<div style="width:142px;height:150px;overflow:auto;text-align:left;margin:auto;" id="shout_container">					
					<?php if( !isset($this->_block['shout']) || !is_array($this->_block['shout']) ) $this->_block['shout'] = array();
foreach($this->_block['shout'] as $shout_key => $shout_value) {
$_tmpb_shout = &$this->_block['shout'][$shout_key]; ?>
					<p id="shout_container_<?php echo isset($_tmpb_shout['IDMSG']) ? $_tmpb_shout['IDMSG'] : ''; ?>"><?php echo isset($_tmpb_shout['PSEUDO']) ? $_tmpb_shout['PSEUDO'] : ''; ?><span class="text_small">: <?php echo isset($_tmpb_shout['CONTENTS']) ? $_tmpb_shout['CONTENTS'] : ''; ?></span></p>						
					<?php } ?>					
				</div>
				<?php if( isset($this->_var['C_VISIBLE_SHOUT']) && $this->_var['C_VISIBLE_SHOUT'] ) { ?>
				<label for="shout_pseudo"><span class="text_small"><?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?></span></label>
				<input size="16" maxlength="25" type="text" class="text" name="shout_pseudo" id="shout_pseudo" value="<?php echo isset($this->_var['SHOUTBOX_PSEUDO']) ? $this->_var['SHOUTBOX_PSEUDO'] : ''; ?>" />
				<?php } ?>					
				<?php if( isset($this->_var['C_HIDDEN_SHOUT']) && $this->_var['C_HIDDEN_SHOUT'] ) { ?>
				<input size="16" maxlength="25" type="hidden" class="text" name="shout_pseudo" id="shout_pseudo" value="<?php echo isset($this->_var['SHOUTBOX_PSEUDO']) ? $this->_var['SHOUTBOX_PSEUDO'] : ''; ?>" /></label>
				<?php } ?>
				<br />
				<label for="shout_contents"><span class="text_small"><?php echo isset($this->_var['L_MESSAGE']) ? $this->_var['L_MESSAGE'] : ''; ?></span></label>
				<textarea class="post" id="shout_contents" name="shout_contents" rows="4" cols="16"></textarea>					
				
				<input type="submit" name="shoutbox" id="shoutbox_submit" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
				<script type="text/javascript">
				<!--				
				document.getElementById('shoutbox_submit').style.display = 'none';
				document.write('<input value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" onclick="XMLHttpRequest_shoutmsg();" type="button" class="submit" />');
				-->
				</script>
				<a href="javascript:XMLHttpRequest_shoutrefresh();" title="<?php echo isset($this->_var['L_REFRESH']) ? $this->_var['L_REFRESH'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/refresh_mini.png" id="shoutimg" alt="<?php echo isset($this->_var['L_REFRESH']) ? $this->_var['L_REFRESH'] : ''; ?>" class="valign_middle" /></a>					
				<p style="margin-top:10px">
					<a class="small_link" href="../shoutbox/shoutbox.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>" title=""><?php echo isset($this->_var['L_ARCHIVE']) ? $this->_var['L_ARCHIVE'] : ''; ?></a>
				</p>
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
		</form>
		