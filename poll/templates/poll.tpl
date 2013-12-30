	# IF C_POLL_MAIN #
		<section>	
			<header>
				<h1>{L_POLL} <span class="actions">{EDIT}</span></h1>
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
						<span class="actions">
							<a href="{PATH_TO_ROOT}/poll/admin_poll.php?id={IDPOLL}" title="{L_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
							<a href="{PATH_TO_ROOT}/poll/admin_poll.php?delete=1&amp;id={IDPOLL}&amp;token={TOKEN}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="delete-element"></a>
						</span>
						# ENDIF #
					</h1>
				</header>
				<div class="content">
					# INCLUDE message_helper #
					
					<article class="block">
						<header><h1>{QUESTION}</h1></header>
						<div class="content">
							# IF C_POLL_QUESTION #
							<div>
								# START radio #
								<p style="margin-top:15px;padding-left:25px;"><label><input type="{radio.TYPE}" name="radio" value="{radio.NAME}"> {radio.ANSWERS}</label></p>
								# END radio #
							
								# START checkbox #
								<p style="margin-top:15px;padding-left:25px;"><label><input type="{checkbox.TYPE}" name="{checkbox.NAME}" value="{checkbox.NAME}"> {checkbox.ANSWERS}</label></p>
								# END checkbox #
								
								<p class="center">
									<button name="valid_poll" type="submit" value="{L_VOTE}">{L_VOTE}</button>
									<br />
									<a class="small" href="{PATH_TO_ROOT}/poll/poll{U_POLL_RESULT}">{L_RESULT}</a>
								</p>
							</div>							
							# ENDIF #														
							
							# START result #
							<div>
								<h6>{result.ANSWERS}</h6>
								<div class="progressbar-container" title="{result.PERCENT}%">
									<span class="progressbar-infos">
										{result.NBRVOTE} {L_VOTE}
									</span>
									<div class="progressbar" style="width:{result.PERCENT}%"></div>
								</div>
								<br/>
							</div>
							# END result #	
							<div>
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
							<span class="actions">
								# IF C_IS_ADMIN #
								<a href="{PATH_TO_ROOT}/poll/admin_poll.php?id={list.ID}" title="{L_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
								<a href="{PATH_TO_ROOT}/poll/admin_poll.php?delete=1&amp;id={list.ID}&amp;token={TOKEN}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="delete-element"></a>
								# ENDIF #
							</span>
						</h1>
					</header>
					<div class="content">
						# START list.result #
							<div>
								<h6>{list.result.ANSWERS}</h6>
								<div class="progressbar-container" title="{list.result.PERCENT}%">
									<span class="progressbar-infos">
										{list.result.NBRVOTE} {list.L_VOTE}
									</span>
									<div class="progressbar" style="width:{list.result.PERCENT}%"></div>
								</div>
								<br/>
							</div>
						# END list.result #	
						<div>
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