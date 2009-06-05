		# INCLUDE forum_top #
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">&bull; {U_FORUM_CAT} &raquo; {U_TITLE_T} <span style="font-weight:normal"><em>{DESC}</em></span> &raquo; <a href="">{L_ALERT}</a></div>
			<div class="module_contents">

			# START alert_form #
				<script type='text/javascript'>
				<!--
				function check_form_alert(){
					# IF C_BBCODE_TINYMCE_MODE #
					tinyMCE.triggerSave();
					# ENDIF #

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
				
				<form method="post" action="alert.php?token={TOKEN}" onsubmit="javascript:return check_form_alert();">
					<table class="module_table" style="width:80%">
						<tr>
							<th>{L_ALERT}</th>
						</tr>
						<tr>
							<td class="row2">
								{L_ALERT_EXPLAIN}: <a href="{alert_form.U_TOPIC}">{alert_form.TITLE}</a><br />
							</td>			
						</tr>
						<tr>
							<td class="row2">
								<p style="text-align:center;">
									<label>{L_ALERT_TITLE} <input type="text" name="title" id="title" class="text" size="50" /></label>
								</p>
								<br />
								<p style="text-align:center;"><label for="contents">{L_ALERT_CONTENTS}</label></p>
								{KERNEL_EDITOR}
								<textarea class="post" style="width:70%" rows="15" cols="40" id="contents" name="contents"></textarea> 
								<br />
								<input type="hidden" name="id" value="{alert_form.ID_ALERT}" />
								<p style="text-align:center;">
									<input type="submit" name="edit_msg" value="{L_SUBMIT}" class="submit" />
									&nbsp;&nbsp; 
									<script type="text/javascript">
									<!--				
									document.write('<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();" type="button" class="submit" />');
									-->
									</script>	
									&nbsp;&nbsp; 	
									<input type="reset" value="{L_RESET}" class="reset" />								
								</p>
							</td>
						</tr>
					</table> 
				</form>
				<br />
				# END alert_form #


				# START alert_confirm #
				<table class="module_table" style="width:80%">
					<tr>
						<th>{L_ALERT}</th>
					</tr>
					<tr>
						<td class="row2" style="text-align:center;">
							<br /><br />
							{alert_confirm.MSG}
							<br /><br />
							<a href="{URL_TOPIC}">{L_BACK_TOPIC}</a>
							<br /><br />
						</td>
					</tr>
				</table>	
				<br />
				# END alert_confirm #
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">&bull; {U_FORUM_CAT} &raquo; {U_TITLE_T} <span style="font-weight:normal"><em>{DESC}</em></span> &raquo; <a href="">{L_ALERT}</a></div>
		</div>
		
		# INCLUDE forum_bottom #
		