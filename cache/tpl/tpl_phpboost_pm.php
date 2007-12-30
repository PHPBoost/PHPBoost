		<script type="text/javascript">
		<!--
		function check_form_convers(){
			if(document.getElementById('login').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_RECIPIENT']) ? $this->_var['L_REQUIRE_RECIPIENT'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('contents').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_MESSAGE']) ? $this->_var['L_REQUIRE_MESSAGE'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('title').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_TITLE']) ? $this->_var['L_REQUIRE_TITLE'] : ''; ?>");
				return false;
		    }
			return true;
		}

		function check_form_pm(){
			if(document.getElementById('contents').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_MESSAGE']) ? $this->_var['L_REQUIRE_MESSAGE'] : ''; ?>");
				return false;
		    }
			return true;
		}

		function Confirm_pm() {
			return confirm("<?php echo isset($this->_var['L_DELETE_MESSAGE']) ? $this->_var['L_DELETE_MESSAGE'] : ''; ?>");
		}

		function XMLHttpRequest_search()
		{
			var xhr_object = null;
			var filename = "../includes/xmlhttprequest.php?pm=1";
			var login = document.getElementById("login").value;
			var data = null;
			
			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non supporté par le navigateur
			    return;
			
			if( login != "" )
			{
				data = "login=" + login;
			   
				xhr_object.open("POST", filename, true);

				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 ) 
					{
						document.getElementById("xmlhttprequest_result_search").innerHTML = xhr_object.responseText;
						show_div("xmlhttprequest_result_search");
					}
				}

				xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr_object.send(data);
			}	
			else
			{
				alert("<?php echo isset($this->_var['L_REQUIRE_RECIPIENT']) ? $this->_var['L_REQUIRE_RECIPIENT'] : ''; ?>");
			}	
		}

		function insert_XMLHttpRequest(login)
		{
			document.getElementById("login").value = login;
		}

		-->
		</script>

		
		<?php if( !isset($this->_block['convers']) || !is_array($this->_block['convers']) ) $this->_block['convers'] = array();
