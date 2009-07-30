		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_NEWSLETTER} </li>
				<li>
					<a href="admin_newsletter.php"><img src="newsletter.png" alt="" /></a>
					<br />
					<a href="admin_newsletter.php" class="quick_link">{L_SEND_NEWSLETTER}</a>
				</li>
				<li>
					<a href="admin_newsletter_config.php"><img src="newsletter.png" alt="" /></a>
					<br />
					<a href="admin_newsletter_config.php" class="quick_link">{L_CONFIG_NEWSLETTER}</a>
				</li>
				<li>
					<a href="admin_newsletter.php?member_list=1"><img src="newsletter.png" alt="" /></a>
					<br />
					<a href="admin_newsletter.php?member_list=1" class="quick_link">{L_USER_LIST}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
			# START select_type #

			<form action="admin_newsletter.php?token={TOKEN}" name="form" method="get">	
				<table  class="module_table">
					<tr> 
						<th colspan="3">
							{select_type.L_SELECT_TYPE}
						</th>
					</tr>
					<tr> 
						<td class="row1" style="text-align:center;">
							<label><input type="radio" id="type" name="type" checked="checked" value="text" />
							<strong>{select_type.L_SELECT_TYPE_TEXT}</strong></label>
							<br />{select_type.L_SELECT_TYPE_EXPLAIN_TEXT}				
						</td>
						<td class="row1" style="text-align:center;">
							<label><input type="radio" id="type" name="type" value="bbcode" />
							<strong>{select_type.L_SELECT_TYPE_BBCODE}</strong></label>
							<br />{select_type.L_SELECT_TYPE_EXPLAIN_BBCODE}				
						</td>
						<td class="row1" style="text-align:center;">
							<label><input type="radio" id="type" name="type" value="html" />
							<strong>{select_type.L_SELECT_TYPE_HTML}</strong></label>
							<br />{select_type.L_SELECT_TYPE_EXPLAIN_HTML}				
						</td>
					</tr>
				</table>
				
				<br /><br />	
				
				<fieldset class="fieldset_submit">
					<legend>{select_type.L_NEXT}</legend>
					<input type="submit" value="{select_type.L_NEXT}" class="submit" />
				</fieldset>
			</form>

			# END select_type #

			# START write #
			<script type="text/javascript">
			<!--
			function check_form(){
				if(document.getElementById('title').value == "") {
					alert("{L_REQUIRE_TITLE}");
					return false;
			    }
				if(document.getElementById('contents').value == "") {
					alert("{L_REQUIRE_TEXT}");
					return false;
			    }
				if(document.getElementById('mail').value == "") {
					alert("{L_REQUIRE_MAIL}");
					return false;
			    }

				return true;
			}

			-->
			</script>
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
			<span id="errorh"></span>
				<div class="{ERRORH_CLASS}">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
				</div>
			</div>
			# ENDIF #
			
			<form action="admin_newsletter.php?type={write.TYPE}&amp;token={TOKEN}" name="form" method="post" style="margin:auto;" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_WRITE_TYPE}</legend>
					<p>{L_NBR_SUBSCRIBERS} <strong>{write.NBR_SUBSCRIBERS}</strong></p>
					# START write.bbcode_explain #					
					<div class="notice">
						{write.bbcode_explain.L_WARNING}
					</div>
					<br />
					# END write.bbcode_explain #
					
					<dl>
						<dt><label for="title">* {L_TITLE}</label></dt>
						<dd><label><input type="text" maxlength="200" size="50" id="title" name="title" value="{write.TITLE}" class="text" /></label></dd>
					</dl>
					<br />
					<label for="contents">* {L_MESSAGE}</label>
					{KERNEL_EDITOR}
					<label><textarea rows="25" cols="40" id="contents" name="contents">{write.MESSAGE}</textarea></label>
					<br /><br />
					<p>{write.SUBSCRIBE_LINK}</p>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_SEND}</legend>
					<input type="hidden" name="send" value="1" />
					{write.PREVIEW_BUTTON}	
					<input type="submit" name="send_test" value="{L_NEWSLETTER_TEST}" class="submit" />		
					<input type="submit" value="{L_SEND}" class="submit" />	
				</fieldset>
			</form>

			# END write #

			
			# START end #

			<table class="module_table">
				<tr> 
					<th>
						{L_NEWSLETTER}
					</th>
				</tr>
				<tr> 
					<td class="row1" style="text-align:center;">
						# IF C_ERROR_HANDLER #
						<span id="errorh"></span>
						<div class="{ERRORH_CLASS}">
							<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
						</div>
						# ENDIF #
						
						<br />
						<a href="admin_newsletter.php">{L_BACK}</a> / <a href="../newsletter/newsletter.php">{L_ARCHIVES}</a>
					</td>
				</tr>
			</table>

			# END end #

			
			# START config #
			
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
				</div>
			</div>
			# ENDIF #
			<form action="admin_newsletter_config.php?token={TOKEN}" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_CONFIG_NEWSLETTER}</legend>
					<dl>
						<dt><label for="sender_mail">{L_SENDER_MAIL}</label></dt>
						<dd><label><input type="text" value="{SENDER_MAIL}" name="sender_mail" id="sender_mail" class="text" size="40" maxlength="100" /></label></dd>
					</dl>
					<dl>
						<dt><label for="newsletter_name">{L_NEWSLETTER_NAME}</label></dt>
						<dd><label><input type="text" value="{NEWSLETTER_NAME}" name="newsletter_name" id="newsletter_name" class="text" size="40" maxlength="100" /></label></dd>
					</dl>
				</fieldset>	
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>	
			</form>

			# END config #

			
			# START member_list #
			
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
			<span id="errorh"></span>
				<div class="{ERRORH_CLASS}">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
				</div>
			</div>
			# ENDIF #
			
			<table class="module_table" style="width:50%;">
				<tr> 
					<th colspan="2">
						{L_USER_LIST}
					</th>
				</tr>
				<tr> 
					<td class="row2" style="text-align:center;">
						{L_MAIL}
					</td>
					<td class="row2" style="text-align:center;">
						{L_DELETE}
					</td>
				</tr>
				# START member_list.line #
				<tr>
					<td class="row1" style="text-align:center;">
						{member_list.line.MAIL}
					</td>
					<td class="row1" style="text-align:center;">
						<a href="{member_list.line.U_DELETE}" onclick="javascript: return confirm('{L_CONFIRM_DELETE}');"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="" /></a>
					</td>
				</tr>
				# END member_list.line #
			</table>

			# END member_list #
		</div>
