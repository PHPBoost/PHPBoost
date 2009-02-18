		# INCLUDE admin_download_menu #
		
		<div id="admin_contents">
		
			# IF C_ERROR_HANDLER #
			<span id="errorh"></span>
			<div id="error_msg">
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
				</div>
			<br />
			</div>
			<script type="text/javascript">
			<!--
				//Javascript timeout to hide this message
				setTimeout('Effect.Fade("error_msg");', 1500);
			-->
			</script>
			<br />
			# ENDIF #

			<table class="module_table" style="width:99%;">
				<tr>			
					<th colspan="3">
						{L_CATS_MANAGEMENT}
					</th>
				</tr>							
				<tr>
					<td style="padding-left:20px;" class="row2">
						<br />
						{CATEGORIES}
						<br />
					</td>
				</tr>
			</table>
			
			<div style="text-align:center; margin:30px 20px;" class="row1">
				<a href="{U_RECOUNT_SUBFILES}">
					<img src="../templates/{THEME}/images/admin/refresh.png" alt="{L_RECOUNT_QUESTIONS}" />
				</a>
				<br />
				<a href="{U_RECOUNT_SUBFILES}">{L_RECOUNT_SUBFILES}</a>
			</div>
				
		</div>