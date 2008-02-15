			<table class="module_table">
				<tr> 
					<th colspan="2">
						{L_INDEX_ADMIN}
					</th>
				</tr>
				<tr> 
					<td class="row1" style="text-align:center;">
						<br />			
						{L_TEXT_INDEX}			
						<br /><br />						
						
						<div class="phpboost_news">
							<script type="text/javascript" src="http://www.phpboost.com/cache/rss_news.html"></script>
						</div>
						<br />
					</td>
				</tr>
			</table>

			<table class="module_table">
				<tr> 
					<th>
						{L_UPDATE_AVAILABLE}
					</th>
				</tr>
				<tr>	
					<td class="row1{WARNING_CORE}" style="text-align:center">
						{UPDATE_AVAILABLE} {L_CORE_UPDATE}
					</td>
				</tr>
				<tr> 
					<td class="row1{WARNING_MODULES}" style="text-align:center">
						{UPDATE_MODULES_AVAILABLE} {L_MODULES_UPDATE}<br />
						# START modules_available #
						<a href="http://www.phpboost.com/phpboost/modules.php?name={modules_available.ID}">{modules_available.NAME} <em>({modules_available.VERSION})</em></a><br />
						# END modules_available #
					</td>
				</tr>	
			</table>
			
			<table class="module_table">
				<tr> 
					<th colspan="4">
						{L_USER_ONLINE}
					</th>
				</tr>	
				<tr> 
					<td class="row1" style="text-align: center;">
						{L_USER_ONLINE}
					</td>
					<td  class="row1" style="text-align: center;">
						{L_USER_IP}
					</td>
					<td  class="row1" style="text-align: center;">
						{L_LOCALISATION}
					</td>
					<td  class="row1" style="text-align: center;">
						{L_LAST_UPDATE}
					</td>
				</tr>
				
				# START user #
				<tr> 
					<td class="row1" style="text-align: center;">
						{user.USER}
					</td>
					<td class="row1" style="text-align: center;">
						{user.USER_IP}
					</td>
					<td class="row1" style="text-align: center;">
						{user.WHERE}
					</td>
					<td class="row1" style="text-align: center;">
						{L_ON} {user.TIME}
					</td>
					
				</tr>
				# END user #

			</table>