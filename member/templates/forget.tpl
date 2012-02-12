		<script type="text/javascript">
		<!--
		function check_form_forget(){
			if(document.getElementById('name').value == "") {
				alert("{L_REQUIRE_PSEUDO}");
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

		<div style="text-align:center;">
			{ERROR}				
			<form action="forget.php?token={TOKEN}" method="post" onsubmit="return check_form_forget();">
				<table class="module_table">
					<tr>
						<th colspan="2">
							<span class="text_white">{L_NEW_PASS}</span>
						</th>
					</tr>
					# IF C_ERROR_HANDLER #
					<tr>
						<td class="row2" style="text-align: center;">
							<br />
							<span id="errorh"></span>
							<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
								<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
								<br />	
							</div>
						</td>
					</tr>
					# ENDIF #
					<tr>	
						<td colspan="2" class="row2" style="text-align: center;">
							{L_REQUIRE}
							<br /><br /><br />
							<label>* {L_PSEUDO} <input type="text" size="25" name="name" id="name" class="text" /></label>
							<br /><br />
							<label>* {L_MAIL} <input type="text" size="50" id="mail" name="mail" class="text" /></label>
							<br /><br />
							{L_NEW_PASS_FORGET}
						</td>
					</tr>	
				</table>
				
				<br /><br />
				
				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="submit" name="forget" value="{L_SUBMIT}" class="submit" />
				</fieldset>	
			</form>	
		</div>
