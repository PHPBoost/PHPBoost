	# IF C_POLL_MAIN #
		<section id="module-poll-main">
			<header>
				<div class="align-right controls">
					# IF C_IS_ADMIN # <a href="{U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
				</div>
				<h1>{L_POLL}</h1>
			</header>

			<div class="content align-center">
				{L_POLL_MAIN}
				# START list #
				<div class="poll-question-container">
					<a id="poll-question-{list.U_POLL_ID}" class="poll-question" href="{PATH_TO_ROOT}/poll/poll{list.U_POLL_ID}">
						<span>{list.QUESTION}</span>
						<img src="{PATH_TO_ROOT}/poll/poll.png" alt="{list.QUESTION}" />
					</a>
				</div>
				# END list #

				<p class="align-center">{U_ARCHIVE}</p>
			</div>
			<footer></footer>
		</section>
	# ENDIF #


	# IF C_POLL_VIEW #
		<form method="post" action="{PATH_TO_ROOT}/poll/poll{U_POLL_ACTION}">
			<section id="module-poll">
				<header>
					<div class="align-right">{L_MINI_POLL}</div>
					<h1>{QUESTION}</h1>
				</header>
					# INCLUDE message_helper #


				<div id="article-poll-{IDPOLL}" class="article-poll">
					# IF C_IS_ADMIN #
						<span class="controls">
							<a href="{U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
							<a href="{U_DEL}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
						</span>
					# ENDIF #
					<div class="content">
						# IF C_POLL_QUESTION #
						<div>
							# START radio #
								<p class="poll-question-select"><label class="radio"><input type="{radio.TYPE}" name="radio" value="{radio.NAME}"> <span>{radio.ANSWERS}</span></label></p>
							# END radio #

							# START checkbox #
								<p class="poll-question-select"><label class="checkbox"><input type="{checkbox.TYPE}" name="{checkbox.NAME}" value="{checkbox.NAME}"> <span>{checkbox.ANSWERS}</span></label></p>
							# END checkbox #

							<p class="align-center">
								<button type="submit" class="button submit" name="valid_poll" value="{L_VOTE}">{L_VOTE}</button>
								<input type="hidden" name="token" value="{TOKEN}">
							</p>
							<p class="align-center">
								<a class="small" href="{PATH_TO_ROOT}/poll/poll{U_POLL_RESULT}">{L_RESULT}</a>
							</p>
						</div>
						# ENDIF #

						# IF C_POLL_RESULTS #
							# IF C_DISPLAY_RESULTS #
								# START result #
									<div>
										<h6>{result.ANSWERS} - ({result.NBRVOTE} {L_VOTE})</h6>
										<div class="progressbar-container" aria-label="{result.PERCENT}%">
											<div class="progressbar-infos">{result.PERCENT}%</div>
											<div class="progressbar" style="width:{result.PERCENT}%;"></div>
										</div>
									</div>
								# END result #
								<div>
									<span class="smaller align-left">{VOTES} {L_VOTE}</span>
									<span class="smaller align-right">${LangLoader::get_message('on', 'main')} : {DATE} </span>
									&nbsp;
								</div>
							# ELSE #
								<div class="message-helper bgc notice"># IF C_NO_VOTE #{L_NO_VOTE}# ELSE #{L_RESULTS_NOT_DISPLAYED_YET}# ENDIF #</div>
							# ENDIF #
						# ENDIF #
					</div>
					<footer></footer>
				</div>
				<footer></footer>
			</section>
		</form>
	# ENDIF #


	# IF C_POLL_ARCHIVES #
		<section id="module-poll-archives">
			<header>
				<h1>{L_ARCHIVE}</h1>
				# IF C_PAGINATION #<span class="align-right"># INCLUDE PAGINATION #</span># ENDIF #
			</header>
			<div class="content">
				# START list #
				<article id="article-poll-{list.ID}" class="article-poll article-several block">
					<header>
						<h2>
							{list.QUESTION}
							<span class="controls">
								# IF C_IS_ADMIN #
								<a href="{list.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
								<a href="{list.U_DEL}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
								# ENDIF #
							</span>
						</h2>
					</header>
					<div class="content">
						# START list.result #
							<div>
								<h6>{list.result.ANSWERS} - ({list.result.NBRVOTE} {list.L_VOTE})</h6>
								<div class="progressbar-container" aria-label="{list.result.PERCENT}%">
									<div class="progressbar-infos">{list.result.PERCENT}%</div>
									<div class="progressbar" style="width:{list.result.PERCENT}%"></div>
								</div>
							</div>
						# END list.result #
						<div>
							<span class="smaller align-left">{list.VOTE} {list.L_VOTE}</span>
							<span class="smaller align-right">${LangLoader::get_message('on', 'main')} : {list.DATE} </span>
							&nbsp;
						</div>
					</div>
				</article>
				# END list #
			</div>
			<footer># IF C_PAGINATION #<span class="align-right"># INCLUDE PAGINATION #</span># ENDIF #</footer>
		</section>
	# ENDIF #
