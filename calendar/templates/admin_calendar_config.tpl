		<script type="text/javascript">
		<!--
		function check_form_conf()
		{
			if(document.getElementById('calendar_auth').value == "") {
				alert("{L_REQUIRE}");
				return false;
			}
			return true;
		}
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_CALENDAR}</li>
				<li>
					<a href="admin_calendar.php"><img src="calendar.png" alt="" /></a>
					<br />
					<a href="admin_calendar.php" class="quick_link">{L_CALENDAR_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">
		
		<form action="admin_calendar.php?token={TOKEN}" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
			<fieldset>
				<legend>{L_CALENDAR_CONFIG}</legend>
				<dl>
					<dt><label for="shoutbox_auth">* {L_RANK}</label></dt>
					<dd><label>
						<select name="calendar_auth" id="calendar_auth">
						# START select_auth #
							{select_auth.RANK}
						# END select_auth #
						</select>
					</label></dd>
				</dl>
			</fieldset>	
			
			<fieldset class="fieldset_submit">
				<legend>{L_UPDATE}</legend>
				<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
			</fieldset>
		</form>
		