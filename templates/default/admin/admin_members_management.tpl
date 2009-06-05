		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('login2').value == "") {
				alert("{L_REQUIRE_LOGIN}");
				return false;
		    }
			if(document.getElementById('mail2').value == "") {
				alert("{L_REQUIRE_MAIL}");
				return false;
		    }
				if(document.getElementById('password2').value == "") {
				alert("{L_REQUIRE_PASS}");
				return false;
		    }
			if(document.getElementById('level').value == "") {
				alert("{L_REQUIRE_RANK}");
				return false;
		    }
			return true;
		}
		function check_form_search(){
			if(document.getElementById('search').value == "") {
				alert("{L_REQUIRE_LOGIN}");
				return false;
		    }

			return true;
		}
		function check_msg(){
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
		    }
			return true;
		}
		function Confirm(level) {
			ok = confirm("{L_CONFIRM_DEL_USER}");
			if (ok && (level == 2)) {
				return confirm("{L_CONFIRM_DEL_ADMIN}");
			}
			return ok;
		}
		-->
		</script>
		
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_USERS_MANAGEMENT}</li>
				<li>
					<a href="admin_members.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members.php" class="quick_link">{L_USERS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_members.php?add=1"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members.php?add=1" class="quick_link">{L_USERS_ADD}</a>
				</li>
				<li>
					<a href="admin_members_config.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members_config.php" class="quick_link">{L_USERS_CONFIG}</a>
				</li>
				<li>
					<a href="admin_members_punishment.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members_punishment.php" class="quick_link">{L_USERS_PUNISHMENT}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
			
			<span id="search"></span>
			<form action="admin_members.php#search" method="post" onsubmit="return check_form_search();" class="fieldset_content">
				<fieldset>
					<legend>{L_SEARCH_USER}</legend>
					<dl>
						<dt><label for="login_mbr">* {L_PSEUDO}</label><br /><span>{L_JOKER}</span></dt>
						<dd>
							<div style="float:left;">
								{L_SEARCH_USER}: <input type="text" size="20" maxlength="25" id="login" value="{all.LOGIN}" name="login_mbr" class="text" />
								<span id="search_img"></span>
							
							</div>
							<div style="float:left;margin-left:5px;">
								<input type="submit" id="search_member" name="search_member" value="{L_SEARCH}" class="submit" />
								<script type="text/javascript">
								<!--								
									document.getElementById('search_member').style.display = 'none';
									document.write('<input value="{L_SEARCH}" onclick="XMLHttpRequest_search_members(\'\', \'{THEME}\', \'admin_member\', \'{L_REQUIRE_LOGIN}\');" type="button" class="submit">');
								-->
								</script>									
								<div id="xmlhttprequest_result_search" # IF NOT C_DISPLAY_SEARCH_RESULT # style="display:none;" # ENDIF # class="xmlhttprequest_result_search">
									# START search #
									{search.RESULT}
									# END search #
								</div>
							</div>
						</dd>
					</dl>
				</fieldset>	
			</form>

			<table  class="module_table">
				<tr> 
					<th colspan="9">
						{L_USERS_MANAGEMENT}
					</th>
				</tr>
				<tr style="text-align:center;">
					<td class="row1">
						<a href="admin_members.php?sort=alph&amp;mode=desc"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" /></a>
						{L_PSEUDO} 
						<a href="admin_members.php?sort=alph&amp;mode=asc"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" /></a>
					</td>
					<td class="row1">
						<a href="admin_members.php?sort=rank&amp;mode=desc"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" /></a>
						{L_RANK}
						<a href="admin_members.php?sort=rank&amp;mode=asc"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" /></a>
					</td>
					<td class="row1">
						{L_MAIL}
					</td>
					<td class="row1">
						{L_WEBSITE}
					</td>
					<td class="row1">
						<a href="admin_members.php?sort=time&amp;mode=desc"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" /></a>
						{L_REGISTERED}
						<a href="admin_members.php?sort=time&amp;mode=asc"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" /></a>
					</td>
					<td class="row1">
						<a href="admin_members.php?sort=aprob&amp;mode=desc"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" /></a>
						{L_APROB}
						<a href="admin_members.php?sort=aprob&amp;mode=asc"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" /></a>
					</td>
					<td class="row1">
						{L_UPDATE}
					</td>
					<td class="row1">
						{L_DELETE}
					</td>
				</tr>
				
				# START member #
				<tr style="text-align:center;"> 
					<td class="row2">
						<a href="{PATH_TO_ROOT}/member/member.php?id={member.IDMBR}">{member.NAME}</a>				
					</td>
					<td class="row2"> 
						{member.RANK}
					</td>
					<td class="row2">
						<a href="mailto:{member.MAIL}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/email.png" alt="{member.MAIL}" title="{member.MAIL}" /></a>
					</td>
					<td class="row2">
						{member.WEB}
					</td>
					<td class="row2">
						{member.DATE}
					</td>			
					<td class="row2">
						{member.APROB}
					</td>
					<td class="row2"> 
						<a href="admin_members.php?id={member.IDMBR}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
					</td>
					<td class="row2">
						<a href="admin_members.php?delete=1&amp;id={member.IDMBR}&amp;token={TOKEN}" onclick="javascript:return Confirm({member.LEVEL});"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
					</td>
				</tr>
				# END member #

			</table>

			<p style="text-align: center;">{PAGINATION}</p>
		</div>
