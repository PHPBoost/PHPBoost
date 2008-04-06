		# INCLUDE admin_download_menu #
		
		<div id="admin_contents">
		
			# IF C_ERROR_HANDLER #
			<span id="errorh"></span>
			<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
				<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
				<br />	
			</div>
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