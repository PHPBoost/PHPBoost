		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("{L_CONFIRM_ERASE_POOL}");
		}
		-->
		</script>
		
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_POLL_MANAGEMENT}</li>
				<li>
					<a href="admin_poll.php"><img src="poll.png" alt="" /></a>
					<br />
					<a href="admin_poll.php" class="quick_link">{L_POLL_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_poll_add.php"><img src="poll.png" alt="" /></a>
					<br />
					<a href="admin_poll_add.php" class="quick_link">{L_POLL_ADD}</a>
				</li>
				<li>
					<a href="admin_poll_config.php"><img src="poll.png" alt="" /></a>
					<br />
					<a href="admin_poll_config.php" class="quick_link">{L_POLL_CONFIG}</a>
				</li>
			</ul>
		</div> 
		
		<div id="admin_contents">
			
			
			<table class="module_table">
				<tr style="text-align:center;">
					<th>
						{L_POLLS}
					</th>
					<th>
						{L_QUESTION}
					</th>
					<th>
						{L_PSEUDO}
					</th>
					<th>
						{L_DATE}
					</th>
					<th>
						{L_ARCHIVED}
					</th>
					<th>
						{L_APROB}
					</th>
					<th>
						{L_UPDATE}
					</th>
					<th>
						{L_DELETE}
					</th>
				</tr>
				
				# START questions #
				<tr style="text-align:center;"> 
					<td class="row2">
						<a href="../poll/poll.php?id={questions.IDPOLL}">{L_SHOW}</a>
					</td>
					<td class="row2"> 
						{questions.QUESTIONS}
					</td>			
					<td class="row2"> 
						{questions.PSEUDO}
					</td>
					<td class="row2">
						{questions.DATE}
					</td>
					<td class="row2">
						{questions.ARCHIVES}
					</td>	
					<td class="row2">
						{questions.APROBATION} 
						<br />
						<span class="text_small">{questions.VISIBLE}</span>
					</td>
					<td class="row2"> 
						<a href="admin_poll.php?id={questions.IDPOLL}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
					</td>
					<td class="row2">
						<a href="admin_poll.php?delete=true&amp;id={questions.IDPOLL}&amp;token={TOKEN}" onclick="javascript:return Confirm();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
					</td>
				</tr>
				# END questions #

			</table>

			<br /><br />
			<p style="text-align: center;">{PAGINATION}</p>
		</div>
