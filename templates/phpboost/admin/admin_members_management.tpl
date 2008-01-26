		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('login2').value == "") {
				alert("{L_REQUIRE_PSEUDO}");
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
				alert("{L_REQUIRE_PSEUDO}");
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
		function Confirm() {
			return confirm("{L_CONFIRM_DEL_MEMBER}");
		}
		function XMLHttpRequest_search()
		{
			var login = document.getElementById('login_mbr').value;
			if( login != "" )
			{
				var xhr_object = xmlhttprequest_init('../includes/xmlhttprequest.php?admin_member=1');
				data = 'login=' + login;
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 ) 
					{
						document.getElementById('xmlhttprequest_result_search').innerHTML = xhr_object.responseText;
						hide_div('xmlhttprequest_result_search');
					}
				}
				xmlhttprequest_sender(xhr_object, data);
			}	
			else
				alert("{L_REQUIRE_LOGIN}");
		}
		function hide_div(divID)
		{
			if( document.getElementById(divID) )
				document.getElementById(divID).style.display = 'block';
		}
		function insert_XMLHttpRequest(login)
		{
			document.getElementById('login_mbr').value = login;
		}
		-->
		</script>
		
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_MEMBERS_MANAGEMENT}</li>
				<li>
					<a href="admin_members.php"><img src="../templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members.php" class="quick_link">{L_MEMBERS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_members.php?add=1"><img src="../templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members.php?add=1" class="quick_link">{L_MEMBERS_ADD}</a>
				</li>
				<li>
					<a href="admin_members_config.php"><img src="../templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members_config.php" class="quick_link">{L_MEMBERS_CONFIG}</a>
				</li>
				<li>
					<a href="admin_members_punishment.php"><img src="../templates/{THEME}/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members_punishment.php" class="quick_link">{L_MEMBERS_PUNISHMENT}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
			
			<span id="search"></span>
			<form action="admin_members.php#search" method="post" onsubmit="return check_form_search();" class="fieldset_content">
				<fieldset>
					<legend>{L_SEARCH_MEMBER}</legend>
					<dl>
						<dt><label for="login_mbr">* {L_PSEUDO}</label><br /><span>{L_JOKER}</span></dt>
						<dd><label>
							<input type="text" size="20" maxlength="25" id="login_mbr" value="{LOGIN}" name="login_mbr" class="text" />
							<script type="text/javascript">
							<!--								
								document.write('<input value="{L_SEARCH}" onclick="XMLHttpRequest_search(this.form);" type="button" class="submit">');
							-->
							</script>
							<noscript>
								<input type="submit" name="search_user" value="{L_SEARCH}" class="submit" />
							</noscript>
							<div id="xmlhttprequest_result_search" style="display:none;" class="xmlhttprequest_result_search"></div>
							# START search #
								{search.RESULT}
							# END search #
						</label></dd>
					</dl>
				</fieldset>	
			</form>

			<table  class="module_table">
				<tr> 
					<th colspan="9">
						{L_MEMBERS_MANAGEMENT}
					</th>
				</tr>
				<tr style="text-align:center;">
					<td class="row1">
						<a href="admin_members.php?sort=alph&amp;mode=desc"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
						{L_PSEUDO} 
						<a href="admin_members.php?sort=alph&amp;mode=asc"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
					</td>
					<td class="row1">
						<a href="admin_members.php?sort=rank&amp;mode=desc"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
						{L_RANK}
						<a href="admin_members.php?sort=rank&amp;mode=asc"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
					</td>
					<td class="row1">
						{L_MAIL}
					</td>
					<td class="row1">
						{L_WEBSITE}
					</td>
					<td class="row1">
						<a href="admin_members.php?sort=time&amp;mode=desc"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
						{L_REGISTERED}
						<a href="admin_members.php?sort=time&amp;mode=asc"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
					</td>
					<td class="row1">
						<a href="admin_members.php?sort=aprob&amp;mode=desc"><img src="../templates/{THEME}/images/top.png" alt="" /></a>
						{L_APROB}
						<a href="admin_members.php?sort=aprob&amp;mode=asc"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
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
						<a href="../member/member.php?id={member.IDMBR}">{member.NAME}</a>				
					</td>
					<td class="row2"> 
						{member.RANK}
					</td>
					<td class="row2">
						<a href="mailto:{member.MAIL}"><img src="../templates/{THEME}/images/{LANG}/email.png" alt="{member.MAIL}" title="{member.MAIL}" /></a>
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
						<a href="admin_members.php?id={member.IDMBR}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
					</td>
					<td class="row2">
						<a href="admin_members.php?delete=1&amp;id={member.IDMBR}" onClick="javascript:return Confirm();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
					</td>
				</tr>
				# END member #

			</table>

			<p style="text-align: center;">{PAGINATION}</p>
		</div>
