		<script type="text/javascript">
		<!--
		function check_form_msg(){
			if(document.getElementById('contents').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_MESSAGE']) ? $this->_var['L_REQUIRE_MESSAGE'] : ''; ?>");
				return false;
		    }
			return true;
		}
		function Confirm_msg() {
			return confirm('<?php echo isset($this->_var['L_DELETE_MESSAGE']) ? $this->_var['L_DELETE_MESSAGE'] : ''; ?>');
		}
		function XMLHttpRequest_del(idmsg)
		{
			var xhr_object = null;
			var data = null;
			var filename = "../forum/xmlhttprequest.php?del=1&idm=" + idmsg;
			
			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non supporté par le navigateur
			    return;
				
			if( document.getElementById('dimg' + idmsg) )
				document.getElementById('dimg' + idmsg).src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/loading_mini.gif';
			
			xhr_object.open("POST", filename, true);
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '-1' )
				{	
					if( document.getElementById('d' + idmsg) )
						document.getElementById('d' + idmsg).style.display = 'none';
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '-1' )
				{	
					if( document.getElementById('dimg' + idmsg) )
						document.getElementById('dimg' + idmsg).src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/delete.png';
				}
			}
			xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr_object.send(null);
		}
		function del_msg(idmsg)
		{
			if( confirm('<?php echo isset($this->_var['L_DELETE_MESSAGE']) ? $this->_var['L_DELETE_MESSAGE'] : ''; ?>') )
				XMLHttpRequest_del(idmsg);
		}
		function Confirm_topic() {
			return confirm("<?php echo isset($this->_var['L_ALERT_DELETE_TOPIC']) ? $this->_var['L_ALERT_DELETE_TOPIC'] : ''; ?>");
		}		
		function Confirm_lock() {
			return confirm("<?php echo isset($this->_var['L_ALERT_LOCK_TOPIC']) ? $this->_var['L_ALERT_LOCK_TOPIC'] : ''; ?>");
		}		
		function Confirm_unlock() {
			return confirm("<?php echo isset($this->_var['L_ALERT_UNLOCK_TOPIC']) ? $this->_var['L_ALERT_UNLOCK_TOPIC'] : ''; ?>");
		}		
		function Confirm_move() {
			return confirm("<?php echo isset($this->_var['L_ALERT_MOVE_TOPIC']) ? $this->_var['L_ALERT_MOVE_TOPIC'] : ''; ?>");
		}
		function Confirm_cut() {
			return confirm("<?php echo isset($this->_var['L_ALERT_CUT_TOPIC']) ? $this->_var['L_ALERT_CUT_TOPIC'] : ''; ?>");
		}

		-->
		</script>

		<span id="go_top"></span>	

		<?php $this->tpl_include('forum_top'); ?>

		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<span style="float:left;">
					<a href="rss.php?cat=<?php echo isset($this->_var['ID']) ? $this->_var['ID'] : ''; ?>" title="Rss"><img class="valign_middle" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/rss.gif" alt="Rss" title="Rss" /></a>
					&bull; <?php echo isset($this->_var['U_FORUM_CAT']) ? $this->_var['U_FORUM_CAT'] : ''; echo ' '; echo isset($this->_var['U_TITLE_T']) ? $this->_var['U_TITLE_T'] : ''; ?> <span style="font-weight:normal"><em><?php echo isset($this->_var['DESC']) ? $this->_var['DESC'] : ''; ?></em></span>
				</span>
				<span style="float:right;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; echo ' '; echo isset($this->_var['LOCK']) ? $this->_var['LOCK'] : ''; echo ' '; echo isset($this->_var['MOVE']) ? $this->_var['MOVE'] : ''; ?></span>&nbsp;
			</div>
		</div>	

		<?php if( isset($this->_var['C_POLL_EXIST']) && $this->_var['C_POLL_EXIST'] ) { ?>
		<div class="module_position">
			<div class="module_contents">
				<form method="post" action="action<?php echo isset($this->_var['U_POLL_ACTION']) ? $this->_var['U_POLL_ACTION'] : ''; ?>">
					<table class="module_table" style="width:70%">
						<tr>
							<th><?php echo isset($this->_var['L_POLL']) ? $this->_var['L_POLL'] : ''; ?>: <?php echo isset($this->_var['QUESTION']) ? $this->_var['QUESTION'] : ''; ?></th>
						</tr>							
						<?php if( !isset($this->_block['poll_radio']) || !is_array($this->_block['poll_radio']) ) $this->_block['poll_radio'] = array();
foreach($this->_block['poll_radio'] as $poll_radio_key => $poll_radio_value) {
$_tmpb_poll_radio = &$this->_block['poll_radio'][$poll_radio_key]; ?>
						<tr>
							<td class="row2" style="font-size:10px;">
								<label><input type="<?php echo isset($_tmpb_poll_radio['TYPE']) ? $_tmpb_poll_radio['TYPE'] : ''; ?>" name="radio" value="<?php echo isset($_tmpb_poll_radio['NAME']) ? $_tmpb_poll_radio['NAME'] : ''; ?>" /> <?php echo isset($_tmpb_poll_radio['ANSWERS']) ? $_tmpb_poll_radio['ANSWERS'] : ''; ?></label>
							</td>
						</tr>
						<?php } ?>							
						<?php if( !isset($this->_block['poll_checkbox']) || !is_array($this->_block['poll_checkbox']) ) $this->_block['poll_checkbox'] = array();
foreach($this->_block['poll_checkbox'] as $poll_checkbox_key => $poll_checkbox_value) {
$_tmpb_poll_checkbox = &$this->_block['poll_checkbox'][$poll_checkbox_key]; ?>
						<tr>
							<td class="row2">
								<label><input type="<?php echo isset($_tmpb_poll_checkbox['TYPE']) ? $_tmpb_poll_checkbox['TYPE'] : ''; ?>" name="<?php echo isset($_tmpb_poll_checkbox['NAME']) ? $_tmpb_poll_checkbox['NAME'] : ''; ?>" value="<?php echo isset($_tmpb_poll_checkbox['NAME']) ? $_tmpb_poll_checkbox['NAME'] : ''; ?>" /> <?php echo isset($_tmpb_poll_checkbox['ANSWERS']) ? $_tmpb_poll_checkbox['ANSWERS'] : ''; ?></label>
							</td>
						</tr>
						<?php } ?>					
						<?php if( !isset($this->_block['poll_result']) || !is_array($this->_block['poll_result']) ) $this->_block['poll_result'] = array();
foreach($this->_block['poll_result'] as $poll_result_key => $poll_result_value) {
$_tmpb_poll_result = &$this->_block['poll_result'][$poll_result_key]; ?>
						<tr>
							<td class="row2" style="font-size:10px;">
								<?php echo isset($_tmpb_poll_result['ANSWERS']) ? $_tmpb_poll_result['ANSWERS'] : ''; ?>
								<table width="95%">
									<tr>
										<td>
											<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/poll_left.png" height="8px" width="" alt="<?php echo isset($_tmpb_poll_result['PERCENT']) ? $_tmpb_poll_result['PERCENT'] : ''; ?>%" title="<?php echo isset($_tmpb_poll_result['PERCENT']) ? $_tmpb_poll_result['PERCENT'] : ''; ?>%" /><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/poll.png" height="8px" width="<?php echo isset($_tmpb_poll_result['WIDTH']) ? $_tmpb_poll_result['WIDTH'] : ''; ?>" alt="<?php echo isset($_tmpb_poll_result['PERCENT']) ? $_tmpb_poll_result['PERCENT'] : ''; ?>%" title="<?php echo isset($_tmpb_poll_result['PERCENT']) ? $_tmpb_poll_result['PERCENT'] : ''; ?>%" /><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/poll_right.png" height="8px" width="" alt="<?php echo isset($_tmpb_poll_result['PERCENT']) ? $_tmpb_poll_result['PERCENT'] : ''; ?>%" title="<?php echo isset($_tmpb_poll_result['PERCENT']) ? $_tmpb_poll_result['PERCENT'] : ''; ?>%" /> <?php echo isset($_tmpb_poll_result['PERCENT']) ? $_tmpb_poll_result['PERCENT'] : ''; ?>% [<?php echo isset($_tmpb_poll_result['NBRVOTE']) ? $_tmpb_poll_result['NBRVOTE'] : ''; echo ' '; echo isset($this->_var['L_VOTE']) ? $this->_var['L_VOTE'] : ''; ?>]
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<?php } ?>										
					</table>
					
					<?php if( isset($this->_var['C_POLL_QUESTION']) && $this->_var['C_POLL_QUESTION'] ) { ?>
					<br />
					<fieldset class="fieldset_submit">
						<legend><?php echo isset($this->_var['L_VOTE']) ? $this->_var['L_VOTE'] : ''; ?></legend>
						<input class="submit" name="valid_forum_poll" type="submit" value="<?php echo isset($this->_var['L_VOTE']) ? $this->_var['L_VOTE'] : ''; ?>" /><br />
						<a class="small_link" href="topic<?php echo isset($this->_var['U_POLL_RESULT']) ? $this->_var['U_POLL_RESULT'] : ''; ?>"><?php echo isset($this->_var['L_RESULT']) ? $this->_var['L_RESULT'] : ''; ?></a>
					</fieldset>						
					<?php } ?>
				</form>
			</div>
		</div>
		<?php } ?>

		<?php if( !isset($this->_block['msg']) || !is_array($this->_block['msg']) ) $this->_block['msg'] = array();
