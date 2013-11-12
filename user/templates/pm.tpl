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
					<h1>&bull; {convers.U_USER_VIEW} &raquo; {convers.U_PM_BOX}</h1>
				</header>
				<div class="content">					
					<div style="float:right;">{L_PRIVATE_MSG}: {convers.PM_POURCENT}</div>
					<br /><br />
					<menu class="dynamic_menu group center">
						<ul>
							<li >
								<a href="{convers.U_POST_NEW_CONVERS}"><i class="icon-plus"></i> {convers.L_POST_NEW_CONVERS}</a>
							</li>
							<li>
								<a href="{convers.U_MARK_AS_READ}"><i class="icon-eraser"></i> {convers.L_MARK_AS_READ}</a>
							</li>
						</ul>
					</menu>
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
				<footer></footer>
			</section>
		</form>
		# END convers #

		
		# START pm #
		<section>
			<header>
				<h1>{pm.U_PM_BOX} : {pm.U_TITLE_CONVERS}</h1>
			</header>
			
			<div class="content">
				<div style="float:right;">
					{pm.PAGINATION}
				</div>
			
			# START pm.msg #	
				<article id="m{pm.msg.ID}" class="message">
					<div class="message-user_infos">
						<div class="message-pseudo">
							# IF pm.msg.C_VISITOR #
								<span>{pm.msg.PSEUDO}</span>
							# ELSE #
								<a href="{pm.msg.U_PROFILE}" class="{pm.msg.LEVEL_CLASS}" # IF pm.msg.C_GROUP_COLOR # style="color:{pm.msg.GROUP_COLOR}" # ENDIF #>
									{pm.msg.PSEUDO}
								</a>
							# ENDIF #
						</div>
						<div class="message-level">{pm.msg.L_LEVEL}</div>
						<img src="{pm.msg.USER_AVATAR}" title="{pm.msg.USER_PSEUDO}" class="message-avatar">
					</div>
					<div class="message-content">
						<div class="message-date">
							<span class="actions">
								<a href="#m{pm.msg.ID}">\#{pm.msg.ID}</a>
								# IF pm.msg.C_MODERATION_TOOLS #
								<a href="pm.php?edit={pm.msg.ID}" title="{L_EDIT}" class="icon-edit"></a>
								<a href="pm.php?del={pm.msg.ID}&amp;token={TOKEN}" title="{L_DELETE}" class="icon-delete" data-confirmation="delete-element"></a>
								# ENDIF #
							</span>
							<span>{pm.msg.DATE}</span>
						</div>
						<div class="message-message">
							<div class="message-containt">{pm.msg.CONTENTS}</div>
						</div>
					</div>
				</article>
			# END pm.msg #
			</div>
			<footer>
				<div style="float:right;">{pm.PAGINATION}</div>
			</footer>
		</section>
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
							<hr / class="basic-button smaller">MP
						</td>
					</tr>	
				</table>
			</div>
			<footer></footer>
		</section>
		# END show_pm #


		# START post_pm #
		# INCLUDE message_helper #
		<span id="quote"></span>			
		<form action="pm{post_pm.U_PM_ACTION_POST}" method="post" onsubmit="return check_form_msg();" style="width:80%;margin:auto">						
			<legend>{L_RESPOND}</legend>				
			{KERNEL_EDITOR}		
			<textarea class="post" rows="15" cols="66" id="contents" name="contents">{post_pm.CONTENTS}</textarea>
			<div class="center">
				<button type="submit" name="pm" value="true">{L_SUBMIT}</button>
				<button type="button" name="prw" id="prw_pm" onclick="XMLHttpRequest_preview();">{L_PREVIEW}</button>			
				<button type="reset" value="true">{L_RESET}</button>				
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
					<!--  
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
								<hr / class="basic-button smaller">MP
							</td>
						</tr>	
					</table>
					# END edit_pm.show_pm #
					-->
					<div class="fieldset_content">
						<fieldset>
							<legend>{L_EDIT}</legend>
							<p>{L_REQUIRE}</p>
							# START edit_pm.title #
							<div class="form-element">
								<label for="title">* {L_TITLE}</label>
								<div class="form-field"><label><input type="text" size="50" maxlength="100" id="title" name="title" value="{edit_pm.title.TITLE}"></label></div>
							</div>
							# END edit_pm.title #
							<div class="form-element-textarea">
								<label for="contents">* {L_MESSAGE}</label>
								{KERNEL_EDITOR}
								<textarea rows="25" cols="66" id="contents" name="contents">{edit_pm.CONTENTS}</textarea>
							</div>
						</fieldset>
						
						<div class="center">
							<button type="submit" name="{SUBMIT_NAME}" value="{L_SUBMIT}">{L_SUBMIT}</button>	
							<button type="button" name="prw" id="prw_pm" onclick="XMLHttpRequest_preview();">{L_PREVIEW}</button>											
							<button type="reset" value="true">{L_RESET}</button>
						</div>	
					</div>	
				</div>
				<footer></footer>
			</section>
		</form>
		# END edit_pm #


		# START post_convers #		
		<form action="pm{post_convers.U_ACTION_CONVERS}" method="post" onsubmit="return check_form_convers();">
			<section>					
				<header>
					<h1>{post_convers.U_PM_BOX}</h1>
				</header>
				<div class="content">	
					# INCLUDE message_helper #
					
					<!--  
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
								<hr / class="basic-button smaller">MP
							</td>
						</tr>	
					</table>
					# END post_convers.show_convers #	
					-->
					
					<div class="fieldset_content">
						<fieldset>
							<legend>{L_POST_NEW_CONVERS}</legend>
							<p>{L_REQUIRE}</p>
							# START post_convers.user_id_dest #
							<div class="form-element">
								<label for="login">* {L_RECIPIENT}</label>
								<div class="form-field">
									<label>
										<input type="text" size="20" maxlength="25" id="login" name="login" value="{post_convers.LOGIN}">
										<span id="search_img"></span>
										<button type="button" value="{L_SEARCH}" onclick="XMLHttpRequest_search_members('', '{THEME}', 'insert_member', '{L_REQUIRE_RECIPIENT}');"  class="submit">{L_SEARCH}</button>							
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
								<div class="form-field"><label><input type="text" size="50" maxlength="100" id="title" name="title" value="{post_convers.TITLE}"></label></div>
							</div>
							<div class="form-element-textarea">
								<label for="contents">* {L_MESSAGE}</label>
								{KERNEL_EDITOR}
								<textarea rows="25" cols="66" id="contents" name="contents">{CONTENTS}</textarea>
							</div>
						</fieldset>
						
						<div class="center">
							<button type="submit" name="convers" value="true">{L_SUBMIT}</button>
							<button type="button" name="prw_convers" id="prw_convers_pm" onclick="XMLHttpRequest_preview();">{L_PREVIEW}</button>
							<button type="reset" value="true">{L_RESET}</button>
						</div>	
					</div>
				</div>
				<footer></footer>
			</section>
		</form>
		# END post_convers #