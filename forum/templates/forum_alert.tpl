		# START alert_form #

		<script type='text/javascript'>
		<!--
		function check_form_alert(){
				if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_TEXT}");
				return false;
			}
			if(document.getElementById('title').value == "") {
				alert("{L_REQUIRE_TITLE}");
				return false;
			}
			return true;
		}
		-->
		</script>
		
		<form method="post" action="alert.php{SID}" onsubmit="javascript:return check_form_alert();">
		<table class="module_table" style="width:80%">
			<tr>
				<th class="forum_title">
					{L_ALERT}
				</th>
			</tr>
			<tr>
				<td class="module_contents">
					{L_ALERT_EXPLAIN}: <em>{alert_form.TITLE}</em><br />
				</td>			
			</tr>
			<tr>
				<td class="module_contents" style="text-align:center;">
					<br />
					{L_ALERT_TITLE}
					<input type="text" name="title" id="title" class="text" size="50" /><br /><br />
					<br />
					{L_ALERT_CONTENTS} 
					{BBCODE}
					<textarea type="text" class="post" style="width:70%" rows="15" cols="40" id="contents" name="contents"></textarea> 
					<br />
					<input type="hidden" name="id" value="{alert_form.ID_ALERT}" />
					<input type="submit" name="edit_msg" value="{L_SUBMIT}" class="submit" /><br /><br />
				</td>
			</tr>
			<tr>
				<td class="module_bottom">
					&nbsp;
				</td>
			</tr>
		</table> 
		</form>

		# END alert_form #


		# START alert_confirm #

			<table class="module_table" style="width:80%">
				<tr>
					<th class="forum_title">
						{L_ALERT}
					</th>
				</tr>
				<tr>
					<td class="module_contents" style="text-align:center;">
						<br /><br />
						{alert_confirm.MSG}
						<br /><br />
						<a href="{URL_TOPIC}">{L_BACK_TOPIC}</a>
						<br /><br />
					</td>
				</tr>
				<tr>
					<td class="module_bottom">
						&nbsp;
					</td>
				</tr>
			</table>
			
		# END alert_confirm #
		