foreach($this->_block['msg'] as $msg_key => $msg_value) {
$_tmpb_msg = &$this->_block['msg'][$msg_key]; ?>		
		<div class="msg_position" id="d<?php echo isset($_tmpb_msg['ID']) ? $_tmpb_msg['ID'] : ''; ?>">
			<div class="msg_container">
				<span id="m<?php echo isset($_tmpb_msg['ID']) ? $_tmpb_msg['ID'] : ''; ?>">
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						<?php echo isset($_tmpb_msg['USER_ONLINE']) ? $_tmpb_msg['USER_ONLINE'] : ''; echo ' '; echo isset($_tmpb_msg['USER_PSEUDO']) ? $_tmpb_msg['USER_PSEUDO'] : ''; ?>
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="topic<?php echo isset($_tmpb_msg['U_VARS_ANCRE']) ? $_tmpb_msg['U_VARS_ANCRE'] : ''; ?>#m<?php echo isset($_tmpb_msg['ID']) ? $_tmpb_msg['ID'] : ''; ?>" title=""><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/ancre.png" alt="" /></a> <?php echo isset($_tmpb_msg['DATE']) ? $_tmpb_msg['DATE'] : ''; ?></div>
					<div style="float:right;"><a href="topic<?php echo isset($_tmpb_msg['U_VARS_QUOTE']) ? $_tmpb_msg['U_VARS_QUOTE'] : ''; ?>#go_bottom" title="<?php echo isset($this->_var['L_QUOTE']) ? $this->_var['L_QUOTE'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/quote.png" alt="<?php echo isset($this->_var['L_QUOTE']) ? $this->_var['L_QUOTE'] : ''; ?>" title="<?php echo isset($this->_var['L_QUOTE']) ? $this->_var['L_QUOTE'] : ''; ?>" /></a><?php echo isset($_tmpb_msg['EDIT']) ? $_tmpb_msg['EDIT'] : '';  echo isset($_tmpb_msg['DEL']) ? $_tmpb_msg['DEL'] : '';  echo isset($_tmpb_msg['CUT']) ? $_tmpb_msg['CUT'] : ''; ?>&nbsp;&nbsp;<a href="topic<?php echo isset($_tmpb_msg['U_VARS_ANCRE']) ? $_tmpb_msg['U_VARS_ANCRE'] : ''; ?>#go_top"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a> <a href="topic<?php echo isset($_tmpb_msg['U_VARS_ANCRE']) ? $_tmpb_msg['U_VARS_ANCRE'] : ''; ?>#go_bottom"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;"><?php echo isset($_tmpb_msg['USER_RANK']) ? $_tmpb_msg['USER_RANK'] : ''; ?></p>
						<p style="text-align:center;"><?php echo isset($_tmpb_msg['USER_IMG_ASSOC']) ? $_tmpb_msg['USER_IMG_ASSOC'] : ''; ?></p>
						<p style="text-align:center;"><?php echo isset($_tmpb_msg['USER_AVATAR']) ? $_tmpb_msg['USER_AVATAR'] : ''; ?></p>
						<p style="text-align:center;"><?php echo isset($_tmpb_msg['USER_GROUP']) ? $_tmpb_msg['USER_GROUP'] : ''; ?></p>	
						<?php echo isset($_tmpb_msg['USER_SEX']) ? $_tmpb_msg['USER_SEX'] : ''; ?>
						<?php echo isset($_tmpb_msg['USER_DATE']) ? $_tmpb_msg['USER_DATE'] : ''; ?><br />
						<?php echo isset($_tmpb_msg['USER_MSG']) ? $_tmpb_msg['USER_MSG'] : ''; ?><br />
						<?php echo isset($_tmpb_msg['USER_LOCAL']) ? $_tmpb_msg['USER_LOCAL'] : ''; ?>		
					</div>
					<div class="msg_contents">
						<div class="msg_contents_overflow">
							<?php echo isset($_tmpb_msg['CONTENTS']) ? $_tmpb_msg['CONTENTS'] : ''; ?>
							<?php echo isset($_tmpb_msg['USER_EDIT']) ? $_tmpb_msg['USER_EDIT'] : ''; ?>
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign">				
				<div class="msg_sign_overflow">
					<?php echo isset($_tmpb_msg['USER_SIGN']) ? $_tmpb_msg['USER_SIGN'] : ''; ?>
				</div>			
				<hr />
				<span style="float:left;">
					<?php echo isset($_tmpb_msg['USER_PM']) ? $_tmpb_msg['USER_PM'] : ''; echo ' '; echo isset($_tmpb_msg['USER_MAIL']) ? $_tmpb_msg['USER_MAIL'] : ''; echo ' '; echo isset($_tmpb_msg['USER_MSN']) ? $_tmpb_msg['USER_MSN'] : ''; echo ' '; echo isset($_tmpb_msg['USER_YAHOO']) ? $_tmpb_msg['USER_YAHOO'] : ''; echo ' '; echo isset($_tmpb_msg['USER_WEB']) ? $_tmpb_msg['USER_WEB'] : ''; ?>
				</span>
				<span style="float:right;font-size:10px;">
					<?php echo isset($_tmpb_msg['WARNING']) ? $_tmpb_msg['WARNING'] : ''; echo ' '; echo isset($_tmpb_msg['PUNISHMENT']) ? $_tmpb_msg['PUNISHMENT'] : ''; ?>
				</span>&nbsp;
			</div>	
		</div>	
		<?php } ?>
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;">
				<span style="float:left;" class="text_strong">
					<a href="rss.php?cat=<?php echo isset($this->_var['ID']) ? $this->_var['ID'] : ''; ?>" title="Rss"><img class="valign_middle" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/rss.gif" alt="Rss" title="Rss" /></a>
					&bull; <?php echo isset($this->_var['U_FORUM_CAT']) ? $this->_var['U_FORUM_CAT'] : ''; echo ' '; echo isset($this->_var['U_TITLE_T']) ? $this->_var['U_TITLE_T'] : ''; ?> <span style="font-weight:normal"><em><?php echo isset($this->_var['DESC']) ? $this->_var['DESC'] : ''; ?></em></span>
				</span>
				<span style="float:right;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; echo ' '; echo isset($this->_var['LOCK']) ? $this->_var['LOCK'] : ''; echo ' '; echo isset($this->_var['MOVE']) ? $this->_var['MOVE'] : ''; ?></span>&nbsp;
			</div>
		</div>
		
		<?php $this->tpl_include('forum_bottom'); ?>
			
			
		<?php if( isset($this->_var['C_AUTH_POST']) && $this->_var['C_AUTH_POST'] ) { ?>		
		<form action="post<?php echo isset($this->_var['U_FORUM_ACTION_POST']) ? $this->_var['U_FORUM_ACTION_POST'] : ''; ?>" method="post" onsubmit="return check_form_msg();" style="width:80%;margin:auto;margin-top:15px;" id="go_bottom">		
			<div style="font-size:11px;text-align:center;"><label for="contents"><?php echo isset($this->_var['L_RESPOND']) ? $this->_var['L_RESPOND'] : ''; ?></label></div>	
			<?php $this->tpl_include('handle_bbcode'); ?>		
			<label><textarea class="post" rows="15" cols="66" id="contents" name="contents"><?php echo isset($this->_var['CONTENTS']) ? $this->_var['CONTENTS'] : ''; ?></textarea></label>
			<fieldset class="fieldset_submit" style="padding-top:17px;">
				<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
				<input type="submit" name="valid" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
				&nbsp;&nbsp; 									
				<script type="text/javascript">
				<!--				
				document.write('<input value="<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>" onclick="XMLHttpRequest_preview(this.form);" type="button" class="submit" />');
				-->
				</script>						
				<noscript><input value="<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>" type="submit" name="prw" class="submit" /></noscript>
				&nbsp;&nbsp;
				<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />				
			</fieldset>			
		</form>		
		<table class="forum_action">
			<tr>
				<?php if( isset($this->_var['C_DISPLAY_MSG']) && $this->_var['C_DISPLAY_MSG'] ) { ?>
				<td><a href="action<?php echo isset($this->_var['U_ACTION_MSG_DISPLAY']) ? $this->_var['U_ACTION_MSG_DISPLAY'] : ''; ?>#go_bottom"><?php echo isset($this->_var['ICON_DISPLAY_MSG']) ? $this->_var['ICON_DISPLAY_MSG'] : ''; ?></a>	<a href="action<?php echo isset($this->_var['U_ACTION_MSG_DISPLAY']) ? $this->_var['U_ACTION_MSG_DISPLAY'] : ''; ?>#go_bottom"><?php echo isset($this->_var['L_EXPLAIN_DISPLAY_MSG']) ? $this->_var['L_EXPLAIN_DISPLAY_MSG'] : ''; ?></a> </td>	
				<?php } ?>			
				<td><a href="action<?php echo isset($this->_var['U_SUSCRIBE']) ? $this->_var['U_SUSCRIBE'] : ''; ?>#go_bottom"><img class="valign_middle" src="<?php echo isset($this->_var['MODULE_DATA_PATH']) ? $this->_var['MODULE_DATA_PATH'] : ''; ?>/images/favorite.png" alt="" /></a> <a href="action<?php echo isset($this->_var['U_SUSCRIBE']) ? $this->_var['U_SUSCRIBE'] : ''; ?>#go_bottom"><?php echo isset($this->_var['L_SUSCRIBE']) ? $this->_var['L_SUSCRIBE'] : ''; ?></a></td>
				<td><a href="alert<?php echo isset($this->_var['U_ALERT']) ? $this->_var['U_ALERT'] : ''; ?>#go_bottom"><img class="valign_middle" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/important.png" alt="" /> <a href="alert<?php echo isset($this->_var['U_ALERT']) ? $this->_var['U_ALERT'] : ''; ?>#go_bottom"><?php echo isset($this->_var['L_ALERT']) ? $this->_var['L_ALERT'] : ''; ?></a></td>
			</tr>
		</table>
		<?php } ?>
		
		<?php if( isset($this->_var['C_ERROR_AUTH_WRITE']) && $this->_var['C_ERROR_AUTH_WRITE'] ) { ?>
		<div style="font-size:10px;text-align:center;padding-bottom:2px;"><?php echo isset($this->_var['L_RESPOND']) ? $this->_var['L_RESPOND'] : ''; ?></div>	
		<div class="forum_text_column" style="width:350px;margin:auto;height:auto;padding:2px;">
			<?php echo isset($this->_var['L_ERROR_AUTH_WRITE']) ? $this->_var['L_ERROR_AUTH_WRITE'] : ''; ?>			
		</div>
		<?php } ?>
		