foreach($this->_block['convers'] as $convers_key => $convers_value) {
$_tmpb_convers = &$this->_block['convers'][$convers_key]; ?>
		<script type="text/javascript">
		<!--
			function check_convers(status, id)
			{
				var i;
				for(i = 0; i < <?php echo isset($_tmpb_convers['NBR_PM']) ? $_tmpb_convers['NBR_PM'] : ''; ?>; i++)
				{	
					if( document.getElementById(id + i) ) 
						document.getElementById(id + i).checked = status;
				}
				document.getElementById('checkall').checked = status;
				document.getElementById('validc').checked = status;
			}	 
		-->
		</script>
		<?php if( !isset($_tmpb_convers['error_handler']) || !is_array($_tmpb_convers['error_handler']) ) $_tmpb_convers['error_handler'] = array();
foreach($_tmpb_convers['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$_tmpb_convers['error_handler'][$error_handler_key]; ?>
		<span id="errorh"></span>
		<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
			<br />	
		</div>
		<br />		
		<?php } ?>
		
		<form action="pm<?php echo isset($_tmpb_convers['U_MEMBER_ACTION_PM']) ? $_tmpb_convers['U_MEMBER_ACTION_PM'] : ''; ?>" method="post" onsubmit="javascript:return Confirm_pm();">
			<div class="module_position">					
				<div class="module_top_l"></div>		
				<div class="module_top_r"></div>
				<div class="module_top">&bull; <?php echo isset($_tmpb_convers['U_MEMBER_VIEW']) ? $_tmpb_convers['U_MEMBER_VIEW'] : ''; ?> &raquo; <?php echo isset($_tmpb_convers['U_PM_BOX']) ? $_tmpb_convers['U_PM_BOX'] : ''; ?> &raquo; <?php echo isset($_tmpb_convers['U_POST_NEW_CONVERS']) ? $_tmpb_convers['U_POST_NEW_CONVERS'] : ''; ?></div>
				<div class="module_contents">					
					<div style="float:left;"><?php echo isset($this->_var['L_PRIVATE_MSG']) ? $this->_var['L_PRIVATE_MSG'] : ''; ?>: <?php echo isset($_tmpb_convers['PM_POURCENT']) ? $_tmpb_convers['PM_POURCENT'] : ''; ?></div>
					<div style="float:right;"><?php echo isset($_tmpb_convers['U_MARK_AS_READ']) ? $_tmpb_convers['U_MARK_AS_READ'] : ''; ?></div>
					<br /><br />
					
					<table class="module_table">	
						<tr>
							<th style="text-align:center;width:20px;">
								<input type="checkbox" id="checkall" onClick="check_convers(this.checked, 'd');">
							</th>
							<th colspan="2" style="text-align:center;">
								<?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?>
							</th>
							<th style="text-align:center;width:110px;">
								<?php echo isset($this->_var['L_PARTICIPANTS']) ? $this->_var['L_PARTICIPANTS'] : ''; ?>
							</th>
							<th style="text-align:center;width:80px;">
								<?php echo isset($this->_var['L_MESSAGE']) ? $this->_var['L_MESSAGE'] : ''; ?>
							</th>
							<th style="text-align:center;width:120px;">
								<?php echo isset($this->_var['L_LAST_MESSAGE']) ? $this->_var['L_LAST_MESSAGE'] : ''; ?>
							</th>
						</tr>
						
						<?php if( !isset($_tmpb_convers['list']) || !is_array($_tmpb_convers['list']) ) $_tmpb_convers['list'] = array();
foreach($_tmpb_convers['list'] as $list_key => $list_value) {
$_tmpb_list = &$_tmpb_convers['list'][$list_key]; ?>		
						<tr class="row2">
							<td style="width:20px;text-align:center;">
								<input type="checkbox" id="d<?php echo isset($_tmpb_list['INCR']) ? $_tmpb_list['INCR'] : ''; ?>" name="<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>" />
							</td>
							<td class="text_small" style="width:40px;text-align:center;">
								<?php echo isset($_tmpb_list['ANNOUNCE']) ? $_tmpb_list['ANNOUNCE'] : ''; ?>
							</td>
							<td style="padding:4px;">
								<?php echo isset($_tmpb_list['ANCRE']) ? $_tmpb_list['ANCRE'] : ''; ?> <a href="pm<?php echo isset($_tmpb_list['U_CONVERS']) ? $_tmpb_list['U_CONVERS'] : ''; ?>"><?php echo isset($_tmpb_list['TITLE']) ? $_tmpb_list['TITLE'] : ''; ?></a> &nbsp;<span class="text_small">[<?php echo isset($_tmpb_list['U_AUTHOR']) ? $_tmpb_list['U_AUTHOR'] : ''; ?>]</span>
								<br />
								&nbsp;<?php echo isset($_tmpb_list['PAGINATION_PM']) ? $_tmpb_list['PAGINATION_PM'] : ''; ?>
							</td>
							<td style="text-align:center;">
								<?php echo isset($_tmpb_list['U_PARTICIPANTS']) ? $_tmpb_list['U_PARTICIPANTS'] : ''; ?>
							</td>
							<td style="text-align:center">
								<?php echo isset($_tmpb_list['MSG']) ? $_tmpb_list['MSG'] : ''; ?>
							</td>
							<td class="text_small" style="text-align:center;">
								<?php echo isset($_tmpb_list['U_LAST_MSG']) ? $_tmpb_list['U_LAST_MSG'] : ''; ?>
							</td>
						</tr>	
						<?php } ?>
								
						<?php if( !isset($_tmpb_convers['no_pm']) || !is_array($_tmpb_convers['no_pm']) ) $_tmpb_convers['no_pm'] = array();
foreach($_tmpb_convers['no_pm'] as $no_pm_key => $no_pm_value) {
$_tmpb_no_pm = &$_tmpb_convers['no_pm'][$no_pm_key]; ?>	
						<tr>
							<td style="text-align:center;" colspan="6" class="row2">
								<strong><?php echo isset($_tmpb_no_pm['L_NO_PM']) ? $_tmpb_no_pm['L_NO_PM'] : ''; ?></strong>
							</td>
						</tr>
						<?php } ?>	
						<tr>
							<td colspan="6" class="row3">
								<div style="float:left;">&nbsp;<input type="checkbox" id="validc" onClick="check_convers(this.checked, 'd');"> &nbsp;<input type="submit" name="valid" value="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" class="submit" /></div>
								<div style="float:right;"><?php echo isset($_tmpb_convers['PAGINATION']) ? $_tmpb_convers['PAGINATION'] : ''; ?>&nbsp;</div>
							</td>
						</tr>
					</table>					
					<br />
					<table class="module_table">
						<tr> 		
							<td style="width:33%;text-align:center"> 
								<img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/announce.gif" alt="" /> <?php echo isset($this->_var['L_READ']) ? $this->_var['L_READ'] : ''; ?> 
							</td>
							<td style="width:34%;text-align:center"> 
								<img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/announce_track.gif" alt="" /> <?php echo isset($this->_var['L_TRACK']) ? $this->_var['L_TRACK'] : ''; ?>		
							</td>
							<td style="width:33%;text-align:center"> 
								<img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/new_announce.gif" alt="" /> <?php echo isset($this->_var['L_NOT_READ']) ? $this->_var['L_NOT_READ'] : ''; ?>		
							</td>
						</tr>
					</table>
				</div>
				<div class="module_bottom_l"></div>		
				<div class="module_bottom_r"></div>
				<div class="module_bottom">&bull; <?php echo isset($_tmpb_convers['U_MEMBER_VIEW']) ? $_tmpb_convers['U_MEMBER_VIEW'] : ''; ?> &raquo; <?php echo isset($_tmpb_convers['U_PM_BOX']) ? $_tmpb_convers['U_PM_BOX'] : ''; ?> &raquo; <?php echo isset($_tmpb_convers['U_POST_NEW_CONVERS']) ? $_tmpb_convers['U_POST_NEW_CONVERS'] : ''; ?></div>
			</div>
		</form>
		<?php } ?>


		
		<?php if( !isset($this->_block['pm']) || !is_array($this->_block['pm']) ) $this->_block['pm'] = array();
foreach($this->_block['pm'] as $pm_key => $pm_value) {
$_tmpb_pm = &$this->_block['pm'][$pm_key]; ?>
		<div class="msg_position">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top">
				<div style="float:left;">
					&bull; <?php echo isset($_tmpb_pm['U_MEMBER_VIEW']) ? $_tmpb_pm['U_MEMBER_VIEW'] : ''; ?> &raquo; <?php echo isset($_tmpb_pm['U_PM_BOX']) ? $_tmpb_pm['U_PM_BOX'] : ''; ?> &raquo; <?php echo isset($_tmpb_pm['U_TITLE_CONVERS']) ? $_tmpb_pm['U_TITLE_CONVERS'] : ''; ?>
				</div>
				<div style="float:right;">
					<?php echo isset($_tmpb_pm['PAGINATION']) ? $_tmpb_pm['PAGINATION'] : ''; ?>
				</div>
			</div>	
		</div>		
		<?php if( !isset($_tmpb_pm['msg']) || !is_array($_tmpb_pm['msg']) ) $_tmpb_pm['msg'] = array();
foreach($_tmpb_pm['msg'] as $msg_key => $msg_value) {
$_tmpb_msg = &$_tmpb_pm['msg'][$msg_key]; ?>		
		<div class="msg_position">
			<div class="msg_container">				
				<div class="msg_top_row">
					<span id="m<?php echo isset($_tmpb_msg['ID']) ? $_tmpb_msg['ID'] : ''; ?>"></span>
					<div class="msg_pseudo_mbr">
					<?php echo isset($_tmpb_msg['USER_ONLINE']) ? $_tmpb_msg['USER_ONLINE'] : ''; ?> <a class="msg_link_pseudo" href="../member/member<?php echo isset($_tmpb_msg['U_MEMBER_ID']) ? $_tmpb_msg['U_MEMBER_ID'] : ''; ?>"><?php echo isset($_tmpb_msg['USER_PSEUDO']) ? $_tmpb_msg['USER_PSEUDO'] : ''; ?></a>
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="<?php echo isset($_tmpb_msg['U_ANCHOR']) ? $_tmpb_msg['U_ANCHOR'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/ancre.png" alt="<?php echo isset($_tmpb_msg['ID']) ? $_tmpb_msg['ID'] : ''; ?>" /></a> <?php echo isset($_tmpb_msg['DATE']) ? $_tmpb_msg['DATE'] : ''; ?></div>
					<div style="float:right;"><?php echo isset($_tmpb_msg['U_QUOTE']) ? $_tmpb_msg['U_QUOTE'] : ''; ?>&nbsp; <?php echo isset($_tmpb_msg['EDIT']) ? $_tmpb_msg['EDIT'] : '';  echo isset($_tmpb_msg['DEL']) ? $_tmpb_msg['DEL'] : ''; ?>&nbsp;&nbsp;</div>
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
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign">				
				<div class="msg_sign_overflow">
					<?php echo isset($_tmpb_msg['USER_SIGN']) ? $_tmpb_msg['USER_SIGN'] : ''; ?>
				</div>				
				<hr />
				<div style="float:left;">
					<?php echo isset($_tmpb_msg['U_MEMBER_PM']) ? $_tmpb_msg['U_MEMBER_PM'] : ''; echo ' '; echo isset($_tmpb_msg['USER_MAIL']) ? $_tmpb_msg['USER_MAIL'] : ''; echo ' '; echo isset($_tmpb_msg['USER_MSN']) ? $_tmpb_msg['USER_MSN'] : ''; echo ' '; echo isset($_tmpb_msg['USER_YAHOO']) ? $_tmpb_msg['USER_YAHOO'] : ''; echo ' '; echo isset($_tmpb_msg['USER_WEB']) ? $_tmpb_msg['USER_WEB'] : ''; ?>
				</div>
				<div style="float:right;font-size:10px;">
					<?php echo isset($_tmpb_msg['WARNING']) ? $_tmpb_msg['WARNING'] : ''; ?>
				</div>&nbsp;
			</div>	
		</div>
		<?php } ?>
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom">
				<div style="float:left;">
					&bull; <?php echo isset($_tmpb_pm['U_MEMBER_VIEW']) ? $_tmpb_pm['U_MEMBER_VIEW'] : ''; ?> &raquo; <?php echo isset($_tmpb_pm['U_PM_BOX']) ? $_tmpb_pm['U_PM_BOX'] : ''; ?> &raquo; <?php echo isset($_tmpb_pm['U_TITLE_CONVERS']) ? $_tmpb_pm['U_TITLE_CONVERS'] : ''; ?>
				</div>
				<div style="float:right;">
					<?php echo isset($_tmpb_pm['PAGINATION']) ? $_tmpb_pm['PAGINATION'] : ''; ?>
				</div>
			</div>
		</div>
		<br />
		<?php } ?>



		<?php if( !isset($this->_block['show_pm']) || !is_array($this->_block['show_pm']) ) $this->_block['show_pm'] = array();
foreach($this->_block['show_pm'] as $show_pm_key => $show_pm_value) {
$_tmpb_show_pm = &$this->_block['show_pm'][$show_pm_key]; ?>
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">&bull; <?php echo isset($_tmpb_show_pm['U_MEMBER_VIEW']) ? $_tmpb_show_pm['U_MEMBER_VIEW'] : ''; ?> &raquo; <?php echo isset($_tmpb_show_pm['U_PM_BOX']) ? $_tmpb_show_pm['U_PM_BOX'] : ''; ?> &raquo; <?php echo isset($_tmpb_show_pm['U_TITLE_CONVERS']) ? $_tmpb_show_pm['U_TITLE_CONVERS'] : ''; ?></div>
			<div class="module_contents">
				<table class="module_table">
					<tr>
						<th>
							<div style="float:left;"><?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?></div>
							<div style="float:right;"><?php echo isset($_tmpb_show_pm['DATE']) ? $_tmpb_show_pm['DATE'] : ''; ?></div>	
						</th>
					</tr>
					<tr>	
						<td class="row2">
							<?php echo isset($_tmpb_show_pm['CONTENTS']) ? $_tmpb_show_pm['CONTENTS'] : ''; ?><br /><br /><br />
							<hr /><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/pm.png" />
						</td>
					</tr>	
				</table>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">&bull; <?php echo isset($_tmpb_show_pm['U_MEMBER_VIEW']) ? $_tmpb_show_pm['U_MEMBER_VIEW'] : ''; ?> &raquo; <?php echo isset($_tmpb_show_pm['U_PM_BOX']) ? $_tmpb_show_pm['U_PM_BOX'] : ''; ?> &raquo; <?php echo isset($_tmpb_show_pm['U_TITLE_CONVERS']) ? $_tmpb_show_pm['U_TITLE_CONVERS'] : ''; ?></div>
		</div>
		<?php } ?>



		<?php if( !isset($this->_block['post_pm']) || !is_array($this->_block['post_pm']) ) $this->_block['post_pm'] = array();
foreach($this->_block['post_pm'] as $post_pm_key => $post_pm_value) {
$_tmpb_post_pm = &$this->_block['post_pm'][$post_pm_key]; ?>
		<?php if( !isset($_tmpb_post_pm['error_handler']) || !is_array($_tmpb_post_pm['error_handler']) ) $_tmpb_post_pm['error_handler'] = array();
foreach($_tmpb_post_pm['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$_tmpb_post_pm['error_handler'][$error_handler_key]; ?>
		<br />
		<span id="errorh"></span>
		<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
			<br />	
		</div>
		<br />		
		<?php } ?>
		<span id="quote"></span>			
		<div style="font-size: 10px;text-align:center;padding-bottom: 2px;"><?php echo isset($this->_var['L_RESPOND']) ? $this->_var['L_RESPOND'] : ''; ?></div>
		<form action="pm<?php echo isset($_tmpb_post_pm['U_PM_ACTION_POST']) ? $_tmpb_post_pm['U_PM_ACTION_POST'] : ''; ?>" method="post" onsubmit="return check_form_msg();" style="width:80%;margin:auto">						
			<?php $this->tpl_include('handle_bbcode'); ?>		
			<label><textarea type="text" class="post" rows="15" cols="66" id="contents" name="contents"><?php echo isset($_tmpb_post_pm['CONTENTS']) ? $_tmpb_post_pm['CONTENTS'] : ''; ?></textarea> </label>
			<div style="padding:17px;">					
				<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
					<input type="submit" name="pm" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
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
			</div>				
		</form>
		<?php } ?>

		

		<?php if( !isset($this->_block['edit_pm']) || !is_array($this->_block['edit_pm']) ) $this->_block['edit_pm'] = array();
foreach($this->_block['edit_pm'] as $edit_pm_key => $edit_pm_value) {
$_tmpb_edit_pm = &$this->_block['edit_pm'][$edit_pm_key]; ?>
		<form action="pm<?php echo isset($_tmpb_edit_pm['U_ACTION_EDIT']) ? $_tmpb_edit_pm['U_ACTION_EDIT'] : ''; ?>" method="post" onsubmit="return check_form_convers();">
			<div class="module_position">					
				<div class="module_top_l"></div>		
				<div class="module_top_r"></div>
				<div class="module_top">&bull; <?php echo isset($_tmpb_edit_pm['U_MEMBER_VIEW']) ? $_tmpb_edit_pm['U_MEMBER_VIEW'] : ''; ?> &raquo; <?php echo isset($_tmpb_edit_pm['U_PM_BOX']) ? $_tmpb_edit_pm['U_PM_BOX'] : ''; ?></div>
				<div class="module_contents">	
					<?php if( !isset($_tmpb_edit_pm['show_pm']) || !is_array($_tmpb_edit_pm['show_pm']) ) $_tmpb_edit_pm['show_pm'] = array();
foreach($_tmpb_edit_pm['show_pm'] as $show_pm_key => $show_pm_value) {
$_tmpb_show_pm = &$_tmpb_edit_pm['show_pm'][$show_pm_key]; ?>		
					<table class="module_table">
						<tr>
							<th>
								<div style="float:left;"><?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?></div>
								<div style="float:right;"><?php echo isset($_tmpb_show_pm['DATE']) ? $_tmpb_show_pm['DATE'] : ''; ?></div>		
							</th>
						</tr>
						<tr>	
							<td class="row2">														
								<?php echo isset($_tmpb_show_pm['CONTENTS']) ? $_tmpb_show_pm['CONTENTS'] : ''; ?>
								<br /><br /><br />
								<hr /><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/pm.png" />
							</td>
						</tr>	
					</table>
					<?php } ?>
										
					<div class="fieldset_content">
						<fieldset>
							<legend><?php echo isset($this->_var['L_EDIT']) ? $this->_var['L_EDIT'] : ''; ?></legend>
							<p><?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?></p>
							<?php if( !isset($_tmpb_edit_pm['title']) || !is_array($_tmpb_edit_pm['title']) ) $_tmpb_edit_pm['title'] = array();
foreach($_tmpb_edit_pm['title'] as $title_key => $title_value) {
$_tmpb_title = &$_tmpb_edit_pm['title'][$title_key]; ?>
							<dl>
								<dt><label for="title">* <?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?></label></dt>
								<dd><label><input type="text" size="50" maxlength="100" id="title" name="title" value="<?php echo isset($_tmpb_title['TITLE']) ? $_tmpb_title['TITLE'] : ''; ?>" class="text" /></label></dd>
							</dl>
							<?php } ?>
							<br />
							<label for="contents">* <?php echo isset($this->_var['L_MESSAGE']) ? $this->_var['L_MESSAGE'] : ''; ?></label>
							<?php $this->tpl_include('handle_bbcode'); ?>
							<textarea type="text" rows="25" cols="66" id="contents" name="contents"><?php echo isset($_tmpb_edit_pm['CONTENTS']) ? $_tmpb_edit_pm['CONTENTS'] : ''; ?></textarea>
							<br />
						</fieldset>
						
						<fieldset class="fieldset_submit">
							<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
							<input type="submit" name="<?php echo isset($this->_var['SUBMIT_NAME']) ? $this->_var['SUBMIT_NAME'] : ''; ?>" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
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
					</div>	
				</div>
				<div class="module_bottom_l"></div>		
				<div class="module_bottom_r"></div>
				<div class="module_bottom">&bull; <?php echo isset($_tmpb_edit_pm['U_MEMBER_VIEW']) ? $_tmpb_edit_pm['U_MEMBER_VIEW'] : ''; ?> &raquo; <?php echo isset($_tmpb_edit_pm['U_PM_BOX']) ? $_tmpb_edit_pm['U_PM_BOX'] : ''; ?></div>
			</div>
		</form>
		<?php } ?>

		

		<?php if( !isset($this->_block['post_convers']) || !is_array($this->_block['post_convers']) ) $this->_block['post_convers'] = array();
foreach($this->_block['post_convers'] as $post_convers_key => $post_convers_value) {
$_tmpb_post_convers = &$this->_block['post_convers'][$post_convers_key]; ?>		
		<form action="pm<?php echo isset($_tmpb_post_convers['U_ACTION_CONVERS']) ? $_tmpb_post_convers['U_ACTION_CONVERS'] : ''; ?>" method="post" onsubmit="return check_form_convers();">
			<div class="module_position">					
					<div class="module_top_l"></div>		
					<div class="module_top_r"></div>
					<div class="module_top">&bull; <?php echo isset($_tmpb_post_convers['U_MEMBER_VIEW']) ? $_tmpb_post_convers['U_MEMBER_VIEW'] : ''; ?> &raquo; <?php echo isset($_tmpb_post_convers['U_PM_BOX']) ? $_tmpb_post_convers['U_PM_BOX'] : ''; ?></div>
					<div class="module_contents">	
						<?php if( !isset($_tmpb_post_convers['error_handler']) || !is_array($_tmpb_post_convers['error_handler']) ) $_tmpb_post_convers['error_handler'] = array();
foreach($_tmpb_post_convers['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$_tmpb_post_convers['error_handler'][$error_handler_key]; ?>
						<br />
						<span id="errorh"></span>
						<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
							<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
							<br />	
						</div>
						<br />		
						<?php } ?>
						
						<?php if( !isset($_tmpb_post_convers['show_convers']) || !is_array($_tmpb_post_convers['show_convers']) ) $_tmpb_post_convers['show_convers'] = array();
foreach($_tmpb_post_convers['show_convers'] as $show_convers_key => $show_convers_value) {
$_tmpb_show_convers = &$_tmpb_post_convers['show_convers'][$show_convers_key]; ?>		
						<table class="module_table">
							<tr>
								<th>
									<div style="float:left;"><?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?></div>
									<div style="float:right;"><?php echo isset($_tmpb_show_convers['DATE']) ? $_tmpb_show_convers['DATE'] : ''; ?></div>		
								</th>
							</tr>
							<tr>	
								<td class="row2">														
									<?php echo isset($_tmpb_show_convers['CONTENTS']) ? $_tmpb_show_convers['CONTENTS'] : ''; ?>
									<br /><br /><br />
									<hr /><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/pm.png" />
								</td>
							</tr>	
						</table>
						<?php } ?>	
						
						<div class="fieldset_content">
							<fieldset>
								<legend><?php echo isset($this->_var['L_POST_NEW_CONVERS']) ? $this->_var['L_POST_NEW_CONVERS'] : ''; ?></legend>
								<p><?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?></p>
								<?php if( !isset($_tmpb_post_convers['user_id_dest']) || !is_array($_tmpb_post_convers['user_id_dest']) ) $_tmpb_post_convers['user_id_dest'] = array();
foreach($_tmpb_post_convers['user_id_dest'] as $user_id_dest_key => $user_id_dest_value) {
$_tmpb_user_id_dest = &$_tmpb_post_convers['user_id_dest'][$user_id_dest_key]; ?>
								<dl>
									<dt><label for="login">* <?php echo isset($this->_var['L_RECIPIENT']) ? $this->_var['L_RECIPIENT'] : ''; ?></label></dt>
									<dd><label>
										<input type="text" size="20 maxlenght="25" id="login" name="login" value="<?php echo isset($_tmpb_post_convers['LOGIN']) ? $_tmpb_post_convers['LOGIN'] : ''; ?>" class="text" />
										<input value="<?php echo isset($this->_var['L_SEARCH']) ? $this->_var['L_SEARCH'] : ''; ?>" onclick="XMLHttpRequest_search(this.form);" type="button" class="submit">
										<div id="xmlhttprequest_result_search" style="display:none;" class="xmlhttprequest_result_search"></div>
										<?php if( !isset($_tmpb_user_id_dest['search']) || !is_array($_tmpb_user_id_dest['search']) ) $_tmpb_user_id_dest['search'] = array();
foreach($_tmpb_user_id_dest['search'] as $search_key => $search_value) {
$_tmpb_search = &$_tmpb_user_id_dest['search'][$search_key]; ?>
											<?php echo isset($_tmpb_search['RESULT']) ? $_tmpb_search['RESULT'] : ''; ?>
										<?php } ?>
									</label></dd>
								</dl>		
								<?php } ?>
								<dl>
									<dt><label for="title">* <?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?></label></dt>
									<dd><label><input type="text" size="50" maxlength="100" id="title" name="title" value="<?php echo isset($_tmpb_post_convers['TITLE']) ? $_tmpb_post_convers['TITLE'] : ''; ?>" class="text" /></label></dd>
								</dl>
								<br />
								<label for="contents">* <?php echo isset($this->_var['L_MESSAGE']) ? $this->_var['L_MESSAGE'] : ''; ?></label>
								<?php $this->tpl_include('handle_bbcode'); ?>
								<textarea type="text" rows="25" cols="66" id="contents" name="contents"><?php echo isset($_tmpb_edit_pm['CONTENTS']) ? $_tmpb_edit_pm['CONTENTS'] : ''; ?></textarea>
								<br />
							</fieldset>
							
							<fieldset class="fieldset_submit">
								<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
								<input type="submit" name="convers" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
									&nbsp;&nbsp; 
									<script type="text/javascript">
									<!--				
									document.write('<input value="<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>" onclick="XMLHttpRequest_preview(this.form);" type="button" class="submit" />');
									-->
									</script>
									<noscript><input value="<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>" type="submit" name="prw_convers" class="submit" /></noscript>
									&nbsp;&nbsp; 
									<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />
							</fieldset>	
						</div>
					</div>
					<div class="module_bottom_l"></div>		
					<div class="module_bottom_r"></div>
					<div class="module_bottom">&bull; <?php echo isset($_tmpb_post_convers['U_MEMBER_VIEW']) ? $_tmpb_post_convers['U_MEMBER_VIEW'] : ''; ?> &raquo; <?php echo isset($_tmpb_post_convers['U_PM_BOX']) ? $_tmpb_post_convers['U_PM_BOX'] : ''; ?></div>
				</div>
			</div>
		</form>
		<?php } ?>
		