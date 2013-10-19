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
			
			
			<table>
				<thead>
					<tr>
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
				</thead>
				# IF C_PAGINATION #
				<tfoot>
					<tr>
						<th colspan="8">
							{PAGINATION}
						</th>
					</tr>
				</tfoot>
				# ENDIF #
				<tbody>
					# START questions #
					<tr> 
						<td>
							<a href="../poll/poll.php?id={questions.IDPOLL}">{L_SHOW}</a>
						</td>
						<td> 
							{questions.QUESTIONS}
						</td>			
						<td> 
							{questions.PSEUDO}
						</td>
						<td>
							{questions.DATE}
						</td>
						<td>
							{questions.ARCHIVES}
						</td>	
						<td>
							{questions.APROBATION} 
							<br />
							<span class="smaller">{questions.VISIBLE}</span>
						</td>
						<td> 
							<a href="admin_poll.php?id={questions.IDPOLL}" title="{L_UPDATE}" class="edit"></a>
						</td>
						<td>
							<a href="admin_poll.php?delete=true&amp;id={questions.IDPOLL}&amp;token={TOKEN}" title="{L_DELETE}" class="delete"></a>
						</td>
					</tr>
					# END questions #
				</tbody>
			</table>
		</div>
