			<div class="block_container">
				<div class="block_top">{L_INDEX_ADMIN}</div>
				<div class="block_contents1 text_center">
					{L_TEXT_INDEX}			
					<br /><br />						
					
					<div class="phpboost_news">
						<script type="text/javascript" src="http://www.phpboost.com/cache/rss_news.html"></script>
					</div>
				</div>
			</div>
			
			<div class="block_container">
				<div class="block_top">{L_UPDATE_AVAILABLE}</div>
				<div class="{WARNING_CORE} row1" style="width:auto;text-align:center">
					{UPDATE_AVAILABLE} {L_CORE_UPDATE}
				</div>
				<div class="{WARNING_MODULES} row1" style="width:auto;text-align:center">
					{UPDATE_MODULES_AVAILABLE} {L_MODULES_UPDATE}<br />
					# START modules_available #
					<a href="http://www.phpboost.com/phpboost/modules.php?name={modules_available.ID}">{modules_available.NAME} <em>({modules_available.VERSION})</em></a><br />
					# END modules_available #
				</div>
			</div>

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