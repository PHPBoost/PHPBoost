		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('login2').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_PSEUDO']) ? $this->_var['L_REQUIRE_PSEUDO'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('mail2').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_MAIL']) ? $this->_var['L_REQUIRE_MAIL'] : ''; ?>");
				return false;
		    }
				if(document.getElementById('password2').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_PASS']) ? $this->_var['L_REQUIRE_PASS'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('level').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_RANK']) ? $this->_var['L_REQUIRE_RANK'] : ''; ?>");
				return false;
		    }
			return true;
		}

		function check_form_search(){
			if(document.getElementById('search').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_PSEUDO']) ? $this->_var['L_REQUIRE_PSEUDO'] : ''; ?>");
				return false;
		    }

			return true;
		}

		function check_msg(){
			if(document.getElementById('contents').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_TEXT']) ? $this->_var['L_REQUIRE_TEXT'] : ''; ?>");
				return false;
		    }
			return true;
		}

		function Confirm() {
		return confirm("<?php echo isset($this->_var['L_CONFIRM_DEL_MEMBER']) ? $this->_var['L_CONFIRM_DEL_MEMBER'] : ''; ?>");
		}

		function XMLHttpRequest_search()
		{
			var xhr_object = null;
			var data = null;
			var filename = "../includes/xmlhttprequest.php?admin_member=1";
			var login = document.getElementById("login_mbr").value;
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
						hide_div("xmlhttprequest_result_search");
					}
				}

				xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

				xhr_object.send(data);
			}	
			else
			{
				alert("<?php echo isset($this->_var['L_REQUIRE_LOGIN']) ? $this->_var['L_REQUIRE_LOGIN'] : ''; ?>");
			}		
		}
		
		function hide_div(divID)
		{
			if( document.getElementById(divID) )
				document.getElementById(divID).style.display = 'block';
		}

		function insert_XMLHttpRequest(login)
		{
			document.getElementById("login_mbr").value = login;
		}

		-->
		</script>
		
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_MEMBERS_MANAGEMENT']) ? $this->_var['L_MEMBERS_MANAGEMENT'] : ''; ?></li>
				<li>
					<a href="admin_members.php"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members.php" class="quick_link"><?php echo isset($this->_var['L_MEMBERS_MANAGEMENT']) ? $this->_var['L_MEMBERS_MANAGEMENT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_members.php?add=1"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members.php?add=1" class="quick_link"><?php echo isset($this->_var['L_MEMBERS_ADD']) ? $this->_var['L_MEMBERS_ADD'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_members_config.php"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members_config.php" class="quick_link"><?php echo isset($this->_var['L_MEMBERS_CONFIG']) ? $this->_var['L_MEMBERS_CONFIG'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_members_punishment.php"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/members.png" alt="" /></a>
					<br />
					<a href="admin_members_punishment.php" class="quick_link"><?php echo isset($this->_var['L_MEMBERS_PUNISHMENT']) ? $this->_var['L_MEMBERS_PUNISHMENT'] : ''; ?></a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
			
			<span id="search"></span>
			<form action="admin_members.php#search" method="post" onsubmit="return check_form_search();" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_SEARCH_MEMBER']) ? $this->_var['L_SEARCH_MEMBER'] : ''; ?></legend>
					<dl>
						<dt><label for="login_mbr">* <?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_JOKER']) ? $this->_var['L_JOKER'] : ''; ?></span></dt>
						<dd><label>
							<input type="text" size="20" maxlength="25" id="login_mbr" value="<?php echo isset($this->_var['LOGIN']) ? $this->_var['LOGIN'] : ''; ?>" name="login_mbr" class="text" />
							<script type="text/javascript">
							<!--								
								document.write('<input value="<?php echo isset($this->_var['L_SEARCH']) ? $this->_var['L_SEARCH'] : ''; ?>" onclick="XMLHttpRequest_search(this.form);" type="button" class="submit">');
							-->
							</script>
							<noscript>
								<input type="submit" name="search_user" value="<?php echo isset($this->_var['L_SEARCH']) ? $this->_var['L_SEARCH'] : ''; ?>" class="submit" />
							</noscript>
							<div id="xmlhttprequest_result_search" style="display:none;" class="xmlhttprequest_result_search"></div>
							<?php if( !isset($this->_block['search']) || !is_array($this->_block['search']) ) $this->_block['search'] = array();
