	# IF C_POLL_MAIN #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top"><strong>{L_POLL} {EDIT}</strong></div>
			<div class="module_contents" style="text-align:center;">
				{L_POLL_MAIN}
				<br /><br />		
				# START list #					
				<a href="poll{list.U_POLL_ID}">{list.QUESTION}
				<br />  
				<a href="poll{list.U_POLL_ID}"><img src="poll.png" alt="" /></a> 
				<br /><br />
				# END list #
				
				<p class="text_center">{U_ARCHIVE}</p>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
	# ENDIF #
		
		
	# IF C_POLL_VIEW #
		<script type='text/javascript'>
		<!--
		function Confirm() {
		return confirm('{L_DELETE_POLL}');
		}
		-->
		</script>
		<form method="post" action="poll{U_POLL_ACTION}">
			<div class="module_position">					
				<div class="module_top_l"></div>		
				<div class="module_top_r"></div>
				<div class="module_top">
					{QUESTION}
					# IF C_IS_ADMIN #
					<a href="../poll/admin_poll.php?id={IDPOLL}" title="{L_EDIT}"><img src="../templates/{THEME}/images/{LANG}/edit.png" class="valign_middle" alt="" /></a>
					&nbsp;&nbsp;<a href="../poll/admin_poll.php?delete=1&amp;id={IDPOLL}&amp;token={TOKEN}" title="{L_DELETE}" onclick="javascript:return Confirm();"><img src="../templates/{THEME}/images/{LANG}/delete.png" class="valign_middle" alt="" /></a>
					# ENDIF #
				</div>
				<div class="module_contents">
					# INCLUDE message_helper #
					
					<div class="block_container">
						<div class="block_top">{QUESTION}</div>
						<div class="block_contents row2">
							# IF C_POLL_QUESTION #
							<div class="row1 text_small">
								# START radio #
								<p style="margin-top:15px;padding-left:25px;"><label><input type="{radio.TYPE}" name="radio" value="{radio.NAME}" /> {radio.ANSWERS}</label></p>
								# END radio #
							
								# START checkbox #
								<p style="margin-top:15px;padding-left:25px;"><label><input type="{checkbox.TYPE}" name="{checkbox.NAME}" value="{checkbox.NAME}" /> {checkbox.ANSWERS}</label></p>
								# END checkbox #
								
								<p class="text_center">
									<input class="submit" name="valid_poll" type="submit" value="{L_VOTE}" /><br />
									<a class="small_link" href="../poll/poll{U_POLL_RESULT}">{L_RESULT}</a>
								</p>
							</div>							
							# ENDIF #														
							
							# START result #
							<div class="row1 text_small">
								<p>{result.ANSWERS}</p>
								<img src="../templates/{THEME}/images/poll_left.png" height="10px" width="" alt="{result.PERCENT}%" title="{result.PERCENT}%" /><img src="../templates/{THEME}/images/poll.png" height="10px" width="{result.WIDTH}" alt="{result.PERCENT}%" title="{result.PERCENT}%" /><img src="../templates/{THEME}/images/poll_right.png" height="10px" width="" alt="{result.PERCENT}%" title="{result.PERCENT}%" /> {result.PERCENT}% [{result.NBRVOTE} {L_VOTE}]
							</div>
							# END result #	
							<div class="row2">
								<span class="text_small" style="float:left;">{VOTES} {L_VOTE}</span>
								<span class="text_small" style="float:right;">{L_ON}:&nbsp;&nbsp;{DATE}&nbsp;&nbsp;</span>
								&nbsp;
							</div>
						</div>
					</div>
				</div>
				<div class="module_bottom_l"></div>		
				<div class="module_bottom_r"></div>
				<div class="module_bottom"><a href="poll.php">{L_BACK_POLL}</a></div>
			</div>
		</form>
	# ENDIF #
	
	
	# IF C_POLL_ARCHIVES #
		<script type='text/javascript'>
		<!--
			function Confirm() {
			return confirm('{L_ALERT_DELETE_POLL}');
			}
		-->
		</script>
			
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<span style="float:left;">{L_ARCHIVE}</span>
				<span style="float:right;">{PAGINATION}</span>
			</div>
			<div class="module_contents">				
				# START list #
				<div class="block_container">
					<div class="block_top">{list.QUESTION} 
					# IF C_IS_ADMIN #
					<a href="../poll/admin_poll.php?id={list.ID}" title="{L_EDIT}"><img src="../templates/{THEME}/images/{LANG}/edit.png" class="valign_middle" /></a>
					&nbsp;&nbsp;<a href="../poll/admin_poll.php?delete=1&amp;id={list.ID}&amp;token={TOKEN}" title="{L_DELETE}" onclick="javascript:return Confirm();"><img src="../templates/{THEME}/images/{LANG}/delete.png" class="valign_middle" /></a>
					# ENDIF #
					</div>
					<div class="block_contents">
						# START list.result #
						<div class="row1 text_small">
							<p>{list.result.ANSWERS}</p>
							<img src="../templates/{THEME}/images/poll_left.png" height="10px" width="" alt="{list.result.PERCENT}%" title="{list.result.PERCENT}%" /><img src="../templates/{THEME}/images/poll.png" height="10px" width="{list.result.WIDTH}" alt="{list.result.PERCENT}%" title="{list.result.PERCENT}%" /><img src="../templates/{THEME}/images/poll_right.png" height="10px" width="" alt="{list.result.PERCENT}%" title="{list.result.PERCENT}%" /> {list.result.PERCENT}% [{list.result.NBRVOTE} {list.result.L_VOTE}]
						</div>
						# END list.result #	
						<div class="row2">
							<span class="text_small" style="float:left;">{list.VOTE} {list.L_VOTE}</span>
							<span class="text_small" style="float: right;">{L_ON}:&nbsp;&nbsp;{list.DATE}&nbsp;&nbsp;</span>
							&nbsp;
						</div>
					</div>
				</div>
				# END list #				
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<span style="float:left;"><a href="poll.php">{L_BACK_POLL}</a></span>
				&nbsp;<span style="float:right;">{PAGINATION}</span>
			</div>
		</div>
	# ENDIF #			
	