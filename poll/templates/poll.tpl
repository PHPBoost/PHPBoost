	# IF C_POLL_MAIN #
		<section id="module-poll-main">
			<header>
				<h1>{L_POLL} <span class="actions">{EDIT}</span></h1>
			</header>

			<div class="content center">
				{L_POLL_MAIN}
				<br /><br />
				# START list #
				<a href="{PATH_TO_ROOT}/poll/poll{list.U_POLL_ID}">{list.QUESTION}</a>
				<br />  
				<a href="{PATH_TO_ROOT}/poll/poll{list.U_POLL_ID}"><img src="{PATH_TO_ROOT}/poll/poll.png" alt="{list.QUESTION}" title="{list.QUESTION}" /></a> 
				<br /><br />
				# END list #
				
				<p class="center">{U_ARCHIVE}</p>
			</div>
			<footer></footer>
		</section>
	# ENDIF #
		
		
	# IF C_POLL_VIEW #
		<form method="post" action="{PATH_TO_ROOT}/poll/poll{U_POLL_ACTION}">
			<section id="module-poll">
				<header>
					<h2>
						{QUESTION}
						# IF C_IS_ADMIN #
						<span class="actions">
							<a href="{PATH_TO_ROOT}/poll/admin_poll.php?id={IDPOLL}" title="{L_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
							<a href="{PATH_TO_ROOT}/poll/admin_poll.php?delete=1&amp;id={IDPOLL}&amp;token={TOKEN}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
						</span>
						# ENDIF #
					</h2>
				</header>
				<div class="content">
					# INCLUDE message_helper #
					
					<article id="article-poll-{IDPOLL}" class="article-poll block">
						<header><h2>{QUESTION}</h2></header>
						<div class="content">
							# IF C_POLL_QUESTION #
							<div>
								# START radio #
								<p class="poll-question-select"><label><input type="{radio.TYPE}" name="radio" value="{radio.NAME}"> {radio.ANSWERS}</label></p>
								# END radio #
							
								# START checkbox #
								<p class="poll-question-select"><label><input type="{checkbox.TYPE}" name="{checkbox.NAME}" value="{checkbox.NAME}"> {checkbox.ANSWERS}</label></p>
								# END checkbox #
								
								<p class="center">
									<button name="valid_poll" type="submit" value="{L_VOTE}">{L_VOTE}</button>
									<input type="hidden" name="token" value="{TOKEN}">
								</p>
								<p class="center">
									<a class="small" href="{PATH_TO_ROOT}/poll/poll{U_POLL_RESULT}">{L_RESULT}</a>
								</p>
							</div>
							# ENDIF #
							
							# IF C_POLL_RESULTS #
								# IF C_DISPLAY_RESULTS #
									# START result #
									<div>
										<h6>{result.ANSWERS} - ({result.NBRVOTE} {L_VOTE})</h6>
										<div class="progressbar-container" title="{result.PERCENT}%">
											<div class="progressbar-infos">{result.PERCENT}%</div>
											<div class="progressbar" style="width:{result.PERCENT}%;"></div>
											
										</div>
										<br/>
									</div>
									# END result #
									<div>
										<span class="smaller left">{VOTES} {L_VOTE}</span>
										<span class="smaller right">{L_ON} : {DATE} </span>
										&nbsp;
									</div>
								# ELSE #
									<div class="notice"># IF C_NO_VOTE #{L_NO_VOTE}# ELSE #{L_RESULTS_NOT_DISPLAYED_YET}# ENDIF #</div>
								# ENDIF #
							# ENDIF #
						</div>
						<footer></footer>
					</article>
				</div>
				<footer></footer>
			</section>
		</form>
	# ENDIF #
	
	
	# IF C_POLL_ARCHIVES #
		<section id="module-poll-archives">
			<header>
				<h1>{L_ARCHIVE}</h1>
				# IF C_PAGINATION #<span class="right"># INCLUDE PAGINATION #</span># ENDIF #
			</header>
			<div class="content">
				# START list #
				<article id="article-poll-{list.ID}" class="article-poll article-several block">
					<header>
						<h2>
							{list.QUESTION}
							<span class="actions">
								# IF C_IS_ADMIN #
								<a href="{PATH_TO_ROOT}/poll/admin_poll.php?id={list.ID}" title="{L_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
								<a href="{PATH_TO_ROOT}/poll/admin_poll.php?delete=1&amp;id={list.ID}&amp;token={TOKEN}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
								# ENDIF #
							</span>
						</h2>
					</header>
					<div class="content">
						# START list.result #
							<div>
								<h6>{list.result.ANSWERS} - ({list.result.NBRVOTE} {list.L_VOTE})</h6>
								<div class="progressbar-container" title="{list.result.PERCENT}%">
									<div class="progressbar-infos">{list.result.PERCENT}%</div>
									<div class="progressbar" style="width:{list.result.PERCENT}%"></div>
								</div>
								<br/>
							</div>
						# END list.result #
						<div>
							<span class="smaller left">{list.VOTE} {list.L_VOTE}</span>
							<span class="smaller right">{L_ON} : {list.DATE} </span>
							&nbsp;
						</div>
					</div>
				</article>
				# END list #
			</div>
			<footer># IF C_PAGINATION #<span class="right"># INCLUDE PAGINATION #</span># ENDIF #</footer>
		</section>
	# ENDIF #