foreach($this->_block['search'] as $search_key => $search_value) {
$_tmpb_search = &$this->_block['search'][$search_key]; ?>
								<?php echo isset($_tmpb_search['RESULT']) ? $_tmpb_search['RESULT'] : ''; ?>
							<?php } ?>
						</label></dd>
					</dl>
				</fieldset>	
			</form>

			<table  class="module_table">
				<tr> 
					<th colspan="9">
						<?php echo isset($this->_var['L_MEMBERS_MANAGEMENT']) ? $this->_var['L_MEMBERS_MANAGEMENT'] : ''; ?>
					</th>
				</tr>
				<tr style="text-align:center;">
					<td class="row1">
						<a href="admin_members.php?sort=alph&amp;mode=desc"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
						<?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?> 
						<a href="admin_members.php?sort=alph&amp;mode=asc"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
					</td>
					<td class="row1">
						<a href="admin_members.php?sort=rank&amp;mode=desc"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
						<?php echo isset($this->_var['L_RANK']) ? $this->_var['L_RANK'] : ''; ?>
						<a href="admin_members.php?sort=rank&amp;mode=asc"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
					</td>
					<td class="row1">
						<?php echo isset($this->_var['L_MAIL']) ? $this->_var['L_MAIL'] : ''; ?>
					</td>
					<td class="row1">
						<?php echo isset($this->_var['L_WEBSITE']) ? $this->_var['L_WEBSITE'] : ''; ?>
					</td>
					<td class="row1">
						<a href="admin_members.php?sort=time&amp;mode=desc"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
						<?php echo isset($this->_var['L_REGISTERED']) ? $this->_var['L_REGISTERED'] : ''; ?>
						<a href="admin_members.php?sort=time&amp;mode=asc"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
					</td>
					<td class="row1">
						<a href="admin_members.php?sort=aprob&amp;mode=desc"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
						<?php echo isset($this->_var['L_APROB']) ? $this->_var['L_APROB'] : ''; ?>
						<a href="admin_members.php?sort=aprob&amp;mode=asc"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
					</td>
					<td class="row1">
						<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>
					</td>
					<td class="row1">
						<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>
					</td>
				</tr>
				
				<?php if( !isset($this->_block['member']) || !is_array($this->_block['member']) ) $this->_block['member'] = array();
foreach($this->_block['member'] as $member_key => $member_value) {
$_tmpb_member = &$this->_block['member'][$member_key]; ?>
				<tr style="text-align:center;"> 
					<td class="row2">
						<a href="../member/member.php?id=<?php echo isset($_tmpb_member['IDMBR']) ? $_tmpb_member['IDMBR'] : ''; ?>"><?php echo isset($_tmpb_member['NAME']) ? $_tmpb_member['NAME'] : ''; ?></a>				
					</td>
					<td class="row2"> 
						<?php echo isset($_tmpb_member['RANK']) ? $_tmpb_member['RANK'] : ''; ?>
					</td>
					<td class="row2">
						<a href="mailto:<?php echo isset($_tmpb_member['MAIL']) ? $_tmpb_member['MAIL'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/email.png" alt="<?php echo isset($_tmpb_member['MAIL']) ? $_tmpb_member['MAIL'] : ''; ?>" title="<?php echo isset($_tmpb_member['MAIL']) ? $_tmpb_member['MAIL'] : ''; ?>" /></a>
					</td>
					<td class="row2">
						<?php echo isset($_tmpb_member['WEB']) ? $_tmpb_member['WEB'] : ''; ?>
					</td>
					<td class="row2">
						<?php echo isset($_tmpb_member['DATE']) ? $_tmpb_member['DATE'] : ''; ?>
					</td>			
					<td class="row2">
						<?php echo isset($_tmpb_member['APROB']) ? $_tmpb_member['APROB'] : ''; ?>
					</td>
					<td class="row2"> 
						<a href="admin_members.php?id=<?php echo isset($_tmpb_member['IDMBR']) ? $_tmpb_member['IDMBR'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/edit.png" alt="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" title="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" /></a>
					</td>
					<td class="row2">
						<a href="admin_members.php?delete=1&amp;id=<?php echo isset($_tmpb_member['IDMBR']) ? $_tmpb_member['IDMBR'] : ''; ?>" onClick="javascript:return Confirm();"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/delete.png" alt="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" title="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" /></a>
					</td>
				</tr>
				<?php } ?>

			</table>

			<p style="text-align: center;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?></p>
		</div>
