	# IF C_POLL_MAIN #
		<section>	
			<header>
				<h1>{L_POLL} <span class="tools">{EDIT}</span></h1>
			</header>
			<div class="content" style="text-align:center;">
				{L_POLL_MAIN}
				<br /><br />		
				# START list #					
				<a href="{PATH_TO_ROOT}/poll/poll{list.U_POLL_ID}">{list.QUESTION}
				<br />  
				<a href="{PATH_TO_ROOT}/poll/poll{list.U_POLL_ID}"><img src="{PATH_TO_ROOT}/poll/poll.png" alt="" title="{list.QUESTION}" /></a> 
				<br /><br />
				# END list #
				
				<p class="center">{U_ARCHIVE}</p>
			</div>
			<footer></footer>
		</section>
	# ENDIF #
		
		
	# IF C_POLL_VIEW #
		<form method="post" action="{PATH_TO_ROOT}/poll/poll{U_POLL_ACTION}">
			<article>					
				<header>
					<h1>
						{QUESTION}
						# IF C_IS_ADMIN #
						<span class="tools">
							<a href="{PATH_TO_ROOT}/poll/admin_poll.php?id={IDPOLL}" title="{L_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="edit"></a>
							<a href="{PATH_TO_ROOT}/poll/admin_poll.php?delete=1&amp;id={IDPOLL}&amp;token={TOKEN}" title="${LangLoader::get_message('delete', 'main')}" class="delete"></a>
						</span>
						# ENDIF #
					</h1>
				</header>
				<div class="content">
					# INCLUDE message_helper #
					
					<article class="block">
						<header><h1>{QUESTION}</h1></header>
						<div class="contents">
							# IF C_POLL_QUESTION #
							<div class="row1 text_small">
								# START radio #
								<p style="margin-top:15px;padding-left:25px;"><label><input type="{radio.TYPE}" name="radio" value="{radio.NAME}"> {radio.ANSWERS}</label></p>
								# END radio #
							
								# START checkbox #
								<p style="margin-top:15px;padding-left:25px;"><label><input type="{checkbox.TYPE}" name="{checkbox.NAME}" value="{checkbox.NAME}"> {checkbox.ANSWERS}</label></p>
								# END checkbox #
								
								<p class="center">
									<input class="submit" name="valid_poll" type="submit" value="{L_VOTE}" /><br>
									<a class="small" href="{PATH_TO_ROOT}/poll/poll{U_POLL_RESULT}">{L_RESULT}</a>
								</p>
							</div>							
							# ENDIF #														
							
							# START result #
							<div class="row1 text_small">
								<p>{result.ANSWERS}</p>
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/poll_left.png" height="10px" width="" alt="{result.PERCENT}%" title="{result.PERCENT}%" /><img src="{PATH_TO_ROOT}/templates/{THEME}/images/poll.png" height="10px" width="{result.WIDTH}" alt="{result.PERCENT}%" title="{result.PERCENT}%" /><img src="../templates/{THEME}/images/poll_right.png" height="10px" width="" alt="{result.PERCENT}%" title="{result.PERCENT}%" /> {result.PERCENT}% [{result.NBRVOTE} {L_VOTE}]
							</div>
							# END result #	
							<div class="row2">
								<span class="smaller" style="float:left;">{VOTES} {L_VOTE}</span>
								<span class="smaller" style="float:right;">{L_ON}:&nbsp;&nbsp;{DATE}&nbsp;&nbsp;</span>
								&nbsp;
							</div>
						</div>
						<footer></footer>
					</article>
				</div>
				<footer></footer>
			</article>
		</form>
	# ENDIF #
	
	
	# IF C_POLL_ARCHIVES #
		<section>					
			<header>
				<h1>{L_ARCHIVE}</h1>
				<span style="float:right;">{PAGINATION}</span>
			</header>
			<div class="content">
				# START list #
				<article class="block">
					<header>
						<h1>
							{list.QUESTION}
							<span class="tools">
								# IF C_IS_ADMIN #
								<a href="{PATH_TO_ROOT}/poll/admin_poll.php?id={list.ID}" title="{L_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="edit"></a>
								<a href="{PATH_TO_ROOT}/poll/admin_poll.php?delete=1&amp;id={list.ID}&amp;token={TOKEN}" title="${LangLoader::get_message('delete', 'main')}" class="delete"></a>
								# ENDIF #
							</span>
						</h1>
					</header>
					<div class="contents">
						# START list.result #
						<div class="row1 text_small">
							<p>{list.result.ANSWERS}</p>
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/poll_left.png" height="10px" width="" alt="{list.result.PERCENT}%" title="{list.result.PERCENT}%" /><img src="{PATH_TO_ROOT}/templates/{THEME}/images/poll.png" height="10px" width="{list.result.WIDTH}" alt="{list.result.PERCENT}%" title="{list.result.PERCENT}%" /><img src="../templates/{THEME}/images/poll_right.png" height="10px" width="" alt="{list.result.PERCENT}%" title="{list.result.PERCENT}%" /> {list.result.PERCENT}% [{list.result.NBRVOTE} {list.result.L_VOTE}]
						</div>
						# END list.result #	
						<div class="row2">
							<span class="smaller" style="float:left;">{list.VOTE} {list.L_VOTE}</span>
							<span class="smaller" style="float: right;">{L_ON}:&nbsp;&nbsp;{list.DATE}&nbsp;&nbsp;</span>
							&nbsp;
						</div>
					</div>
				</article>
				# END list #				
			</div>
			<footer><span style="float:right;">{PAGINATION}</span></footer>
		</section>
	# ENDIF #