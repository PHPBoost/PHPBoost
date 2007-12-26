	# START main #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top"><strong>{L_POLL} {EDIT}</strong></div>
			<div class="module_contents" style="text-align:center;">
				{L_POLL_MAIN}
				<br /><br />		
				# START poll #					
				<a href="poll{main.poll.U_POLL_ID}">{main.poll.QUESTION}
				<br />  
				<a href="poll{main.poll.U_POLL_ID}"><img src="poll.png" alt="" /></a> 
				<br /><br />
				# END poll #
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">{U_ARCHIVE}</div>
		</div>
	# END main #
		
		
		
	# START poll #
		{JAVA}
		
		<form method="post" action="poll{U_POLL_ACTION}">
			<div class="module_position">					
				<div class="module_top_l"></div>		
				<div class="module_top_r"></div>
				<div class="module_top">{poll.QUESTION} {EDIT}{DEL}</div>
				<div class="module_contents">
					# START error_handler #
					<br />
					<span id="errorh"></span>
					<div class="{poll.error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
						<img src="../templates/{THEME}/images/{poll.error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {poll.error_handler.L_ERROR}
						<br />	
					</div>
					# END error_handler #
					
					<table class="module_table">
						# START question #
						# START radio #
						<tr>
							<td class="row2" style="font-size:10px;">
								<label><input type="{poll.question.radio.TYPE}" name="radio" value="{poll.question.radio.NAME}" /> {poll.question.radio.ANSWERS}</label>
							</td>
						</tr>
						# END radio #
					
						# START checkbox #
						<tr>
							<td class="row2">
								<label><input type="{poll.question.checkbox.TYPE}" name="{poll.question.checkbox.NAME}" value="{poll.question.checkbox.NAME}" /> {poll.question.checkbox.ANSWERS}</label>
							</td>
						</tr>
						# END checkbox #
						
						<tr>	
							<td class="row1" style="text-align:center;">	
								<input class="submit" name="valid_poll" type="submit" value="{L_VOTE}" /><br />
								<a class="small_link" href="../poll/poll{U_POLL_RESULT}">{L_RESULT}</a>
							</td>
						</tr>
						# END question #
						
						
						
						# START result #
						<tr>
							<td class="row2" style="font-size:10px;">
								{poll.result.ANSWERS}
								<table width="95%">
									<tr>
										<td>
											<img src="../templates/{THEME}/images/poll_left.png" height="10px" width="" alt="{poll.result.PERCENT}%" title="{poll.result.PERCENT}%" /><img src="../templates/{THEME}/images/poll.png" height="10px" width="{poll.result.WIDTH}" alt="{poll.result.PERCENT}%" title="{poll.result.PERCENT}%" /><img src="../templates/{THEME}/images/poll_right.png" height="10px" width="" alt="{poll.result.PERCENT}%" title="{poll.result.PERCENT}%" /> {poll.result.PERCENT}% [{poll.result.NBRVOTE} {L_VOTE}]
										</td>
									</tr>
								</table>
							</td>
						</tr>
						# END result #	
						
						<tr>
							<td class="row3">
								<span class="text_small" style="float:left;">{poll.VOTES} {L_VOTE}</span>
								<span class="text_small" style="float:right;">{L_ON}:&nbsp;&nbsp;{poll.DATE}&nbsp;&nbsp;</span>
							</td>
						</tr>
					</table>				
				</div>
				<div class="module_bottom_l"></div>		
				<div class="module_bottom_r"></div>
				<div class="module_bottom"><a href="poll.php{SID}">{L_BACK_POLL}</a></div>
			</div>
		</form>
	# END poll #
	
	
	# START archives #
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
				&nbsp;<span style="float:right;">{archives.PAGINATION}</span>
			</div>
			<div class="module_contents">
				# START main #
				<table class="module_table">
					<tr>
						<th>{archives.main.QUESTION} {archives.main.EDIT}{archives.main.DEL}</th>
					</tr>	
					<tr>	
						<td class="row2">
							<table class="module_table">								
								# START result #
								<tr>
									<td class="row3" style="font-size:10px;">
										{archives.main.result.ANSWERS}
										<table width="95%">
											<tr>
												<td>
													<img src="../templates/{THEME}/images/poll_left.png" height="10px" width="" alt="{archives.main.result.PERCENT}%" title="{archives.main.result.PERCENT}%" /><img src="../templates/{THEME}/images/poll.png" height="10px" width="{archives.main.result.WIDTH}" alt="{archives.main.result.PERCENT}%" title="{archives.main.result.PERCENT}%" /><img src="../templates/{THEME}/images/poll_right.png" height="10px" width="" alt="{archives.main.result.PERCENT}%" title="{archives.main.result.PERCENT}%" /> {poll.result.PERCENT}% [{archives.main.result.NBRVOTE} {archives.main.result.L_VOTE}]
												</td>
											</tr>
										</table>
									</td>
								</tr>
								# END result #									
							</table>
						</td>
					</tr>
					<tr>
						<td class="row2">
							<span class="text_small" style="float:left;">{archives.main.VOTE} {archives.main.L_VOTE}</span>
							<span class="text_small" style="float: right;">{L_ON}:&nbsp;&nbsp;{archives.main.DATE}&nbsp;&nbsp;</span>
						</td>
					</tr>
				</table>						
				<br /><br />
					
				# END main #
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<span style="float:left;"><a href="poll.php{SID}">{L_BACK_POLL}</a></span>
				&nbsp;<span style="float:right;">{archives.PAGINATION}</span>
			</div>
		</div>
	# END archives #			
	