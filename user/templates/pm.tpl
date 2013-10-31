		<script type="text/javascript">
		<!--
		function check_form_convers(){
			# IF C_BBCODE_TINYMCE_MODE #
				tinyMCE.triggerSave();
			# ENDIF #
			
			if(document.getElementById('login').value == "") {
				alert("{L_REQUIRE_RECIPIENT}");
				return false;
		    }
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_MESSAGE}");
				return false;
		    }
			if(document.getElementById('title').value == "") {
				alert("{L_REQUIRE_TITLE}");
				return false;
		    }
			return true;
		}
		function check_form_pm(){
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_MESSAGE}");
				return false;
		    }
			return true;
		}
		function Confirm_pm() {
			return confirm("{L_DELETE_MESSAGE}");
		}
		-->
		</script>

		
		# START convers #
		<script type="text/javascript">
		<!--
			function check_convers(status, id)
			{
				var i;
				for(i = 0; i < {convers.NBR_PM}; i++)
				{	
					if( document.getElementById(id + i) ) 
						document.getElementById(id + i).checked = status;
				}
				document.getElementById('checkall').checked = status;
				document.getElementById('validc').checked = status;
			}	 
		-->
		</script>
		# INCLUDE message_helper #
		
		<form action="pm{convers.U_USER_ACTION_PM}" method="post" onsubmit="javascript:return Confirm_pm();">
			<section>					
				<header>
					<h1>&bull; {convers.U_USER_VIEW} &raquo; {convers.U_PM_BOX} &raquo; {convers.U_POST_NEW_CONVERS}</h1>
				</header>
				<div class="content">					
					<div style="float:left;">{L_PRIVATE_MSG}: {convers.PM_POURCENT}</div>
					<div style="float:right;"><img src="../templates/{THEME}/images/read_mini.png" alt="" class="valign_middle" /> {convers.U_MARK_AS_READ}</div>
					<br /><br />
					
					<table>
						<thead>
							<tr>
								<th style="width:20px;">
									<input type="checkbox" id="checkall" onclick="check_convers(this.checked, 'd');">
								</th>
								<th colspan="2">
									{L_TITLE}
								</th>
								<th>
									{L_PARTICIPANTS}
								</th>
								<th>
									{L_MESSAGE}
								</th>
								<th>
									{L_LAST_MESSAGE}
								</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th colspan="6">
									<div style="float:left;">&nbsp;<input type="checkbox" id="validc" onclick="check_convers(this.checked, 'd');" /> &nbsp;<button type="submit" name="valid" value="true">{L_DELETE}</button></div>
									<div style="float:right;">{convers.PAGINATION}&nbsp;</div>
								</th>
							</tr>
						</tfoot>
						<tbody>
							# START convers.list #		
							<tr>
								<td style="width:20px;">
									<input type="checkbox" id="d{convers.list.INCR}" name="{convers.list.ID}">
								</td>
								<td style="width:40px;">
									<img src="{convers.list.ANNOUNCE}.png" alt="" />
								</td>
								<td class="no-separator">
									{convers.list.ANCRE} <a href="pm{convers.list.U_CONVERS}">{convers.list.TITLE}</a> &nbsp;<span class="smaller">[{convers.list.U_AUTHOR}]</span>
									# IF convers.list.PAGINATION_PM #
										<br/>
										{convers.list.PAGINATION_PM}
									# ENDIF #
								</td>
								<td>
									{convers.list.U_PARTICIPANTS}
								</td>
								<td>
									{convers.list.MSG}
								</td>
								<td class="smaller">
									{convers.list.U_LAST_MSG}
								</td>
							</tr>	
							# END convers.list #
									
							# START convers.no_pm #	
							<tr>
								<td colspan="6">
									<span class="text_strong">{convers.no_pm.L_NO_PM}</span>
								</td>
							</tr>
							# END convers.no_pm #
						</tbody>
					</table>					
					<br />
					<table>
						<tr> 		
							<td style="width:33%;"> 
								<img class="valign_middle" src="../templates/{THEME}/images/announce.png" alt="" /> {L_READ} 
							</td>
							<td style="width:34%;" class="no-separator">  
								<img class="valign_middle" src="../templates/{THEME}/images/announce_track.png" alt="" /> {L_TRACK}		
							</td>
							<td style="width:33%;" class="no-separator">  
								<img class="valign_middle" src="../templates/{THEME}/images/new_announce.png" alt="" /> {L_NOT_READ}		
							</td>
						</tr>
					</table>
				</div>
				<footer>&bull; {convers.U_USER_VIEW} &raquo; {convers.U_PM_BOX} &raquo; {convers.U_POST_NEW_CONVERS}</footer>
			</section>
		</form>
		# END convers #


		
		# START pm #
		<div class="msg_position">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top">
				<div style="float:left;">
					&bull; {pm.U_USER_VIEW} &raquo; {pm.U_PM_BOX} &raquo; {pm.U_TITLE_CONVERS}
				</div>
				<div style="float:right;">
					{pm.PAGINATION}
				</div>
			</div>	
		</div>		
		# START pm.msg #		
		<div class="msg_position">
			<div class="msg_container{pm.msg.CLASS_COLOR}">				
				<div class="msg_top_row">
					<span id="m{pm.msg.ID}"></span>
					<div class="msg_pseudo_mbr">
					{pm.msg.USER_ONLINE} <a class="msg_link_pseudo" href="{pm.msg.U_USER_PROFILE}">{pm.msg.USER_PSEUDO}</a>
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="{pm.msg.U_ANCHOR}"><img src="../templates/{THEME}/images/ancre.png" alt="{pm.msg.ID}" /></a> {pm.msg.DATE}</div>
					<div style="float:right;">
						{pm.msg.U_QUOTE}
						# IF pm.msg.C_MODERATION_TOOLS #
						<a href="pm.php?edit={pm.msg.ID}" title="{L_EDIT}" class="pbt-icon-edit">
						<a href="pm.php?del={pm.msg.ID}&amp;token={TOKEN}" title="{L_DELETE}" class="pbt-icon-delete" data-confirmation="delete-element"></a>
						&nbsp;&nbsp;
						# ENDIF #
					</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;">{pm.msg.USER_RANK}</p>
						<p style="text-align:center;">{pm.msg.USER_IMG_ASSOC}</p>
						<p style="text-align:center;">{pm.msg.USER_AVATAR}</p>
						<p style="text-align:center;">{pm.msg.USER_GROUP}</p>
						{pm.msg.USER_SEX}
						{pm.msg.USER_DATE}<br />
						{pm.msg.USER_MSG}<br />
						{pm.msg.USER_LOCAL}
					</div>
					<div class="msg_contents{pm.msg.CLASS_COLOR}">
						<div class="msg_contents_overflow">
							{pm.msg.CONTENTS}
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign{pm.msg.CLASS_COLOR}">				
				<div class="msg_sign_overflow">
					{pm.msg.USER_SIGN}
				</div>				
				<hr />
				<div style="float:left;">
					{pm.msg.U_USER_PM} {pm.msg.USER_MAIL} {pm.msg.USER_MSN} {pm.msg.USER_YAHOO} {pm.msg.USER_WEB}
				</div>
				<div style="float:right;font-size:10px;">
					{pm.msg.WARNING}
				</div>&nbsp;
			</div>	
		</div>
		# END pm.msg #
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom">
				<div style="float:left;">
					&bull; {pm.U_USER_VIEW} &raquo; {pm.U_PM_BOX} &raquo; {pm.U_TITLE_CONVERS}
				</div>
				<div style="float:right;">
					{pm.PAGINATION}
				</div>
			</div>
		</div>
		<br />
		# END pm #



		# START show_pm #
		<section>					
			<header>
				<h1>&bull; {show_pm.U_USER_VIEW} &raquo; {show_pm.U_PM_BOX} &raquo; {show_pm.U_TITLE_CONVERS}</h1>
			</header>
			<div class="content">
				<table class="module_table">
					<tr>
						<th>
							<div style="float:left;">{L_PREVIEW}</div>
							<div style="float:right;">{show_pm.DATE}</div>	
						</th>
					</tr>
					<tr>	
						<td class="row2">
							{show_pm.CONTENTS}<br /><br /><br />
							<hr / class="small-button">MP
						</td>
					</tr>	
				</table>
			</div>
			<footer>&bull; {show_pm.U_USER_VIEW} &raquo; {show_pm.U_PM_BOX} &raquo; {show_pm.U_TITLE_CONVERS}</footer>
		</section>
		# END show_pm #



		# START post_pm #
		# INCLUDE message_helper #
		<span id="quote"></span>			
		<form action="pm{post_pm.U_PM_ACTION_POST}" method="post" onsubmit="return check_form_msg();" style="width:80%;margin:auto">						
			<div>					
				<div style="font-size: 10px;text-align:center;padding-bottom: 2px;">{L_RESPOND}</div>
				{KERNEL_EDITOR}		
				<textarea class="post" rows="15" cols="66" id="contents" name="contents">{post_pm.CONTENTS}</textarea>
				<fieldset class="fieldset_submit" style="padding-top:17px;margin-bottom:0px;">
				<legend>{L_SUBMIT}</legend>
					<button type="submit" name="pm" value="true">{L_SUBMIT}</button>
					&nbsp;&nbsp;
					<button type="button" name="prw" id="prw_pm" onclick="XMLHttpRequest_preview();">{L_PREVIEW}</button>			
					&nbsp;&nbsp;
					<button type="reset" value="true">{L_RESET}</button>				
				</fieldset>	
			</div>				
		</form>
		# END post_pm #

		

		# START edit_pm #
		<form action="pm{edit_pm.U_ACTION_EDIT}" method="post" onsubmit="return check_form_convers();">
			<section>					
				<header>
					<h1>&bull; {edit_pm.U_USER_VIEW} &raquo; {edit_pm.U_PM_BOX}</h1>
				</header>
				<div class="content">	
					# START edit_pm.show_pm #		
					<table class="module_table">
						<tr>
							<th>
								<div style="float:left;">{L_PREVIEW}</div>
								<div style="float:right;">{edit_pm.show_pm.DATE}</div>		
							</th>
						</tr>
						<tr>	
							<td class="row2">														
								{edit_pm.show_pm.CONTENTS}
								<br /><br /><br />
								<hr / class="small-button">MP
							</td>
						</tr>	
					</table>
					# END edit_pm.show_pm #
					
					<div class="fieldset_content">
						<fieldset>
							<legend>{L_EDIT}</legend>
							<p>{L_REQUIRE}</p>
							# START edit_pm.title #
							<div class="form-element">
								<label for="title">* {L_TITLE}</label>
								<div class="form-field"><label><input type="text" size="50" maxlength="100" id="title" name="title" value="{edit_pm.title.TITLE}" class="text"></label></div>
							</div>
							# END edit_pm.title #
							<div class="form-element-textarea">
								<label for="contents">* {L_MESSAGE}</label>
								{KERNEL_EDITOR}
								<textarea rows="25" cols="66" id="contents" name="contents">{edit_pm.CONTENTS}</textarea>
							</div>
						</fieldset>
						
						<fieldset class="fieldset_submit">
							<legend>{L_SUBMIT}</legend>
							<input type="submit" name="{SUBMIT_NAME}" value="{L_SUBMIT}" class="submit">
							&nbsp;&nbsp; 
							<button type="button" name="prw" id="prw_pm" onclick="XMLHttpRequest_preview();">{L_PREVIEW}</button>											
							&nbsp;&nbsp; 
							<button type="reset" value="true">{L_RESET}</button>
						</fieldset>	
					</div>	
				</div>
				<footer>&bull; {edit_pm.U_USER_VIEW} &raquo; {edit_pm.U_PM_BOX}</footer>
			</section>
		</form>
		# END edit_pm #

		

		# START post_convers #		
		<form action="pm{post_convers.U_ACTION_CONVERS}" method="post" onsubmit="return check_form_convers();">
			<section>					
				<header>
					<h1>&bull; {post_convers.U_USER_VIEW} &raquo; {post_convers.U_PM_BOX}</h1>
				</header>
				<div class="content">	
					# INCLUDE message_helper #
					
					# START post_convers.show_convers #		
					<table class="module_table">
						<tr>
							<th>
								<div style="float:left;">{L_PREVIEW}</div>
								<div style="float:right;">{post_convers.show_convers.DATE}</div>		
							</th>
						</tr>
						<tr>	
							<td class="row2">														
								{post_convers.show_convers.CONTENTS}
								<br /><br /><br />
								<hr / class="small-button">MP
							</td>
						</tr>	
					</table>
					# END post_convers.show_convers #	
					
					<div class="fieldset_content">
						<fieldset>
							<legend>{L_POST_NEW_CONVERS}</legend>
							<p>{L_REQUIRE}</p>
							# START post_convers.user_id_dest #
							<div class="form-element">
								<label for="login">* {L_RECIPIENT}</label>
								<div class="form-field">
									<label>
										<input type="text" size="20" maxlength="25" id="login" name="login" value="{post_convers.LOGIN}" class="text">
										<span id="search_img"></span> <input value="{L_SEARCH}" onclick="XMLHttpRequest_search_members('', '{THEME}', 'insert_member', '{L_REQUIRE_RECIPIENT}');" type="button" class="submit">								
									</label>
									<div id="xmlhttprequest_result_search" style="display:none;" class="xmlhttprequest_result_search"></div>
									# START post_convers.user_id_dest.search #
										{post_convers.user_id_dest.search.RESULT}
									# END post_convers.user_id_dest.search #
								</div>
							</div>		
							# END post_convers.user_id_dest #
							<div class="form-element">
								<label for="title">* {L_TITLE}</label>
								<div class="form-field"><label><input type="text" size="50" maxlength="100" id="title" name="title" value="{post_convers.TITLE}" class="text"></label></div>
							</div>
							<div class="form-element-textarea">
								<label for="contents">* {L_MESSAGE}</label>
								{KERNEL_EDITOR}
								<textarea rows="25" cols="66" id="contents" name="contents">{CONTENTS}</textarea>
							</div>
						</fieldset>
						
						<fieldset class="fieldset_submit">
							<legend>{L_SUBMIT}</legend>
							<button type="submit" name="convers" value="true">{L_SUBMIT}</button>
								&nbsp;&nbsp; 
								<button type="button" name="prw_convers" id="prw_convers_pm" onclick="XMLHttpRequest_preview();">{L_PREVIEW}</button>
								&nbsp;&nbsp; 
								<button type="reset" value="true">{L_RESET}</button>
						</fieldset>	
					</div>
				</div>
				<footer>&bull; {post_convers.U_USER_VIEW} &raquo; {post_convers.U_PM_BOX}</footer>
			</section>
		</form>
		# END post_convers #